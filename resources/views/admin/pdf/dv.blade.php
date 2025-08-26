<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Disbursement Voucher</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; vertical-align: top; }
        .header { text-align: center; font-size: 14px; font-weight: bold; }
        .sub-header { text-align: center; font-size: 12px; }
        .voucher-title { text-align: center; font-size: 16px; font-weight: bold; margin: 10px 0; }
        .section-title { font-weight: bold; background: #f2f2f2; }
        .checkbox { display: inline-block; width: 12px; height: 12px; border: 1px solid #000; margin-right: 5px; }
        .signature-block { height: 60px; }
    </style>
</head>
<body>
    <div class="header">Republic of the Philippines</div>
    <div class="sub-header">PROVINCIAL GOVERNMENT OF SAN PEDRO, LAGUNA</div>
    <div class="sub-header">San Pedro, Laguna</div>

    <div class="voucher-title">DISBURSEMENT VOUCHER</div>

    <table>
        <tr>
            <th style="width:20%;">Mode of Payment</th>
            <td colspan="3">
                <div><span class="checkbox"></span> Check</div>
                <div><span class="checkbox"></span> Cash</div>
                <div><span class="checkbox"></span> Other</div>
            </td>
            <th style="width:15%;">No.</th>
            <td style="width:15%;">{{ $dv->dv_code ?? '' }}</td>
        </tr>
        <tr>
            <th>Payee</th>
            <td colspan="2">{{ $patient->claimant_name ?? '' }}</td>
            <th>TIN/Employee No.</th>
            <td colspan="2">{{ $dv->tin ?? '' }}</td>
        </tr>
        <tr>
            <th>Address</th>
            <td colspan="2">{{ $patient->address ?? 'San Pedro City' }}</td>
            <th>Responsibility Center</th>
            <td colspan="2">{{ $dv->responsibility_center ?? '' }}</td>
        </tr>
        <tr>
            <th>Office/Unit/Project</th>
            <td colspan="2">{{ $dv->office_unit ?? '' }}</td>
            <th>Code</th>
            <td colspan="2">{{ $dv->project_code ?? '' }}</td>
        </tr>
    </table>

    <table style="margin-top:10px;">
        <tr>
            <th style="width:20%;">EXPLANATION</th>
            <td colspan="5">
                {!! $dv->explanation ?? "Payment for assistance case of <b>" . ($patient->patient_name ?? 'Unknown') . "</b>, 
                Type: " . ($patient->case_type ?? 'N/A') . ", 
                Category: " . ($patient->case_category ?? 'N/A') !!}
            </td>
        </tr>
        <tr>
            <th>Amount</th>
            <td colspan="5" style="text-align:right;">Php {{ number_format($dv->amount ?? 0, 2) }}</td>
        </tr>
    </table>

    <table style="margin-top:10px;">
        <tr>
            <th style="width:20%;">A. Certified</th>
            <td colspan="2">
                <div><span class="checkbox"></span> Allotment obligated</div>
                <div><span class="checkbox"></span> Supporting docs complete</div>
                <div class="signature-block"></div>
                <b>{{ $accountant->name ?? 'MERLE R. BADEL' }}</b><br>
                Municipal Accountant
            </td>
            <th style="width:20%;">B. Certified</th>
            <td colspan="2">
                <div><span class="checkbox"></span> Fund Available</div>
                <div class="signature-block"></div>
                <b>{{ $treasurer->name ?? 'ARSENIA R. PONCE' }}</b><br>
                Municipal Treasurer
            </td>
        </tr>
        <tr>
            <th>C. Approved for Payment</th>
            <td colspan="2">
                <div class="signature-block"></div>
                <b>{{ $mayor->name ?? 'EMMANUEL C. MAGANA' }}</b><br>
                Municipal Mayor
            </td>
            <th>D. Received Payment</th>
            <td colspan="2">
                <div class="signature-block"></div>
                <b>{{ $dv->received_by ?? 'Bureau of Treasury' }}</b><br>
                OR/Other Documents: {{ $dv->jev_no ?? 'N/A' }}
            </td>
        </tr>
    </table>

    <p style="margin-top:15px;">Generated on {{ now()->format('F j, Y h:i A') }}</p>
</body>
</html>
