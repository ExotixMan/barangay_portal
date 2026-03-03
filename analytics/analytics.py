import json
import os

import pandas as pd
import numpy as np
import json
from statsmodels.tsa.holtwinters import ExponentialSmoothing
from statsmodels.tsa.statespace.sarimax import SARIMAX
from sqlalchemy import create_engine
from urllib.parse import quote_plus
import warnings
warnings.filterwarnings("ignore")

# ================= DATABASE CONNECTION (POSTGRESQL) =================

username = "postgres.pxzxgegygwggabbtxfyj"
password = quote_plus(os.getenv("DB_PASSWORD"))

host = "aws-1-ap-southeast-1.pooler.supabase.com"
port = "6543"
database = "postgres"

DATABASE_URL = f"postgresql+psycopg2://{username}:{password}@{host}:{port}/{database}?sslmode=require"

engine = create_engine(DATABASE_URL, pool_pre_ping=True)

# =====================================================================
# ===================== ANNOUNCEMENTS ANALYTICS ========================
# =====================================================================

ann = pd.read_sql("SELECT * FROM announcements", engine)
ann['published_at'] = pd.to_datetime(ann['published_at'], errors='coerce')

# Daily announcements
ann_daily = (
    ann.dropna(subset=['published_at'])
       .assign(ds=lambda d: d['published_at'].dt.floor('D'))
       .groupby('ds', as_index=False)
       .agg(count=('id','count'))
)

ann_daily = ann_daily.set_index('ds').asfreq('D', fill_value=0).reset_index()
ann_daily['y'] = ann_daily['count'].astype(float)

def forecast_es(ts_df, periods=30):
    ts = ts_df.set_index('ds')['y'].asfreq('D').fillna(0)
    if len(ts) < 2:
        last = float(ts.iloc[-1]) if len(ts)==1 else 0
        idx = pd.date_range(ts.index[-1] + pd.Timedelta(days=1), periods=periods)
        return pd.DataFrame({'ds': idx, 'y_hat': [last]*periods})
    model = ExponentialSmoothing(ts, trend='add').fit()
    pred = model.forecast(periods)
    idx = pd.date_range(ts.index[-1] + pd.Timedelta(days=1), periods=periods)
    return pd.DataFrame({'ds': idx, 'y_hat': pred.values})

ann_forecast = forecast_es(ann_daily[['ds','y']], 30)

announcements_analytics = {
    "by_category": ann['category'].value_counts().to_dict(),
    "by_status": ann['status'].value_counts().to_dict(),
    "featured_count": int(ann['is_featured'].sum()),
    "total_views": int(ann['views'].sum())
}

# =====================================================================
# ===================== APPLICATIONS FORECASTING =======================
# =====================================================================

applications_tables = {
    "barangay_clearances": "created_at",
    "indigency_applications": "created_at",
    "residency_applications": "created_at"
}

applications_output = {}

for table, date_field in applications_tables.items():
    df = pd.read_sql(f"SELECT * FROM {table}", engine)
    df[date_field] = pd.to_datetime(df[date_field], errors='coerce')

    daily = (
        df.dropna(subset=[date_field])
          .assign(ds=lambda d: d[date_field].dt.floor('D'))
          .groupby('ds', as_index=False)
          .agg(count=('id','count'))
    )

    daily = daily.set_index('ds').asfreq('D', fill_value=0).reset_index()
    daily['y'] = daily['count'].astype(float)

    fc = forecast_es(daily[['ds','y']], 30)

    applications_output[table] = {
        "daily_forecast": {
            d.strftime("%b %d, %Y"): round(float(v),1)
            for d,v in zip(fc['ds'], fc['y_hat'])
        },
        "status_distribution": df['status'].value_counts().to_dict()
    }

# =====================================================================
# ===================== BLOTTER REPORT FORECASTING =====================
# =====================================================================

blotter = pd.read_sql("SELECT * FROM blotter_reports", engine)
blotter['incident_date'] = pd.to_datetime(blotter['incident_date'], errors='coerce')

blotter_daily = (
    blotter.dropna(subset=['incident_date'])
        .assign(ds=lambda d: d['incident_date'].dt.floor('D'))
        .groupby('ds', as_index=False)
        .agg(count=('id','count'))
)

blotter_daily = blotter_daily.set_index('ds').asfreq('D', fill_value=0).reset_index()
blotter_daily['y'] = blotter_daily['count'].astype(float)

blotter_forecast = forecast_es(blotter_daily[['ds','y']], 30)

blotter_analytics = {
    "by_report_type": blotter['report_type'].value_counts().to_dict(),
    "by_status": blotter['status'].value_counts().to_dict(),
    "by_location": blotter['location'].value_counts().to_dict()
}

# =====================================================================
# ========================= EVENTS ANALYTICS ===========================
# =====================================================================

events = pd.read_sql("SELECT * FROM events", engine)
events['event_date'] = pd.to_datetime(events['event_date'], errors='coerce')

events_analytics = {
    "total_events": len(events),
    "upcoming_vs_past": events['type'].value_counts().to_dict(),
    "by_location": events['location'].value_counts().to_dict()
}

# =====================================================================
# ========================= PROJECT ANALYTICS ==========================
# =====================================================================

projects = pd.read_sql("SELECT * FROM projects", engine)

projects_analytics = {
    "status_distribution": projects['status'].value_counts().to_dict(),
    "average_progress": round(float(projects['progress'].mean()),1) if not projects.empty else 0
}

# =====================================================================
# ========================= RESIDENT ANALYTICS =========================
# =====================================================================

residents = pd.read_sql("SELECT * FROM residents", engine)
residents['birthdate'] = pd.to_datetime(residents['birthdate'], errors='coerce')

today = pd.Timestamp.today()
residents['age'] = residents['birthdate'].apply(
    lambda x: today.year - x.year if pd.notna(x) else None
)

age_distribution = residents['age'].dropna().astype(int).value_counts().sort_index().to_dict()

residents_analytics = {
    "total_residents": len(residents),
    "age_distribution": age_distribution,
    "verified_phones": int(residents['phone_verified'].sum())
}

# =====================================================================
# ============================= FINAL OUTPUT ===========================
# =====================================================================

output = {
    "announcements": {
        "daily_forecast": {
            d.strftime("%b %d, %Y"): round(float(v),1)
            for d,v in zip(ann_forecast['ds'], ann_forecast['y_hat'])
        },
        "analytics": announcements_analytics
    },
    "applications": applications_output,
    "blotter_reports": {
        "daily_forecast": {
            d.strftime("%b %d, %Y"): round(float(v),1)
            for d,v in zip(blotter_forecast['ds'], blotter_forecast['y_hat'])
        },
        "analytics": blotter_analytics
    },
    "events": events_analytics,
    "projects": projects_analytics,
    "residents": residents_analytics
}

# =====================================================================
# ============================= SAVE TO FILE ===========================
# =====================================================================

# Prevent negative forecasts (important)
def remove_negative_values(data):
    if isinstance(data, dict):
        return {k: remove_negative_values(v) for k, v in data.items()}
    elif isinstance(data, list):
        return [remove_negative_values(v) for v in data]
    elif isinstance(data, (int, float)):
        return round(max(0, data), 1)
    else:
        return data

clean_output = remove_negative_values(output)

# Save inside public/analytics
base_dir = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
output_path = os.path.join(base_dir, "public", "analytics", "forecast.json")

os.makedirs(os.path.dirname(output_path), exist_ok=True)

with open(output_path, "w", encoding="utf-8") as f:
    json.dump(clean_output, f, indent=4)

print("Forecast saved successfully to:", output_path)