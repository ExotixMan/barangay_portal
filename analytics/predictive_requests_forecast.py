import pandas as pd
import numpy as np
import json
from statsmodels.tsa.holtwinters import ExponentialSmoothing
from statsmodels.tsa.statespace.sarimax import SARIMAX
from sqlalchemy import create_engine
import warnings
warnings.filterwarnings("ignore")

#DB connection
db_user = "root"
db_pass = ""
db_host = "localhost"
db_name = "barangay_system"
engine = create_engine(f"mysql+pymysql://{db_user}:{db_pass}@{db_host}/{db_name}")

# ==================== REQUESTS FORECASTING ==================

#Load request data
df = pd.read_sql("SELECT * FROM requests", con=engine)

#Preprocessing 
df['date_submitted'] = pd.to_datetime(df['date_submitted'], errors='coerce')
df['approval_flag'] = df['remarks'].apply(lambda x: 1 if str(x).strip().lower() == 'approved' else 0)
df['request_type'] = df['request_type'].fillna('unknown')

#Daily time series
daily = (
    df.dropna(subset=['date_submitted'])
      .assign(ds=lambda d: d['date_submitted'].dt.floor('D'))
      .groupby('ds', as_index=False)
      .agg(
         requests_per_day=('request_id','count'),
         approvals_per_day=('approval_flag','sum')
      )
)

daily = daily.set_index('ds').asfreq('D', fill_value=0).reset_index()
daily['y_requests'] = daily['requests_per_day'].astype(float)
daily['y_approvals'] = daily['approvals_per_day'].astype(float)

#Forecasting functions
def forecast_requests_es(ts_df, periods=14):
    ts = ts_df.set_index('ds')['y_requests'].asfreq('D').fillna(0)
    if len(ts) < 2:
        last = float(ts.iloc[-1]) if len(ts)==1 else 0.0
        idx = pd.date_range(ts.index[-1] + pd.Timedelta(days=1), periods=periods)
        return pd.DataFrame({'ds': idx, 'y_hat': [last]*periods})
    model = ExponentialSmoothing(ts, trend='add', seasonal=None).fit()
    pred = model.forecast(periods)
    idx = pd.date_range(ts.index[-1] + pd.Timedelta(days=1), periods=periods)
    return pd.DataFrame({'ds': idx, 'y_hat': pred.values})

def forecast_approvals_sarima(ts_df, periods=14):
    ts = ts_df.set_index('ds')['y_approvals'].asfreq('D').fillna(0)
    if len(ts) < 2:
        last = float(ts.iloc[-1]) if len(ts)==1 else 0.0
        idx = pd.date_range(ts.index[-1] + pd.Timedelta(days=1), periods=periods)
        return pd.DataFrame({'ds': idx, 'yhat': [last]*periods})
    model = SARIMAX(ts, order=(1,0,1), seasonal_order=(0,0,0,0),
                    enforce_stationarity=False, enforce_invertibility=False).fit(disp=False)
    pred = model.get_forecast(steps=periods)
    idx = pd.date_range(ts.index[-1] + pd.Timedelta(days=1), periods=periods)
    return pd.DataFrame({'ds': idx, 'yhat': pred.predicted_mean.values})

#Daily forecasts
daily_forecast = forecast_requests_es(daily[['ds','y_requests']], periods=30)
approval_forecast = forecast_approvals_sarima(daily[['ds','y_approvals']], periods=30)

#Weekly forecasts by type
weekly_forecasts = {}
tmp = df.dropna(subset=['date_submitted']).copy()
if not tmp.empty:
    tmp['date'] = tmp['date_submitted'].dt.floor('D')
    demand = tmp.groupby(['date','request_type']).size().reset_index(name='count')
    demand['week'] = demand['date'].dt.to_period('W').apply(lambda r: r.start_time)
    weekly = demand.groupby(['week','request_type'], as_index=False)['count'].sum()
    weekly = weekly[weekly['request_type'] != 'unknown']

    top_types = weekly.groupby('request_type')['count'].sum().sort_values(ascending=False).head(5).index.tolist()

    def forecast_weekly_type(df_weekly, req_type, periods=12):
        series = df_weekly[df_weekly['request_type'] == req_type].set_index('week')['count'].sort_index()
        if series.empty:
            return pd.DataFrame()
        if len(series) == 1:
            last = float(series.iloc[-1])
            idx = [series.index[-1] + pd.Timedelta(weeks=i+1) for i in range(periods)]
            return pd.DataFrame({'ds': idx, 'y_hat': [last]*periods})
        model = ExponentialSmoothing(series, trend='add', seasonal=None).fit()
        pred = model.forecast(periods)
        idx = [series.index[-1] + pd.Timedelta(weeks=i+1) for i in range(periods)]
        return pd.DataFrame({'ds': idx, 'y_hat': pred.values})

    for rt in top_types:
        fc = forecast_weekly_type(weekly, rt)
        if not fc.empty:
            weekly_forecasts[rt] = {d.strftime("%b %d, %Y"): round(float(v), 1) for d, v in zip(fc['ds'], fc['y_hat'])}

# ==================== INCIDENT FORECASTING ==================

incident_df = pd.read_sql("SELECT * FROM incident_reports", con=engine)
incident_df['date_of_incident'] = pd.to_datetime(incident_df['date_of_incident'], errors='coerce')
incident_df['type_of_incident'] = incident_df['type_of_incident'].fillna('unknown')

#Daily incidents
inc_daily = (
    incident_df.dropna(subset=['date_of_incident'])
        .assign(ds=lambda d: d['date_of_incident'].dt.floor('D'))
        .groupby('ds', as_index=False)
        .agg(incidents_per_day=('incident_id', 'count'))
)
inc_daily = inc_daily.set_index('ds').asfreq('D', fill_value=0).reset_index()
inc_daily['y_incidents'] = inc_daily['incidents_per_day'].astype(float)

#Forecast incidents
def forecast_incidents_es(ts_df, periods=14):
    ts = ts_df.set_index('ds')['y_incidents'].asfreq('D').fillna(0)
    if len(ts) < 2:
        last = float(ts.iloc[-1]) if len(ts)==1 else 0.0
        idx = pd.date_range(ts.index[-1] + pd.Timedelta(days=1), periods=periods)
        return pd.DataFrame({'ds': idx, 'y_hat': [last]*periods})
    model = ExponentialSmoothing(ts, trend='add').fit()
    pred = model.forecast(periods)
    idx = pd.date_range(ts.index[-1] + pd.Timedelta(days=1), periods=periods)
    return pd.DataFrame({'ds': idx, 'y_hat': pred.values})

def forecast_incidents_sarima(ts_df, periods=14):
    ts = ts_df.set_index('ds')['y_incidents'].asfreq('D').fillna(0)
    if len(ts) < 2:
        last = float(ts.iloc[-1]) if len(ts)==1 else 0.0
        idx = pd.date_range(ts.index[-1] + pd.Timedelta(days=1), periods=periods)
        return pd.DataFrame({'ds': idx, 'yhat': [last]*periods})
    model = SARIMAX(ts, order=(1,0,1)).fit(disp=False)
    pred = model.get_forecast(steps=periods)
    idx = pd.date_range(ts.index[-1] + pd.Timedelta(days=1), periods=periods)
    return pd.DataFrame({'ds': idx, 'yhat': pred.predicted_mean.values})

inc_daily_es = forecast_incidents_es(inc_daily[['ds','y_incidents']], periods=30)
inc_daily_sarima = forecast_incidents_sarima(inc_daily[['ds','y_incidents']], periods=30)

#Weekly incident-type forecasts
inc_weekly_forecasts = {}
tmpi = incident_df.dropna(subset=['date_of_incident']).copy()
if not tmpi.empty:
    tmpi['date'] = tmpi['date_of_incident'].dt.floor('D')
    demand = tmpi.groupby(['date','type_of_incident']).size().reset_index(name='count')
    demand['week'] = demand['date'].dt.to_period('W').apply(lambda r: r.start_time)
    weekly = demand.groupby(['week','type_of_incident'], as_index=False)['count'].sum()
    weekly = weekly[weekly['type_of_incident'] != 'unknown']

    top_types = weekly.groupby('type_of_incident')['count'].sum().sort_values(ascending=False).head(5).index.tolist()

    def forecast_weekly_inc(df_weekly, inc_type, periods=12):
        series = df_weekly[df_weekly['type_of_incident'] == inc_type].set_index('week')['count'].sort_index()
        if series.empty:
            return pd.DataFrame()
        if len(series) == 1:
            last = float(series.iloc[-1])
            idx = [series.index[-1] + pd.Timedelta(weeks=i+1) for i in range(periods)]
            return pd.DataFrame({'ds': idx, 'y_hat': [last]*periods})
        model = ExponentialSmoothing(series, trend='add').fit()
        pred = model.forecast(periods)
        idx = [series.index[-1] + pd.Timedelta(weeks=i+1) for i in range(periods)]
        return pd.DataFrame({'ds': idx, 'y_hat': pred.values})

    for it in top_types:
        fc = forecast_weekly_inc(weekly, it)
        if not fc.empty:
            inc_weekly_forecasts[it] = {d.strftime("%b %d, %Y"): round(float(v), 1) for d, v in zip(fc['ds'], fc['y_hat'])}

# ========================= ANALYTICS =========================

#Request Analytics
requests_by_type = df.groupby('request_type')['request_id'].count().sort_values(ascending=False).to_dict()
requests_missing_docs = df[['valid_id_path','proof_of_residency_path']].isna().sum().to_dict()
age_distribution = df['age'].dropna().astype(int).value_counts().sort_index().to_dict()

#Incident Analytics
incidents_by_type = incident_df.groupby('type_of_incident')['incident_id'].count().sort_values(ascending=False).to_dict()
incidents_by_location = incident_df.groupby('location')['incident_id'].count().sort_values(ascending=False).to_dict()
status_distribution = incident_df['status'].fillna('unknown').value_counts().to_dict()

# ========================= OUTPUT ===========================

output = {
    "requests": {
        "daily_requests_forecast": {d.strftime("%b %d, %Y"): round(float(v),1) for d,v in zip(daily_forecast['ds'], daily_forecast['y_hat'])},
        "daily_approvals_forecast": {d.strftime("%b %d, %Y"): round(float(v),1) for d,v in zip(approval_forecast['ds'], approval_forecast['yhat'])},
        "weekly_requests_forecast": weekly_forecasts,
        "analytics": {
            "requests_by_type": requests_by_type,
            "requests_missing_docs": requests_missing_docs,
            "age_distribution": age_distribution
        }
    },
    "incidents": {
        "daily_incidents_forecast_es": {d.strftime("%b %d, %Y"): round(float(v),1) for d,v in zip(inc_daily_es['ds'], inc_daily_es['y_hat'])},
        "daily_incidents_forecast_sarima": {d.strftime("%b %d, %Y"): round(float(v),1) for d,v in zip(inc_daily_sarima['ds'], inc_daily_sarima['yhat'])},
        "weekly_incident_type_forecast": inc_weekly_forecasts,
        "analytics": {
            "incidents_by_type": incidents_by_type,
            "incidents_by_location": incidents_by_location,
            "status_distribution": status_distribution
        }
    }
}

print(json.dumps(output, indent=2))
