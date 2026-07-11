import json
import os
import warnings
from urllib.parse import quote_plus

import numpy as np
import pandas as pd
from sqlalchemy import create_engine

try:
    from xgboost import XGBRegressor

    FORECAST_MODEL = "xgboost"
except ImportError:
    from sklearn.ensemble import HistGradientBoostingRegressor

    FORECAST_MODEL = "hist_gradient_boosting"

warnings.filterwarnings("ignore")

APP_TIMEZONE = "Asia/Manila"
FORECAST_HORIZONS = {
    "7_days": {"periods": 7, "lookback_days": 90},
    "30_days": {"periods": 30, "lookback_days": 365},
    "90_days": {"periods": 90, "lookback_days": 730},
}
LAGS = (1, 7, 14, 28)
ROLLING_WINDOWS = (7, 14, 30)
MIN_MODEL_ROWS = 30

username = "postgres.pxzxgegygwggabbtxfyj"
raw_password = os.getenv("DB_PASSWORD")
if not raw_password:
    raise RuntimeError("DB_PASSWORD environment variable is not set.")

password = quote_plus(raw_password)
host = "aws-1-ap-southeast-1.pooler.supabase.com"
port = "6543"
database = "postgres"

DATABASE_URL = (
    f"postgresql+psycopg2://{username}:{password}"
    f"@{host}:{port}/{database}?sslmode=require"
)

engine = create_engine(DATABASE_URL, pool_pre_ping=True)


def local_today():
    return pd.Timestamp.now(tz=APP_TIMEZONE).tz_localize(None).normalize()


def normalize_datetime_column(series):
    parsed = pd.to_datetime(series, errors="coerce")

    if getattr(parsed.dt, "tz", None) is not None:
        parsed = parsed.dt.tz_convert(APP_TIMEZONE).dt.tz_localize(None)

    return parsed


def make_daily_series(df, date_field, forecast_start=None):
    forecast_start = (
        pd.Timestamp(forecast_start).normalize()
        if forecast_start is not None
        else local_today()
    )
    training_end = forecast_start - pd.Timedelta(days=1)

    daily = (
        df.dropna(subset=[date_field])
        .assign(ds=lambda data: data[date_field].dt.floor("D"))
        .loc[lambda data: data["ds"] <= training_end]
        .groupby("ds", as_index=False)
        .agg(y=("id", "count"))
    )

    if daily.empty:
        return pd.DataFrame(columns=["ds", "y"])

    full_dates = pd.date_range(
        start=daily["ds"].min(),
        end=training_end,
        freq="D",
    )

    daily = (
        daily.set_index("ds")
        .reindex(full_dates, fill_value=0)
        .rename_axis("ds")
        .reset_index()
    )
    daily["y"] = daily["y"].astype(float)

    return daily[["ds", "y"]]


def combine_daily_series(series_list, forecast_start=None):
    forecast_start = (
        pd.Timestamp(forecast_start).normalize()
        if forecast_start is not None
        else local_today()
    )
    training_end = forecast_start - pd.Timedelta(days=1)

    valid_series = [data for data in series_list if not data.empty]
    if not valid_series:
        return pd.DataFrame(columns=["ds", "y"])

    start_date = min(data["ds"].min() for data in valid_series)
    full_dates = pd.date_range(start=start_date, end=training_end, freq="D")

    combined = pd.Series(0.0, index=full_dates)
    for data in valid_series:
        values = data.set_index("ds")["y"].reindex(full_dates, fill_value=0.0)
        combined = combined.add(values, fill_value=0.0)

    return pd.DataFrame({"ds": full_dates, "y": combined.to_numpy()})


def add_calendar_features(frame):
    result = frame.copy()
    dates = pd.to_datetime(result["ds"])

    result["day_of_week"] = dates.dt.dayofweek
    result["is_weekend"] = dates.dt.dayofweek.isin([5, 6]).astype(int)
    result["day_of_month"] = dates.dt.day
    result["week_of_year"] = dates.dt.isocalendar().week.astype(int)
    result["month"] = dates.dt.month
    result["quarter"] = dates.dt.quarter
    result["day_of_year"] = dates.dt.dayofyear

    return result


def build_training_frame(ts_df, lookback_days):
    if ts_df.empty:
        return pd.DataFrame()

    data = ts_df.sort_values("ds").copy()
    latest_date = data["ds"].max()
    earliest_date = latest_date - pd.Timedelta(
        days=lookback_days + max(ROLLING_WINDOWS) + max(LAGS)
    )
    data = data[data["ds"] >= earliest_date].reset_index(drop=True)

    for lag in LAGS:
        data[f"lag_{lag}"] = data["y"].shift(lag)

    shifted = data["y"].shift(1)
    for window in ROLLING_WINDOWS:
        data[f"ma_{window}"] = shifted.rolling(window=window).mean()
        data[f"std_{window}"] = shifted.rolling(window=window).std().fillna(0.0)

    data = add_calendar_features(data)
    data["trend_index"] = np.arange(len(data), dtype=float)

    requested_start = latest_date - pd.Timedelta(days=lookback_days - 1)
    data = data[data["ds"] >= requested_start]

    required = [
        *(f"lag_{lag}" for lag in LAGS),
        *(f"ma_{window}" for window in ROLLING_WINDOWS),
    ]

    return data.dropna(subset=required).reset_index(drop=True)


def feature_columns():
    return [
        *(f"lag_{lag}" for lag in LAGS),
        *(f"ma_{window}" for window in ROLLING_WINDOWS),
        *(f"std_{window}" for window in ROLLING_WINDOWS),
        "day_of_week",
        "is_weekend",
        "day_of_month",
        "week_of_year",
        "month",
        "quarter",
        "day_of_year",
        "trend_index",
    ]


def fit_forecast_model(training_frame):
    columns = feature_columns()
    x_train = training_frame[columns].astype(float)
    y_train = np.log1p(training_frame["y"].clip(lower=0).astype(float))

    if FORECAST_MODEL == "xgboost":
        model = XGBRegressor(
            objective="reg:squarederror",
            n_estimators=350,
            learning_rate=0.03,
            max_depth=3,
            min_child_weight=2,
            subsample=0.85,
            colsample_bytree=0.9,
            reg_alpha=0.05,
            reg_lambda=1.0,
            random_state=42,
            n_jobs=2,
        )
    else:
        model = HistGradientBoostingRegressor(
            learning_rate=0.05,
            max_iter=300,
            max_depth=4,
            l2_regularization=1.0,
            random_state=42,
        )

    model.fit(x_train, y_train)
    return model


def fallback_prediction(history, forecast_date):
    previous = history[history.index < forecast_date].astype(float)
    if previous.empty:
        return 0.0

    recent_7 = float(previous.tail(7).mean())
    recent_30 = float(previous.tail(30).mean())
    same_weekday = previous[previous.index.dayofweek == forecast_date.dayofweek].tail(8)
    weekday_average = (
        float(same_weekday.mean()) if not same_weekday.empty else recent_30
    )

    return max(0.0, (0.50 * weekday_average) + (0.30 * recent_7) + (0.20 * recent_30))


def build_future_feature(history, forecast_date, trend_index):
    previous = history[history.index < forecast_date].sort_index().astype(float)
    fallback = float(previous.tail(30).mean()) if not previous.empty else 0.0

    row = {"ds": forecast_date}

    for lag in LAGS:
        lag_date = forecast_date - pd.Timedelta(days=lag)
        row[f"lag_{lag}"] = float(history.get(lag_date, fallback))

    for window in ROLLING_WINDOWS:
        values = previous.tail(window)
        row[f"ma_{window}"] = float(values.mean()) if not values.empty else fallback
        row[f"std_{window}"] = (
            float(values.std(ddof=0)) if len(values) > 1 else 0.0
        )

    row_frame = add_calendar_features(pd.DataFrame([row]))
    row_frame["trend_index"] = float(trend_index)

    return row_frame[feature_columns()].astype(float)


def forecast_with_features(ts_df, periods, lookback_days, forecast_start=None):
    forecast_start = (
        pd.Timestamp(forecast_start).normalize()
        if forecast_start is not None
        else local_today()
    )
    future_dates = pd.date_range(start=forecast_start, periods=periods, freq="D")

    if ts_df.empty:
        return pd.DataFrame({"ds": future_dates, "y_hat": np.zeros(periods)})

    series = (
        ts_df.sort_values("ds")
        .drop_duplicates(subset=["ds"], keep="last")
        .set_index("ds")["y"]
        .astype(float)
    )

    training_frame = build_training_frame(ts_df, lookback_days)
    use_model = len(training_frame) >= MIN_MODEL_ROWS
    model = fit_forecast_model(training_frame) if use_model else None

    history = series.copy()
    predictions = []
    base_trend_index = len(training_frame)

    for offset, forecast_date in enumerate(future_dates):
        if model is None:
            prediction = fallback_prediction(history, forecast_date)
        else:
            future_x = build_future_feature(
                history=history,
                forecast_date=forecast_date,
                trend_index=base_trend_index + offset,
            )
            log_prediction = float(model.predict(future_x)[0])
            prediction = float(np.expm1(log_prediction))

            if not np.isfinite(prediction):
                prediction = fallback_prediction(history, forecast_date)

        prediction = max(0.0, prediction)
        predictions.append(prediction)
        history.loc[forecast_date] = prediction

    return pd.DataFrame({"ds": future_dates, "y_hat": predictions})


def format_forecast(forecast_df):
    return {
        date.strftime("%b %d, %Y"): round(max(0.0, float(value)), 1)
        for date, value in zip(forecast_df["ds"], forecast_df["y_hat"])
    }


def create_forecast_windows(ts_df, forecast_start=None):
    forecast_start = (
        pd.Timestamp(forecast_start).normalize()
        if forecast_start is not None
        else local_today()
    )

    forecasts = {}
    averages = {}
    today_prediction = 0.0

    for name, config in FORECAST_HORIZONS.items():
        result = forecast_with_features(
            ts_df=ts_df,
            periods=config["periods"],
            lookback_days=config["lookback_days"],
            forecast_start=forecast_start,
        )
        forecasts[name] = format_forecast(result)
        averages[name] = round(float(result["y_hat"].mean()), 1)

        if name == "7_days" and not result.empty:
            today_prediction = round(float(result.iloc[0]["y_hat"]), 1)

    training_end = forecast_start - pd.Timedelta(days=1)

    return {
        **forecasts,
        "today": max(0.0, today_prediction),
        "average": averages,
        "model": FORECAST_MODEL if len(build_training_frame(ts_df, 90)) >= MIN_MODEL_ROWS else "rolling_weekday_average",
        "forecast_start_date": forecast_start.strftime("%Y-%m-%d"),
        "training_end_date": training_end.strftime("%Y-%m-%d"),
    }


forecast_start = local_today()

ann = pd.read_sql("SELECT * FROM announcements", engine)
ann["published_at"] = normalize_datetime_column(ann["published_at"])
ann_daily = make_daily_series(ann, "published_at", forecast_start)
ann_forecasts = create_forecast_windows(ann_daily, forecast_start)

announcements_analytics = {
    "by_category": ann["category"].value_counts().to_dict(),
    "by_status": ann["status"].value_counts().to_dict(),
    "featured_count": int(ann["is_featured"].fillna(False).sum()),
    "total_views": int(ann["views"].fillna(0).sum()),
}

applications_tables = {
    "barangay_clearances": "created_at",
    "indigency_applications": "created_at",
    "residency_applications": "created_at",
}

applications_output = {}
application_daily_series = []
combined_statuses = []

for table, date_field in applications_tables.items():
    df = pd.read_sql(f"SELECT * FROM {table}", engine)
    df[date_field] = normalize_datetime_column(df[date_field])

    daily = make_daily_series(df, date_field, forecast_start)
    application_daily_series.append(daily)

    if "status" in df.columns:
        combined_statuses.append(df["status"].dropna())

    applications_output[table] = {
        "daily_forecast": create_forecast_windows(daily, forecast_start),
        "status_distribution": df["status"].value_counts().to_dict(),
    }

all_requests_daily = combine_daily_series(application_daily_series, forecast_start)
all_request_statuses = (
    pd.concat(combined_statuses, ignore_index=True)
    if combined_statuses
    else pd.Series(dtype="object")
)

request_forecast = {
    "daily_forecast": create_forecast_windows(all_requests_daily, forecast_start),
    "status_distribution": all_request_statuses.value_counts().to_dict(),
}

blotter = pd.read_sql("SELECT * FROM blotter_reports", engine)
blotter["incident_date"] = normalize_datetime_column(blotter["incident_date"])
blotter_daily = make_daily_series(blotter, "incident_date", forecast_start)
blotter_forecasts = create_forecast_windows(blotter_daily, forecast_start)

blotter_analytics = {
    "by_report_type": blotter["report_type"].value_counts().to_dict(),
    "by_status": blotter["status"].value_counts().to_dict(),
    "by_location": blotter["location"].value_counts().to_dict(),
}

events = pd.read_sql("SELECT * FROM events", engine)
events["event_date"] = normalize_datetime_column(events["event_date"])

events_analytics = {
    "total_events": len(events),
    "upcoming_vs_past": events["type"].value_counts().to_dict(),
    "by_location": events["location"].value_counts().to_dict(),
}

projects = pd.read_sql("SELECT * FROM projects", engine)
projects_analytics = {
    "status_distribution": projects["status"].value_counts().to_dict(),
    "average_progress": (
        round(float(projects["progress"].mean()), 1)
        if not projects.empty
        else 0
    ),
}

residents = pd.read_sql("SELECT * FROM residents", engine)
residents["birthdate"] = normalize_datetime_column(residents["birthdate"])
today = local_today()

residents["age"] = residents["birthdate"].apply(
    lambda birthdate: (
        today.year
        - birthdate.year
        - ((today.month, today.day) < (birthdate.month, birthdate.day))
    )
    if pd.notna(birthdate)
    else None
)

age_distribution = (
    residents["age"]
    .dropna()
    .astype(int)
    .value_counts()
    .sort_index()
    .to_dict()
)

residents_analytics = {
    "total_residents": len(residents),
    "age_distribution": age_distribution,
    "verified_phones": int(residents["phone_verified"].fillna(False).sum()),
}

output = {
    "forecast_metadata": {
        "timezone": APP_TIMEZONE,
        "forecast_start_date": forecast_start.strftime("%Y-%m-%d"),
        "training_end_date": (forecast_start - pd.Timedelta(days=1)).strftime("%Y-%m-%d"),
        "current_day_actuals_excluded": True,
        "primary_model": FORECAST_MODEL,
    },
    "request_forecast": request_forecast,
    "announcements": {
        "daily_forecast": ann_forecasts,
        "analytics": announcements_analytics,
    },
    "applications": applications_output,
    "blotter_reports": {
        "daily_forecast": blotter_forecasts,
        "analytics": blotter_analytics,
    },
    "events": events_analytics,
    "projects": projects_analytics,
    "residents": residents_analytics,
}


def clean_json_values(data):
    if isinstance(data, dict):
        return {key: clean_json_values(value) for key, value in data.items()}

    if isinstance(data, list):
        return [clean_json_values(value) for value in data]

    if isinstance(data, (np.bool_, bool)):
        return bool(data)

    if isinstance(data, (np.integer, int)):
        return max(0, int(data))

    if isinstance(data, (np.floating, float)):
        if not np.isfinite(data):
            return 0.0
        return round(max(0.0, float(data)), 1)

    if data is None:
        return None

    try:
        if pd.isna(data):
            return None
    except (TypeError, ValueError):
        pass

    return data


clean_output = clean_json_values(output)

base_dir = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
output_path = os.path.join(base_dir, "public", "analytics", "forecast.json")
os.makedirs(os.path.dirname(output_path), exist_ok=True)

with open(output_path, "w", encoding="utf-8") as file:
    json.dump(clean_output, file, indent=4, ensure_ascii=False)

engine.dispose()
print("Forecast saved successfully to:", output_path)