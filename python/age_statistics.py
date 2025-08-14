import pandas as pd
import json
import os

# Base path is the parent directory of this script
base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))

# CSV input path (single unified export)
full_csv_path = os.path.join(base_path, 'storage', 'app', 'public', 'full_patient_data.csv')

# JSON output path
json_path = os.path.join(base_path, 'storage', 'app', 'public', 'age_stats_output.json')

# Load CSV
df = pd.read_csv(full_csv_path)

def compute_stats(df, value_col):
    """
    Compute stats grouped by case_category and case_type for a given value column.
    Returns two dicts: stats by category and stats by type.
    """
    category_stats = {}
    type_stats = {}

    # Group by case_category
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

    # Group by case_type
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
}

# Save JSON output
with open(json_path, 'w') as f:
    json.dump(output, f, indent=2)
