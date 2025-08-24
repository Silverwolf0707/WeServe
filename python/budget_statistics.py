import pandas as pd
import json
import os

# --------------------------
# Paths
# --------------------------
base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
full_csv_path = os.path.join(base_path, 'storage', 'app', 'public', 'full_patient_data.csv')
json_path = os.path.join(base_path, 'storage', 'app', 'public', 'budget_stats_output.json')

# Load CSV
df = pd.read_csv(full_csv_path, parse_dates=['month', 'date_processed'])

# Add Year column for grouping
df['year'] = df['month'].dt.year

# --------------------------
# Dashboard Summary (Overall)
# --------------------------
highest_category = df.groupby('case_category')['budget_allocated'].sum().idxmax()
highest_type = df.groupby('case_type')['budget_allocated'].sum().idxmax()
total_budget_disbursed = df['budget_allocated'].sum()
monthly_avg_budget = df.groupby(df['month'].dt.to_period('M'))['budget_allocated'].sum().mean()

dashboard_summary = {
    'highest_allocation_category': highest_category,
    'highest_allocation_type': highest_type,
    'total_budget_disbursed': round(total_budget_disbursed, 2),
    'monthly_average_budget_allocation': round(monthly_avg_budget, 2)
}

# --------------------------
# Helper Functions
# --------------------------
def summarize_spread(values):
    if not values:
        return []
    sorted_vals = sorted(values)
    median_val = round(pd.Series(values).median(), 2)
    sample_spread = sorted_vals[:3] + [median_val] + sorted_vals[-3:]
    return sorted(set(sample_spread), key=lambda x: values.index(x) if x in values else x)

def compute_stats_summary(df, value_col):
    category_stats = {}
    type_stats = {}
    for cat, group in df.groupby('case_category'):
        values = group[value_col].dropna().tolist()
        s = pd.Series(values)
        category_stats[cat] = {
            'mean': round(s.mean(), 2) if values else 0,
            'median': round(s.median(), 2) if values else 0,
            'mode': s.mode().tolist() if values else [],
            'variance': round(s.var(), 2) if len(values) > 1 else 0,
            'std_dev': round(s.std(), 2) if len(values) > 1 else 0,
            'sample_spread': summarize_spread(values)
        }
    for ctype, group in df.groupby('case_type'):
        values = group[value_col].dropna().tolist()
        s = pd.Series(values)
        type_stats[ctype] = {
            'mean': round(s.mean(), 2) if values else 0,
            'median': round(s.median(), 2) if values else 0,
            'mode': s.mode().tolist() if values else [],
            'variance': round(s.var(), 2) if len(values) > 1 else 0,
            'std_dev': round(s.std(), 2) if len(values) > 1 else 0,
            'sample_spread': summarize_spread(values)
        }
    return category_stats, type_stats

# --------------------------
# Yearly Stats
# --------------------------
yearly_stats = {}

for year, year_df in df.groupby('year'):
    budget_stats_by_category, budget_stats_by_type = compute_stats_summary(year_df, 'budget_allocated')

    total_applications_by_category = year_df.groupby('case_category').size().to_dict()
    total_applications_by_type = year_df.groupby('case_type').size().to_dict()

    yearly_stats[year] = {
        'budget_stats_by_category': budget_stats_by_category,
        'budget_stats_by_type': budget_stats_by_type,
        'total_applications_by_category': total_applications_by_category,
        'total_applications_by_type': total_applications_by_type
    }

# --------------------------
# Final Output JSON
# --------------------------
output = {
    'overall': {
        'dashboard_summary': dashboard_summary
    },
    'yearly': yearly_stats
}

# Save JSON
with open(json_path, 'w') as f:
    json.dump(output, f, indent=2)
