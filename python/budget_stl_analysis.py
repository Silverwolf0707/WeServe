import pandas as pd
from statsmodels.tsa.seasonal import STL
import json
import os

# Paths
base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
csv_path = os.path.join(base_path, 'storage', 'app', 'public', 'full_patient_data1.csv')
json_path = os.path.join(base_path, 'storage', 'app', 'public', 'stl_budget_output.json')

# Load CSV
df = pd.read_csv(csv_path, parse_dates=['month'])

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


# Save JSON
with open(json_path, 'w') as f:
    json.dump(output, f, indent=4)
