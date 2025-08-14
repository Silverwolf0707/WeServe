import pandas as pd
from statsmodels.tsa.seasonal import STL
import json
import os

# Paths
base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
csv_path = os.path.join(base_path, 'storage', 'app', 'public', 'patient_records_year.csv')
json_path = os.path.join(base_path, 'storage', 'app', 'public', 'stl_output.json')

# Load CSV
df = pd.read_csv(csv_path, parse_dates=['month'])

# Generate all months between min & max
all_months = pd.date_range(start=df['month'].min(), end=df['month'].max(), freq='MS')
categories = df['case_category'].unique()

output = {}

for cat in categories:
    cat_df = df[df['case_category'] == cat].set_index('month').sort_index()
    cat_series = cat_df['value'].reindex(all_months, fill_value=0)

    if len(cat_series) < 12: 
        continue

    stl = STL(cat_series, period=12)
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
    json.dump(output, f)
