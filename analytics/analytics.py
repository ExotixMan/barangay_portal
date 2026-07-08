import json
import os
import warnings
from pathlib import Path
from urllib.parse import quote_plus

import numpy as np
import pandas as pd
from sqlalchemy import create_engine, inspect, text
from statsmodels.tsa.holtwinters import ExponentialSmoothing

from datetime import datetime
from zoneinfo import ZoneInfo

warnings.filterwarnings("ignore")


# Forecast horizon used by the dashboard range chips.
# Keep this at 90 so the dashboard can render 7D, 30D, and 90D forecasts
# from the same forecast.json file. You may override using FORECAST_DAYS=120.
FORECAST_DAYS = max(90, int(os.getenv("FORECAST_DAYS", "90")))
FORECAST_HORIZONS = [7, 30, 90]


# =====================================================================
# DATABASE CONNECTION (POSTGRESQL / SUPABASE)
# =====================================================================

DB_USER = os.getenv("DB_USERNAME", "postgres.pxzxgegygwggabbtxfyj")
DB_PASSWORD_RAW = os.getenv("DB_PASSWORD", "")
DB_HOST = os.getenv("DB_HOST", "aws-1-ap-southeast-1.pooler.supabase.com")
DB_PORT = os.getenv("DB_PORT", "6543")
DB_DATABASE = os.getenv("DB_DATABASE", "postgres")

if not DB_PASSWORD_RAW:
    raise RuntimeError(
        "DB_PASSWORD is missing. Add it to your .env or environment variables before running this script."
    )

DB_PASSWORD = quote_plus(DB_PASSWORD_RAW)
DATABASE_URL = (
    f"postgresql+psycopg2://{DB_USER}:{DB_PASSWORD}@{DB_HOST}:{DB_PORT}/{DB_DATABASE}?sslmode=require"
)

engine = create_engine(DATABASE_URL, pool_pre_ping=True)
inspector = inspect(engine)


# =====================================================================
# HELPERS
# =====================================================================

def table_exists(table_name: str) -> bool:
    return inspector.has_table(table_name)


def get_columns(table_name: str) -> set[str]:
    if not table_exists(table_name):
        return set()
    return {column["name"] for column in inspector.get_columns(table_name)}


def read_table(table_name: str, include_trashed: bool = False) -> pd.DataFrame:
    """Read a table safely. Returns empty DataFrame if table does not exist."""
    if not table_exists(table_name):
        print(f"Warning: table '{table_name}' does not exist. Using empty data.")
        return pd.DataFrame()

    columns = get_columns(table_name)
    query = f"SELECT * FROM {table_name}"

    # Exclude soft-deleted records by default.
    if not include_trashed and "deleted_at" in columns:
        query += " WHERE deleted_at IS NULL"

    try:
        return pd.read_sql(query, engine)
    except Exception as exc:
        print(f"Warning: failed to read table '{table_name}': {exc}")
        return pd.DataFrame()


def safe_datetime(df: pd.DataFrame, column: str) -> pd.Series:
    if df.empty or column not in df.columns:
        return pd.Series(dtype="datetime64[ns]")
    return pd.to_datetime(df[column], errors="coerce")


def safe_value_counts(df: pd.DataFrame, column: str, limit: int | None = None) -> dict:
    if df.empty or column not in df.columns:
        return {}

    counts = df[column].fillna("Unknown").astype(str).value_counts()
    if limit:
        counts = counts.head(limit)

    return {str(k): int(v) for k, v in counts.to_dict().items()}


def safe_sum_bool(df: pd.DataFrame, column: str) -> int:
    if df.empty or column not in df.columns:
        return 0

    series = df[column]
    if series.dtype == bool:
        return int(series.fillna(False).sum())

    return int(
        series.fillna(False)
        .astype(str)
        .str.lower()
        .isin(["1", "true", "yes", "y", "t"])
        .sum()
    )


def safe_numeric_sum(df: pd.DataFrame, column: str) -> int:
    if df.empty or column not in df.columns:
        return 0
    return int(pd.to_numeric(df[column], errors="coerce").fillna(0).sum())


def count_matching_statuses(df: pd.DataFrame, keywords: list[str]) -> int:
    """Count rows whose status contains any keyword. Safe for missing/empty status columns."""
    if df.empty or "status" not in df.columns:
        return 0

    status = df["status"].fillna("").astype(str).str.lower().str.replace("_", " ", regex=False)
    mask = False
    for keyword in keywords:
        mask = mask | status.str.contains(keyword.lower(), na=False)
    return int(mask.sum())


def dataframe_len(df: pd.DataFrame) -> int:
    return int(len(df)) if isinstance(df, pd.DataFrame) else 0


def daily_counts(df: pd.DataFrame, date_field: str) -> pd.DataFrame:
    """Create ds/y daily count dataframe. Safe even with no data."""
    if df.empty or date_field not in df.columns:
        return pd.DataFrame(columns=["ds", "y"])

    working = df.copy()
    working[date_field] = pd.to_datetime(working[date_field], errors="coerce")
    working = working.dropna(subset=[date_field])

    if working.empty:
        return pd.DataFrame(columns=["ds", "y"])

    daily = (
        working.assign(ds=lambda d: d[date_field].dt.floor("D"))
        .groupby("ds", as_index=False)
        .agg(count=("id", "count") if "id" in working.columns else (date_field, "count"))
    )

    full_index = pd.date_range(daily["ds"].min(), daily["ds"].max(), freq="D")
    daily = daily.set_index("ds").reindex(full_index, fill_value=0).rename_axis("ds").reset_index()
    daily["y"] = daily["count"].astype(float)

    return daily[["ds", "y"]]


def forecast_es(ts_df: pd.DataFrame, periods: int = 30) -> pd.DataFrame:
    """
    Robust forecast:
    - If no data: returns 0 forecast starting tomorrow.
    - If very little data: repeats the recent average/last value.
    - If model fails: falls back safely.
    """
    today = pd.Timestamp.today().normalize()

    if ts_df.empty or "ds" not in ts_df.columns or "y" not in ts_df.columns:
        idx = pd.date_range(today + pd.Timedelta(days=1), periods=periods, freq="D")
        return pd.DataFrame({"ds": idx, "y_hat": np.zeros(periods)})

    ts_df = ts_df.copy()
    ts_df["ds"] = pd.to_datetime(ts_df["ds"], errors="coerce")
    ts_df["y"] = pd.to_numeric(ts_df["y"], errors="coerce").fillna(0)
    ts_df = ts_df.dropna(subset=["ds"]).sort_values("ds")

    if ts_df.empty:
        idx = pd.date_range(today + pd.Timedelta(days=1), periods=periods, freq="D")
        return pd.DataFrame({"ds": idx, "y_hat": np.zeros(periods)})

    ts = ts_df.set_index("ds")["y"].asfreq("D").fillna(0)
    start_date = ts.index[-1] + pd.Timedelta(days=1)
    idx = pd.date_range(start_date, periods=periods, freq="D")

    # Too few records or flat data: avoid unstable Holt-Winters output.
    if len(ts) < 7 or ts.sum() == 0 or ts.nunique() <= 1:
        fallback_value = float(ts.tail(7).mean()) if len(ts) else 0.0
        return pd.DataFrame({"ds": idx, "y_hat": np.full(periods, fallback_value)})

    try:
        model = ExponentialSmoothing(
            ts,
            trend="add",
            damped_trend=True,
            seasonal=None,
            initialization_method="estimated",
        ).fit(optimized=True)
        pred = model.forecast(periods)
        values = np.maximum(pred.values.astype(float), 0)
    except Exception as exc:
        print(f"Warning: forecast model fallback used: {exc}")
        fallback_value = float(ts.tail(7).mean()) if len(ts) else 0.0
        values = np.full(periods, fallback_value)

    return pd.DataFrame({"ds": idx, "y_hat": values})


def forecast_to_dict(fc: pd.DataFrame) -> dict:
    if fc.empty:
        return {}
    return {
        d.strftime("%b %d, %Y"): round(float(v), 1)
        for d, v in zip(fc["ds"], fc["y_hat"])
    }


def forecast_horizon_summary(fc: pd.DataFrame) -> dict:
    """Return 7/30/90-day totals so dashboard cards can summarize each range."""
    if fc.empty or "y_hat" not in fc.columns:
        return {
            f"{days}_days": {"days": days, "total": 0, "daily_average": 0, "peak": 0}
            for days in FORECAST_HORIZONS
        }

    values = pd.to_numeric(fc["y_hat"], errors="coerce").fillna(0).clip(lower=0)
    result = {}
    for days in FORECAST_HORIZONS:
        window = values.head(days)
        result[f"{days}_days"] = {
            "days": int(days),
            "total": round(float(window.sum()), 1),
            "daily_average": round(float(window.mean()), 1) if len(window) else 0,
            "peak": round(float(window.max()), 1) if len(window) else 0,
        }
    return result


def combine_forecasts(forecasts: list[pd.DataFrame]) -> pd.DataFrame:
    """Combine several forecast dataframes by date for dashboard total demand charts."""
    series_list = []
    for fc in forecasts:
        if isinstance(fc, pd.DataFrame) and not fc.empty and {"ds", "y_hat"}.issubset(fc.columns):
            series_list.append(
                fc.assign(ds=pd.to_datetime(fc["ds"], errors="coerce"))
                  .dropna(subset=["ds"])
                  .set_index("ds")["y_hat"]
            )

    if not series_list:
        idx = pd.date_range(pd.Timestamp.today().normalize() + pd.Timedelta(days=1), periods=FORECAST_DAYS, freq="D")
        return pd.DataFrame({"ds": idx, "y_hat": np.zeros(FORECAST_DAYS)})

    combined = pd.concat(series_list, axis=1).fillna(0).sum(axis=1).sort_index()
    return combined.reset_index().rename(columns={"index": "ds", 0: "y_hat"})


def clean_json_value(value):
    """Convert numpy/pandas values and prevent negative numeric forecasts."""
    if isinstance(value, dict):
        return {str(k): clean_json_value(v) for k, v in value.items()}
    if isinstance(value, list):
        return [clean_json_value(v) for v in value]
    if isinstance(value, (np.integer,)):
        return int(value)
    if isinstance(value, (np.floating, float)):
        if np.isnan(value) or np.isinf(value):
            return 0
        return round(max(0, float(value)), 1)
    if isinstance(value, (int,)):
        return max(0, int(value))
    if pd.isna(value):
        return None
    return value


# =====================================================================
# ANNOUNCEMENTS ANALYTICS
# =====================================================================

ann = read_table("announcements")
ann_daily = daily_counts(ann, "published_at")
ann_forecast = forecast_es(ann_daily, FORECAST_DAYS)

announcements_analytics = {
    "by_category": safe_value_counts(ann, "category"),
    "by_status": safe_value_counts(ann, "status"),
    "featured_count": safe_sum_bool(ann, "is_featured"),
    "total_views": safe_numeric_sum(ann, "views"),
    "historical_count": int(len(ann)),
}


# =====================================================================
# APPLICATIONS FORECASTING
# =====================================================================

applications_tables = {
    "barangay_clearances": "created_at",
    "indigency_applications": "created_at",
    "residency_applications": "created_at",
}

applications_output = {}
application_frames = {}
application_forecasts = {}

for table, date_field in applications_tables.items():
    df = read_table(table)
    application_frames[table] = df
    daily = daily_counts(df, date_field)
    fc = forecast_es(daily, FORECAST_DAYS)
    application_forecasts[table] = fc

    applications_output[table] = {
        "daily_forecast": forecast_to_dict(fc),
        "forecast_ranges": forecast_horizon_summary(fc),
        "historical_count": int(len(df)),
        "status_distribution": safe_value_counts(df, "status"),
    }


# =====================================================================
# BLOTTER REPORT FORECASTING
# =====================================================================

blotter = read_table("blotter_reports")
blotter_daily = daily_counts(blotter, "incident_date")
blotter_forecast = forecast_es(blotter_daily, FORECAST_DAYS)

blotter_analytics = {
    "by_report_type": safe_value_counts(blotter, "report_type"),
    "by_status": safe_value_counts(blotter, "status"),
    "by_location": safe_value_counts(blotter, "location", limit=10),
    "historical_count": int(len(blotter)),
}


# =====================================================================
# EVENTS ANALYTICS
# =====================================================================

events = read_table("events")

# Use event_date when possible to compute upcoming/past instead of relying on a type field.
if not events.empty and "event_date" in events.columns:
    event_dates = safe_datetime(events, "event_date")
    now = pd.Timestamp.today()
    upcoming_count = int((event_dates >= now).sum())
    past_count = int((event_dates < now).sum())
else:
    upcoming_count = 0
    past_count = 0

events_analytics = {
    "total_events": int(len(events)),
    "upcoming_vs_past": {
        "upcoming": upcoming_count,
        "past": past_count,
    },
    "by_location": safe_value_counts(events, "location", limit=10),
}


# =====================================================================
# PROJECT ANALYTICS
# =====================================================================

projects = read_table("projects")
progress_series = (
    pd.to_numeric(projects["progress"], errors="coerce")
    if not projects.empty and "progress" in projects.columns
    else pd.Series(dtype=float)
)

projects_analytics = {
    "status_distribution": safe_value_counts(projects, "status"),
    "average_progress": round(float(progress_series.mean()), 1) if not progress_series.empty and not pd.isna(progress_series.mean()) else 0,
    "historical_count": int(len(projects)),
}


# =====================================================================
# RESIDENT ANALYTICS
# =====================================================================

residents = read_table("residents")

if not residents.empty and "birthdate" in residents.columns:
    birthdates = safe_datetime(residents, "birthdate")
    today = pd.Timestamp.today()
    ages = birthdates.apply(
        lambda x: today.year - x.year - ((today.month, today.day) < (x.month, x.day)) if pd.notna(x) else np.nan
    )
    age_distribution = ages.dropna().astype(int).value_counts().sort_index().to_dict()
    age_distribution = {str(k): int(v) for k, v in age_distribution.items()}
else:
    age_distribution = {}

residents_analytics = {
    "total_residents": int(len(residents)),
    "age_distribution": age_distribution,
    "verified_phones": safe_sum_bool(residents, "phone_verified"),
}


# =====================================================================
# DASHBOARD OVERVIEW DATA
# Data used by the interactive dashboard charts such as:
# - Service Pipeline Snapshot
# - Data Readiness
# - Request Types Distribution
# =====================================================================

application_total_count = int(sum(dataframe_len(df) for df in application_frames.values()))
pending_requests_total = int(
    sum(
        count_matching_statuses(df, ["pending", "submitted", "processing", "for review", "review"])
        for df in application_frames.values()
    )
)

open_reports_total = count_matching_statuses(
    blotter,
    ["open", "pending", "ongoing", "investigation", "under investigation", "active"],
)

service_pipeline = {
    "residents": dataframe_len(residents),
    "pending_requests": pending_requests_total,
    "open_reports": open_reports_total,
    "upcoming_events": upcoming_count,
}

request_type_totals = {
    "barangay_clearances": dataframe_len(application_frames.get("barangay_clearances", pd.DataFrame())),
    "indigency_applications": dataframe_len(application_frames.get("indigency_applications", pd.DataFrame())),
    "residency_applications": dataframe_len(application_frames.get("residency_applications", pd.DataFrame())),
}

combined_application_forecast = combine_forecasts(list(application_forecasts.values()))

module_counts = {
    "residents": dataframe_len(residents),
    "requests": application_total_count,
    "reports": dataframe_len(blotter),
    "events": dataframe_len(events),
    "projects": dataframe_len(projects),
    "announcements": dataframe_len(ann),
}

data_readiness = {
    module: {
        "records": int(count),
        "ready": bool(count > 0),
        "score": 100 if count > 0 else 0,
    }
    for module, count in module_counts.items()
}


# =====================================================================
# FINAL OUTPUT
# =====================================================================

output = {
    "dashboard_overview": {
        "forecast_days": FORECAST_DAYS,
        "forecast_horizons": FORECAST_HORIZONS,
        "service_pipeline": service_pipeline,
        "data_readiness": data_readiness,
        "request_type_totals": request_type_totals,
        "combined_application_forecast": forecast_to_dict(combined_application_forecast),
        "combined_application_ranges": forecast_horizon_summary(combined_application_forecast),
        "module_counts": module_counts,
    },
    "announcements": {
        "daily_forecast": forecast_to_dict(ann_forecast),
        "forecast_ranges": forecast_horizon_summary(ann_forecast),
        "analytics": announcements_analytics,
    },
    "applications": applications_output,
    "blotter_reports": {
        "daily_forecast": forecast_to_dict(blotter_forecast),
        "forecast_ranges": forecast_horizon_summary(blotter_forecast),
        "analytics": blotter_analytics,
    },
    "events": events_analytics,
    "projects": projects_analytics,
    "residents": residents_analytics,
}

clean_output = clean_json_value(output)

output["meta"] = {
    "generated_at": datetime.now(ZoneInfo("Asia/Manila")).strftime("%b %d, %Y %I:%M %p PHT")
}

# =====================================================================
# SAVE TO FILE
# =====================================================================

base_dir = Path(__file__).resolve().parent.parent
output_path = base_dir / "public" / "analytics" / "forecast.json"
output_path.parent.mkdir(parents=True, exist_ok=True)

with output_path.open("w", encoding="utf-8") as f:
    json.dump(clean_output, f, indent=4, ensure_ascii=False)

print("Forecast saved successfully to:", output_path)
