import pandas as pd
from statsmodels.tsa.arima.model import ARIMA
import json
from sqlalchemy import create_engine

# Connect to DB using SQLAlchemy ... MySQL doesn't work
db_user = 'root'
db_pass = ''
db_host = 'localhost'
db_name = 'barangay_system'

# Create SQLAlchemy engine
engine = create_engine(f'mysql+mysqlconnector://{db_user}:{db_pass}@{db_host}/{db_name}')

#Read data
query = "SELECT request_type, date_submitted FROM requests"
data = pd.read_sql(query, con=engine)

# Remove empty request_type rows
data = data[data['request_type'].notna() & (data['request_type'] != '')]

# Preprocess dates
data['date_submitted'] = pd.to_datetime(data['date_submitted'])
data['week_start'] = data['date_submitted'].dt.to_period('W').apply(lambda r: r.start_time)

# Aggregate weekly counts per request type
weekly_data = data.groupby(['week_start', 'request_type']).size().reset_index(name='count')

#Forecast per type
forecast_results = {}

for request_type in weekly_data['request_type'].unique():
    ts = weekly_data[weekly_data['request_type'] == request_type].set_index('week_start')['count']

    if len(ts) < 2:
        #repeat last value for next 4 weeks
        last_value = ts.iloc[-1]
        forecast_results[request_type] = {}
        for i, date in enumerate(pd.date_range(start=ts.index[-1] + pd.Timedelta(days=7), periods=4, freq='W')):
            forecast_results[request_type][str(date.date())] = int(last_value)
        continue

    model = ARIMA(ts, order=(1,1,1))
    model_fit = model.fit()
    forecast = model_fit.forecast(steps=4)

    forecast_results[request_type] = {str(date.date()): int(val) for date, val in zip(
        pd.date_range(start=ts.index[-1] + pd.Timedelta(days=7), periods=4, freq='W'),
        forecast
    )}

#Output JSON
print(json.dumps(forecast_results))
