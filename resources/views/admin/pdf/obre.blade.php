<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>OBRE</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid black; padding: 4px; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <h3 class="center">OBLIGATION REQUEST AND STATUS</h3>
    <table>
        <tr>
            <td>Payee: {{ $patient->full_name ?? '' }}</td>
            <td>Office: {{'Budget Office' }}</td>
            <td>Address: {{ $patient->address ?? '' }}</td>
        </tr>
        <tr>
            <td>Responsibility Center: {{ $patient->responsibility_center ?? '---' }}</td>
            <td>Amount: ₱{{ number_format($amount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2">Particulars: {{ $remarks ?? 'Budget allocation approved' }}</td>
        </tr>
    </table>

    <br><br>
    <table>
        <tr>
            <th class="center">Certification A</th>
            <th class="center">Certification B</th>
        </tr>
        <tr>
            <td>
                Prepared by: {{ $prepared_by }}<br>
                Date: {{ $status_date }}
            </td>
            <td>
                Certified correct by: Head of Office<br>
                Date: {{ $status_date }}
            </td>
        </tr>
    </table>
</body>
</html>
