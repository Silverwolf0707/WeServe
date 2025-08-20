import pandas as pd
import json
import os

base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
full_csv_path = os.path.join(base_path, 'storage', 'app', 'public', 'full_patient_data.csv')
json_path = os.path.join(base_path, 'storage', 'app', 'public', 'age_stats_output.json')

df = pd.read_csv(full_csv_path)

# Ensure dates are datetime
df['date_processed'] = pd.to_datetime(df['month'], errors='coerce')
df['disbursed_date'] = pd.to_datetime(df['dv_date'], errors='coerce')

# Compute processing_days only for rows with disbursed_date
df['processing_days'] = (df['disbursed_date'] - df['date_processed']).dt.days

# Compute average processing time
average_processing_time = round(df['processing_days'].mean(), 2) if not df['processing_days'].dropna().empty else 0

# Add dashboard summary
top_assistance = df['case_type'].mode().iloc[0] if not df['case_type'].mode().empty else 'N/A'
most_common_category = df['case_category'].mode().iloc[0] if not df['case_category'].mode().empty else 'N/A'
total_applicants = len(df)

dashboard_summary = {
    'top_assistance': top_assistance,
    'most_common_category': most_common_category,
    'total_applicants': total_applicants,
    'average_processing_time': f"{average_processing_time} days"
}

# Compute stats by category/type
def compute_stats(df, value_col):
    category_stats = {}
    type_stats = {}
    for cat, group in df.groupby('case_category'):
        values = group[value_col].dropna()
        category_stats[cat] = {
            'mean': round(values.mean(), 2) if not values.empty else 0,
            'median': round(values.median(), 2) if not values.empty else 0,
            'mode': values.mode().tolist() if not values.mode().empty else [],
            'variance': round(values.var(), 2) if len(values) > 1 else 0,
            'std_dev': round(values.std(), 2) if len(values) > 1 else 0,
            'count': len(values),
        }
    for ctype, group in df.groupby('case_type'):
        values = group[value_col].dropna()
        type_stats[ctype] = {
            'mean': round(values.mean(), 2) if not values.empty else 0,
            'median': round(values.median(), 2) if not values.empty else 0,
            'mode': values.mode().tolist() if not values.mode().empty else [],
            'variance': round(values.var(), 2) if len(values) > 1 else 0,
            'std_dev': round(values.std(), 2) if len(values) > 1 else 0,
            'count': len(values),
        }
    return category_stats, type_stats

age_stats_by_category, age_stats_by_type = compute_stats(df, 'age')
application_counts = df.groupby(['case_category', 'case_type']).size().reset_index(name='application_count')
app_stats_by_category, app_stats_by_type = compute_stats(application_counts.rename(columns={'application_count': 'value'}), 'value')

total_applications_by_category = df.groupby('case_category').size().to_dict()
total_applications_by_type = df.groupby('case_type').size().to_dict()

output = {
    'age_stats_by_category': age_stats_by_category,
    'age_stats_by_type': age_stats_by_type,
    'application_stats_by_category': app_stats_by_category,
    'application_stats_by_type': app_stats_by_type,
    'total_applications_by_category': total_applications_by_category,
    'total_applications_by_type': total_applications_by_type,
    'dashboard_summary': dashboard_summary,
}

with open(json_path, 'w') as f:
    json.dump(output, f, indent=2)
