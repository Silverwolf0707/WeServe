import pandas as pd
from statsmodels.tsa.seasonal import STL
import json
import os

# Paths
base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
csv_path = os.path.join(base_path, 'storage', 'app', 'public', 'full_patient_data.csv') 
json_path = os.path.join(base_path, 'storage', 'app', 'public', 'stl_budget_output.json')

df = pd.read_csv(csv_path, parse_dates=['disbursed_date'])

df['budget_amount'] = pd.to_numeric(df['budget_amount'], errors='coerce').fillna(0)

df['year'] = df['disbursed_date'].dt.year
df['month'] = df['disbursed_date'].dt.to_period('M').dt.to_timestamp()

grouped = df.groupby(['year', 'month', 'case_category', 'case_type'])['budget_amount'].sum().reset_index()

output = {}

for (cat, ctype), subdf in grouped.groupby(['case_category', 'case_type']):
   
    all_months = pd.date_range(start=subdf['month'].min(), end=subdf['month'].max(), freq='MS')
    series = subdf.set_index('month').reindex(all_months, fill_value=0)['budget_amount'].sort_index()

    if len(series) < 12: 
        continue

    try:
        stl = STL(series, period=12)
        result = stl.fit()

        key = f"{cat}_{ctype}"
        output[key] = {
            'case_category': cat,
            'case_type': ctype,
            'dates': all_months.strftime('%Y-%m').tolist(),
            'observed': series.round(2).tolist(),
            'trend': result.trend.round(2).tolist(),
            'seasonal': result.seasonal.round(2).tolist(),
            'residual': result.resid.round(2).tolist()
        }
    except Exception as e:
        print(f"Error processing {cat}-{ctype}: {e}")

with open(json_path, 'w') as f:
    json.dump(output, f)
