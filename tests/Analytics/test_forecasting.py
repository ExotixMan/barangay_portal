import importlib.util
import json
from pathlib import Path
import unittest
from unittest.mock import patch

import numpy as np
import pandas as pd
from sqlalchemy import create_engine

spec = importlib.util.spec_from_file_location('bisig_analytics', Path(__file__).resolve().parents[2] / 'analytics/analytics.py')
a = importlib.util.module_from_spec(spec)
spec.loader.exec_module(a)


class ForecastingTests(unittest.TestCase):
    def setUp(self):
        self.start = pd.Timestamp('2026-11-15')

    def series(self, days=150, value=5):
        return pd.DataFrame({'ds': pd.date_range(end=self.start - pd.Timedelta(days=1), periods=days), 'y': [value] * days})

    def test_manila_timestamp_conversion_and_current_day_exclusion(self):
        df = pd.DataFrame({'id': [1, 2, 3], 'date': ['2026-11-13T16:30:00Z', '2026-11-14T16:30:00Z', '2026-11-16T00:00:00Z']})
        df['date'] = a.normalize_datetime_column(df['date'])
        daily = a.make_daily_series(df, 'date', self.start)
        self.assertEqual(daily['y'].sum(), 1)
        self.assertEqual(daily.iloc[-1]['ds'], pd.Timestamp('2026-11-14'))

    def test_training_features_match_future_features_and_trend_continues(self):
        df = self.series(800)
        df['y'] = np.arange(800) % 8
        training = a.build_training_frame(df, 90)
        next_row = a.build_future_feature(df.set_index('ds')['y'], self.start, 800)
        self.assertEqual(training.iloc[-1]['trend_index'] + 1, next_row.iloc[0]['trend_index'])
        # Compare exactly the same day's training and prediction features.
        row = training.iloc[-1]
        future = a.build_future_feature(df.set_index('ds')['y'], row['ds'], row['trend_index'])
        np.testing.assert_allclose(row[a.feature_columns()].to_numpy(dtype=float), future.iloc[0].to_numpy(dtype=float))

    def test_future_records_do_not_leak_into_model_training(self):
        df = self.series(10, 2)
        contaminated = pd.concat([df, pd.DataFrame({'ds': [self.start], 'y': [100000]})], ignore_index=True)
        expected = a.forecast_with_features(df, 7, 90, self.start)
        actual = a.forecast_with_features(contaminated, 7, 90, self.start)
        pd.testing.assert_frame_equal(expected, actual)

    def test_empty_history_has_explicit_status_and_exact_horizons(self):
        output = a.create_forecast_windows(pd.DataFrame(columns=['ds', 'y']), self.start)
        self.assertEqual(output['history_status'], 'no_history')
        for key, length in [('7_days', 7), ('30_days', 30), ('90_days', 90)]:
            self.assertEqual(len(output[key]), length)
            self.assertTrue(all(v == 0 for v in output[key].values()))

    def test_sparse_history_uses_fallback_despite_many_calendar_days(self):
        df = self.series(800, 0)
        df.loc[0, 'y'] = 1
        output = a.create_forecast_windows(df, self.start)
        self.assertEqual(output['model'], 'rolling_weekday_average')
        self.assertEqual(output['history_status'], 'limited_history')

    def test_real_model_fits_stable_counts_and_outputs_finite_nonnegative_values(self):
        result = a.forecast_with_features(self.series(), 7, 90, self.start)
        self.assertTrue(np.isfinite(result['y_hat']).all())
        self.assertLess(np.abs(result['y_hat'] - 5).mean(), 0.5)
        self.assertTrue((result['y_hat'] >= 0).all())

    def test_combined_forecast_matches_every_service_day_and_total(self):
        apps = {str(i): {'daily_forecast': a.create_forecast_windows(self.series(12, i + 1), self.start)} for i in range(3)}
        combined = a.sum_service_forecasts(apps, self.start)
        for key in a.FORECAST_HORIZONS:
            for date, value in combined[key].items():
                self.assertAlmostEqual(value, sum(s['daily_forecast'][key][date] for s in apps.values()))

    def test_database_pipeline_excludes_archives_and_draft_publishing(self):
        engine = create_engine('sqlite://')
        columns = {
            'announcements': ['id', 'published_at', 'category', 'status', 'is_featured', 'views'],
            'barangay_clearances': ['id', 'created_at', 'status'],
            'indigency_applications': ['id', 'created_at', 'status'],
            'residency_applications': ['id', 'created_at', 'status'],
            'blotter_reports': ['id', 'incident_date', 'report_type', 'status', 'location'],
            'events': ['id', 'event_date', 'location'],
            'projects': ['id', 'status', 'progress'],
            'residents': ['id', 'birthdate', 'phone_verified'],
        }
        for table, fields in columns.items():
            pd.DataFrame(columns=fields + ['deleted_at']).to_sql(table, engine, index=False)
        with engine.begin() as db:
            db.exec_driver_sql("INSERT INTO residency_applications VALUES (1, '2026-11-14 09:00:00', 'processing', NULL), (2, '2026-11-14 09:00:00', 'processing', '2026-11-14')")
            db.exec_driver_sql("INSERT INTO announcements VALUES (1, '2026-11-14', 'general', 'draft', 0, 0, NULL)")
        output = a.generate_analytics(engine, self.start)
        self.assertEqual(output['request_forecast']['daily_forecast']['historical_count'], 1)
        self.assertEqual(output['announcements']['daily_forecast']['historical_count'], 0)
        self.assertEqual(output['applications']['residency_applications']['status_distribution'], {'processing': 1})
        json.dumps(output, allow_nan=False)
        engine.dispose()


if __name__ == '__main__':
    unittest.main()
