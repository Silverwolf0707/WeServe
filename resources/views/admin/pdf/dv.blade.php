<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Disbursement Voucher</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="title">DISBURSEMENT VOUCHER</div>

    <table>
        <tr>
            <th>DV Code</th>
            <td>{{ $dv->dv_code }}</td>
        </tr>
        <tr>
            <th>DV Date</th>
            <td>{{ \Carbon\Carbon::parse($dv->dv_date)->format('F j, Y') }}</td>
        </tr>
        <tr>
            <th>Patient Name</th>
            <td>{{ $patient->patient_name }}</td>
        </tr>
        <tr>
            <th>Case Type</th>
            <td>{{ $patient->case_type }}</td>
        </tr>
        <tr>
            <th>Case Category</th>
            <td>{{ App\Models\PatientRecord::CASE_CATEGORY_SELECT[$patient->case_category] ?? '' }}</td>
        </tr>
    </table>

    <p style="margin-top:20px;">Generated on {{ now()->format('F j, Y h:i A') }}</p>
</body>
</html>
