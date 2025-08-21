import pandas as pd
from statsmodels.tsa.seasonal import STL
import json
import os

# Paths
base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
csv_path = os.path.join(base_path, 'storage', 'app', 'public', 'full_patient_data.csv')
json_path = os.path.join(base_path, 'storage', 'app', 'public', 'stl_output.json')

# Load CSV
df = pd.read_csv(csv_path, parse_dates=['month'])

# Ensure all months in the year exist
start_month = pd.Timestamp(df['month'].min().year, 1, 1)   # Jan of earliest year
end_month = pd.Timestamp(df['month'].max().year, 12, 1)    # Dec of latest year
all_months = pd.date_range(start=start_month, end=end_month, freq='MS')

categories = df['case_category'].unique()

output = {}

for cat in categories:
    # Filter category data and reindex with all months
    cat_df = df[df['case_category'] == cat].set_index('month').sort_index()
    
    # If the 'value' column doesn't exist, create it with 0
    if 'value' not in cat_df.columns:
        cat_df['value'] = 0

    # Reindex to full months, filling missing with 0
    cat_series = cat_df['value'].reindex(all_months, fill_value=0)

    # Only skip if entire series is 0
    if cat_series.sum() == 0:
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
