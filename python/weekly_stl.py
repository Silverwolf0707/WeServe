import pandas as pd
from statsmodels.tsa.seasonal import STL
import json
import os
import sys
import numpy as np

# -------------------------------------------------
# Paths
# -------------------------------------------------
if len(sys.argv) > 1:
    csv_path = sys.argv[1]
else:
    base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
    csv_path = os.path.join(
        base_path, 'storage', 'app', 'private', 'analytics', 'full_patient_data.csv'
    )

base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
json_path = os.path.join(
    base_path, 'storage', 'app', 'private', 'analytics', 'weekly_stl_output.json'
)

os.makedirs(os.path.dirname(json_path), exist_ok=True)

# -------------------------------------------------
# Load CSV
# -------------------------------------------------
try:
    df = pd.read_csv(csv_path, parse_dates=['date_processed'])
except Exception as e:
    print(f"Error loading CSV: {e}")
    sys.exit(1)

# -------------------------------------------------
# Daily aggregation (application count) - WEEKDAYS ONLY
# -------------------------------------------------
df['applications'] = 1

# Group by date
daily_df = (
    df.groupby(df['date_processed'].dt.date)['applications']
      .sum()
      .reset_index()
)

daily_df['date_processed'] = pd.to_datetime(daily_df['date_processed'])

# Filter out weekends (Saturday=5, Sunday=6)
daily_df = daily_df[~daily_df['date_processed'].dt.weekday.isin([5, 6])]

# Set index and ensure continuous business days (no weekends)
daily_df = daily_df.set_index('date_processed').sort_index()

# -------------------------------------------------
# STL Decomposition (5-day Business Week Seasonality)
# -------------------------------------------------
# For business days (Monday-Friday), period should be 5
stl = STL(daily_df['applications'], period=5, robust=True)
result = stl.fit()

stl_df = pd.DataFrame({
    'observed': daily_df['applications'],
    'trend': result.trend,
    'seasonal': result.seasonal,
    'residual': result.resid
})

# -------------------------------------------------
# Weekday Seasonality Extraction
# -------------------------------------------------
stl_df['weekday'] = stl_df.index.day_name()

# Only include weekdays that actually exist in our data
weekday_seasonality = (
    stl_df.groupby('weekday')['seasonal']
          .mean()
          .round(2)
)

# Ensure we only have Monday-Friday in output
weekday_order = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
weekday_seasonality = weekday_seasonality.reindex(weekday_order)

# -------------------------------------------------
# Output JSON (Laravel-ready)
# -------------------------------------------------
output = {
    "weekly_stl": {
        "weekday_seasonality": weekday_seasonality.to_dict(),
        "observed": stl_df['observed'].round(2).tolist(),
        "trend": stl_df['trend'].round(2).tolist(),
        "seasonal": stl_df['seasonal'].round(2).tolist(),
        "residual": stl_df['residual'].round(2).tolist(),
        "dates": stl_df.index.strftime('%Y-%m-%d').tolist()
    }
}

with open(json_path, 'w') as f:
    json.dump(output, f, indent=2)