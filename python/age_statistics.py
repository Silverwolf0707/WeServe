import pandas as pd
import json
import os

# Paths
base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
full_csv_path = os.path.join(base_path, 'storage', 'app', 'public', 'full_patient_data1.csv')
json_path = os.path.join(base_path, 'storage', 'app', 'public', 'age_stats_output.json')

# Load CSV
df = pd.read_csv(full_csv_path, parse_dates=['month', 'date_processed'])

# Add Year column for grouping
df['year'] = df['month'].dt.year

# Compute processing_days as difference between disbursed month and processed date
df['disbursed_month'] = df['month']
df['processing_days'] = (df['disbursed_month'] - df['date_processed']).dt.days

# Compute average processing time
average_processing_time = round(df['processing_days'].mean(), 2) if not df['processing_days'].dropna().empty else 0

# Dashboard summary (overall)
top_assistance = df['case_type'].mode().iloc[0] if not df['case_type'].mode().empty else 'N/A'
most_common_category = df['case_category'].mode().iloc[0] if not df['case_category'].mode().empty else 'N/A'
total_applicants = len(df)

dashboard_summary = {
    'top_assistance': top_assistance,
    'most_common_category': most_common_category,
    'total_applicants': total_applicants,
    'average_processing_time': f"{average_processing_time} days"
}

# Helper function to summarize spread
def summarize_spread(values):
    if not values:
        return []
    sorted_vals = sorted(values)
    median_val = round(pd.Series(values).median(), 2)
    sample_spread = sorted_vals[:3] + [median_val] + sorted_vals[-3:]
    return sorted(set(sample_spread), key=lambda x: values.index(x) if x in values else x)

# Compute stats
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

# 🔹 Compute per-year stats
yearly_stats = {}

for year, year_df in df.groupby('year'):
    age_stats_by_category, age_stats_by_type = compute_stats_summary(year_df, 'age')

    application_counts = year_df.groupby(['case_category', 'case_type']).size().reset_index(name='application_count')
    app_stats_by_category, app_stats_by_type = compute_stats_summary(
        application_counts.rename(columns={'application_count': 'value'}), 'value'
    )

    total_applications_by_category = year_df.groupby('case_category').size().to_dict()
    total_applications_by_type = year_df.groupby('case_type').size().to_dict()

    yearly_stats[year] = {
        'age_stats_by_category': age_stats_by_category,
        'age_stats_by_type': age_stats_by_type,
        'application_stats_by_category': app_stats_by_category,
        'application_stats_by_type': app_stats_by_type,
        'total_applications_by_category': total_applications_by_category,
        'total_applications_by_type': total_applications_by_type,
    }

# Final JSON output
output = {
    'overall': {
        'dashboard_summary': dashboard_summary,
    },
    'yearly': yearly_stats
}

# Save JSON
with open(json_path, 'w') as f:
    json.dump(output, f, indent=2)

