import pandas as pd
import json
import os
import sys
from datetime import datetime

# Get CSV path from command line argument
if len(sys.argv) > 1:
    csv_path = sys.argv[1]
else:
    # Fallback path if no argument provided
    base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
    csv_path = os.path.join(base_path, 'storage', 'app', 'private', 'analytics', 'full_patient_data.csv')

# Output JSON path in private storage
base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
json_path = os.path.join(base_path, 'storage', 'app', 'private', 'analytics', 'budget_stats_output.json')

# Ensure the analytics directory exists
os.makedirs(os.path.dirname(json_path), exist_ok=True)

# Load CSV
try:
    df = pd.read_csv(csv_path, parse_dates=['month', 'date_processed'])
except Exception as e:
    print(f"Error loading CSV: {e}")
    sys.exit(1)

# Add Year and Month columns for grouping
df['year'] = df['month'].dt.year
df['month_num'] = df['month'].dt.month
df['month_name'] = df['month'].dt.strftime('%B')

# Ensure budget_allocated column exists and is numeric
if 'budget_allocated' not in df.columns:
    df['budget_allocated'] = 0
df['budget_allocated'] = pd.to_numeric(df['budget_allocated'], errors='coerce').fillna(0)

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
    'total_budget_disbursed': float(round(total_budget_disbursed, 2)),
    'monthly_average_budget_allocation': float(round(monthly_avg_budget, 2))
}

# --------------------------
# Helper Functions
# --------------------------
def summarize_spread(values):
    if not values:
        return []
    sorted_vals = sorted(values)
    median_val = float(round(pd.Series(values).median(), 2))
    sample_spread = sorted_vals[:3] + [median_val] + sorted_vals[-3:]
    return [float(x) for x in sorted(set(sample_spread), key=lambda x: values.index(x) if x in values else x)]

def compute_stats_summary(df, value_col):
    category_stats = {}
    type_stats = {}

    for cat, group in df.groupby('case_category'):
        values = group[value_col].dropna().tolist()
        s = pd.Series(values)
        category_stats[cat] = {
            'mean': float(round(s.mean(), 2)) if values else 0,
            'median': float(round(s.median(), 2)) if values else 0,
            'mode': [float(x) for x in s.mode().tolist()] if values else [],
            'variance': float(round(s.var(), 2)) if len(values) > 1 else 0,
            'std_dev': float(round(s.std(), 2)) if len(values) > 1 else 0,
            'sample_spread': summarize_spread(values)
        }

    for ctype, group in df.groupby('case_type'):
        values = group[value_col].dropna().tolist()
        s = pd.Series(values)
        type_stats[ctype] = {
            'mean': float(round(s.mean(), 2)) if values else 0,
            'median': float(round(s.median(), 2)) if values else 0,
            'mode': [float(x) for x in s.mode().tolist()] if values else [],
            'variance': float(round(s.var(), 2)) if len(values) > 1 else 0,
            'std_dev': float(round(s.std(), 2)) if len(values) > 1 else 0,
            'sample_spread': summarize_spread(values)
        }

    return category_stats, type_stats

# --------------------------
# Yearly Stats
# --------------------------
yearly_stats = {}
monthly_stats = {}

for year, year_df in df.groupby('year'):
    # Yearly budget stats
    budget_stats_by_category, budget_stats_by_type = compute_stats_summary(year_df, 'budget_allocated')
    
    # Yearly application counts
    total_applications_by_category = {k: int(v) for k, v in year_df.groupby('case_category').size().to_dict().items()}
    total_applications_by_type = {k: int(v) for k, v in year_df.groupby('case_type').size().to_dict().items()}
    
    # Yearly budget totals
    total_budget_by_category = year_df.groupby('case_category')['budget_allocated'].sum().to_dict()
    total_budget_by_type = year_df.groupby('case_type')['budget_allocated'].sum().to_dict()
    
    yearly_stats[year] = {
        'budget_stats_by_category': budget_stats_by_category,
        'budget_stats_by_type': budget_stats_by_type,
        'total_applications_by_category': total_applications_by_category,
        'total_applications_by_type': total_applications_by_type,
        'total_budget_by_category': {k: float(v) for k, v in total_budget_by_category.items()},
        'total_budget_by_type': {k: float(v) for k, v in total_budget_by_type.items()}
    }
    
    # --------------------------
    # Monthly Stats for this year
    # --------------------------
    monthly_stats[year] = {}
    for month, month_df in year_df.groupby('month_num'):
        month_name = month_df['month_name'].iloc[0]
        
        # Monthly budget stats
        month_budget_stats_by_category, month_budget_stats_by_type = compute_stats_summary(month_df, 'budget_allocated')
        
        # Monthly application counts
        month_total_applications_by_category = {k: int(v) for k, v in month_df.groupby('case_category').size().to_dict().items()}
        month_total_applications_by_type = {k: int(v) for k, v in month_df.groupby('case_type').size().to_dict().items()}
        
        # Monthly budget totals
        month_total_budget_by_category = month_df.groupby('case_category')['budget_allocated'].sum().to_dict()
        month_total_budget_by_type = month_df.groupby('case_type')['budget_allocated'].sum().to_dict()
        
        monthly_stats[year][month] = {
            'month_name': month_name,
            'budget_stats_by_category': month_budget_stats_by_category,
            'budget_stats_by_type': month_budget_stats_by_type,
            'total_applications_by_category': month_total_applications_by_category,
            'total_applications_by_type': month_total_applications_by_type,
            'total_budget_by_category': {k: float(v) for k, v in month_total_budget_by_category.items()},
            'total_budget_by_type': {k: float(v) for k, v in month_total_budget_by_type.items()}
        }

# Get current year and month for default selection
current_year = datetime.now().year
current_month = datetime.now().month

# --------------------------
# Final Output JSON
# --------------------------
output = {
    'overall': {
        'dashboard_summary': dashboard_summary
    },
    'yearly': yearly_stats,
    'monthly': monthly_stats,
    'default_selection': {
        'year': current_year,
        'month': current_month
    }
}

# Save JSON to private storage
with open(json_path, 'w') as f:
    json.dump(output, f, indent=2)
