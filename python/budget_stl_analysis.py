import pandas as pd
from statsmodels.tsa.seasonal import STL
import json
import os
import sys

# Get CSV path from command line argument
if len(sys.argv) > 1:
    csv_path = sys.argv[1]
else:
    # Fallback path if no argument provided
    base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
    csv_path = os.path.join(base_path, 'storage', 'app', 'private', 'analytics', 'full_patient_data.csv')

# Output JSON path in private storage
base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
json_path = os.path.join(base_path, 'storage', 'app', 'private', 'analytics', 'stl_budget_output.json')

# Ensure the analytics directory exists
os.makedirs(os.path.dirname(json_path), exist_ok=True)

# Load CSV
try:
    df = pd.read_csv(csv_path, parse_dates=['month'])
except Exception as e:
    print(f"Error loading CSV: {e}")
    sys.exit(1)

# Ensure all months in the year exist (pad missing months with 0)
start_month = pd.Timestamp(df['month'].min().year, 1, 1)   # Jan of earliest year
end_month = pd.Timestamp(df['month'].max().year, 12, 1)    # Dec of latest year
all_months = pd.date_range(start=start_month, end=end_month, freq='MS')

categories = df['case_category'].unique()
output = {}

for cat in categories:
    # Sum budget per month
    cat_df = df[df['case_category'] == cat].groupby('month')['budget_allocated'].sum()
    
    # Reindex to all months
    cat_series = cat_df.reindex(all_months, fill_value=0)
    
    # Skip if all zeros
    if cat_series.sum() == 0:
        continue
    
    # STL decomposition
    stl = STL(cat_series, period=12, robust=True)
    result = stl.fit()
    
    output[cat] = {
        'dates': all_months.strftime('%Y-%m').tolist(),
        'observed': cat_series.round(2).tolist(),
        'trend': result.trend.round(2).tolist(),
        'seasonal': result.seasonal.round(2).tolist(),
        'residual': result.resid.round(2).tolist()
    }

# Save JSON to private storage
with open(json_path, 'w') as f:
    json.dump(output, f, indent=4)
