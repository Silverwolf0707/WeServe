import pandas as pd
from statsmodels.tsa.seasonal import STL
import json
import os
import sys
from sklearn.metrics import mean_squared_error, mean_absolute_error
import numpy as np
from statsmodels.stats.stattools import durbin_watson
from statsmodels.tsa.stattools import acf

# ─────────────────────────────────────────────
# PATH SETUP
# ─────────────────────────────────────────────
if len(sys.argv) > 1:
    csv_path = sys.argv[1]
else:
    base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
    csv_path = os.path.join(base_path, 'storage', 'app', 'private', 'analytics', 'full_patient_data.csv')

base_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..'))
json_path = os.path.join(base_path, 'storage', 'app', 'private', 'analytics', 'stl_output.json')
os.makedirs(os.path.dirname(json_path), exist_ok=True)

# ─────────────────────────────────────────────
# LOAD & AGGREGATE DATA
# ─────────────────────────────────────────────
try:
    df = pd.read_csv(csv_path, parse_dates=['month'])
except Exception as e:
    print(f"Error loading CSV: {e}")
    sys.exit(1)

df['applications'] = 1
agg_df = df.groupby(['month', 'case_category'])['applications'].sum().reset_index()

start_month = pd.Timestamp(df['month'].min().year, 1, 1)
end_month   = pd.Timestamp(df['month'].max().year, 12, 1)
all_months  = pd.date_range(start=start_month, end=end_month, freq='MS')
categories  = df['case_category'].unique()


output = {}

for cat in categories:
    cat_df = agg_df[agg_df['case_category'] == cat].set_index('month').sort_index()
    cat_series = cat_df['applications'].reindex(all_months, fill_value=0)

    if cat_series.sum() == 0:
        continue

    stl    = STL(cat_series, period=12, robust=True)
    result = stl.fit()

    residuals     = result.resid
    reconstructed = result.trend + result.seasonal

    mse = mean_squared_error(cat_series, reconstructed)
    mae = mean_absolute_error(cat_series, reconstructed)

    rmse = np.sqrt(mse)

    nonzero_mask = cat_series != 0
    if nonzero_mask.sum() > 0:
        mape = np.mean(
            np.abs((cat_series[nonzero_mask] - reconstructed[nonzero_mask])
                   / cat_series[nonzero_mask])
        ) * 100
    else:
        mape = None

    residual_mean = residuals.mean()
    residual_std  = residuals.std()

    dw_stat = durbin_watson(residuals)

    acf_values    = acf(residuals, nlags=6, fft=False)
    acf_lags      = {f"lag_{i}": round(float(acf_values[i]), 4) for i in range(1, 7)}
    residual_autocorr_flag = any(abs(v) > 0.3 for v in acf_values[1:])

    var_resid           = np.var(residuals)
    var_seasonal_resid  = np.var(result.seasonal + residuals)
    var_trend_resid     = np.var(result.trend + residuals)

    seasonality_strength = (
        1 - (var_resid / var_seasonal_resid) if var_seasonal_resid != 0 else 0
    )
    trend_strength = (
        1 - (var_resid / var_trend_resid) if var_trend_resid != 0 else 0
    )

    output[cat] = {
        'dates':    all_months.strftime('%Y-%m').tolist(),
        'observed': cat_series.round(2).tolist(),
        'trend':    result.trend.round(2).tolist(),
        'seasonal': result.seasonal.round(2).tolist(),
        'residual': result.resid.round(2).tolist(),

        'metrics': {
            'mse':  round(mse,  4),
            'rmse': round(rmse, 4),  
            'mae':  round(mae,  4),
            'mape': round(mape, 4) if mape is not None else None,  

            'residual_mean': round(residual_mean, 4),
            'residual_std':  round(residual_std,  4),
            'durbin_watson': round(dw_stat,        4),   
            'residual_acf':  acf_lags,                  
            'residual_has_autocorrelation': residual_autocorr_flag,  

            'seasonality_strength': round(seasonality_strength, 4),
            'trend_strength':       round(trend_strength,       4),
        }
    }

with open(json_path, 'w') as f:
    json.dump(output, f, indent=2)

print(f"STL output written to: {json_path}")