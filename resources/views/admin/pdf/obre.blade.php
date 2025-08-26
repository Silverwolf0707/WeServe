<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OBRE</title>
    <style>
        /* Base */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .tiny {
            font-size: 9px;
        }

        /* Header (left text + stacked right cells) */
        .header td {
            border: 1px solid #000;
        }

        .agency {
            line-height: 1.3;
        }

        .hdr-cell {
            height: 22px;
        }

        /* Title band with checkbox column */
        .title td {
            border: 1px solid #000;
        }

        .cb-col {
            width: 26px;
            text-align: center;
        }

        .box {
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            display: inline-block;
            line-height: 12px;
            font-weight: bold;
            font-size: 10px;
        }

        /* Payee/Office/Address rows take full width one per line */
        .singleline td {
            padding: 6px;
        }

        /* Particulars table */
        .particulars th {
            text-align: center;
        }

        .particulars .pad {
            height: 110px;
        }

        /* Certifications A and B */
        .cert-head {
            font-weight: bold;
        }

        .cert-body {
            height: 100px;
        }

        .line {
            display: inline-block;
            min-width: 180px;
            border-bottom: 1px solid #000;
        }

        /* Status of Obligation (multi-row header with groups) */
        .status th {
            text-align: center;
        }

        .status .pad {
            height: 130px;
        }

        /* Footer line */
        .footer {
            border: none;
            margin-top: 6px;
            width: 100%;
        }

        .footer td {
            border: none;
            padding: 0 6px;
        }
    </style>
</head>

<body>

    <!-- HEADER: Left agency block + right stacked cells -->
    <table class="header">
        <tr>
            <td class="agency" rowspan="3" style="width: 65%;">
                <strong>Republic of the Philippines</strong><br>
                <span>City Budget Office</span><br>
                <span>City of San Pedro Laguna</span><br>
                <span>City Hall 4F, New City Hall Bldg </span><br>
            </td>
            <td class="hdr-cell" style="width: 35%;">Serial No.: __________________</td>
        </tr>
        <tr>
            <td class="hdr-cell">Date: _______________________</td>
        </tr>
        <tr>
            <td class="hdr-cell">Fund Cluster: <u>01</u></td>
        </tr>
    </table>

    <!-- TITLE BAND: two rows, checkbox column on the left -->
    <table class="title">
        <tr>
            <td class="cb-col"><span class="box">X</span></td>
            <td class="center"><strong>OBLIGATION REQUEST AND STATUS</strong></td>
        </tr>
        <tr>
            <td class="cb-col"><span class="box">&nbsp;</span></td>
            <td class="center"><strong>BUDGET UTILIZATION REQUEST AND STATUS</strong></td>
        </tr>
    </table>

    <!-- PAYEE / OFFICE / ADDRESS (one per row, full width) -->
    <table class="singleline">
        <tr>
            <td>Payee: <span class="line">{{ $patient->claimant_name ?? '' }}</span></td>
        </tr>
        <tr>
            <td>Office: <span class="line">{{ 'BUDGET OFFICE' }}</span></td>
        </tr>
        <tr>
            <td>Address: <span class="line" style="min-width: 400px;">{{ $patient->address ?? '' }}</span></td>
        </tr>
    </table>

    <!-- PARTICULARS TABLE -->
    <table class="particulars">
        <tr>
            <th style="width: 16%;">Responsibility Center</th>
            <th>Particulars</th>
            <th style="width: 15%;">MFO/PAP</th>
            <th style="width: 15%;">UACS Object Code</th>
            <th style="width: 15%;">Amount</th>
        </tr>
        <tr class="pad">
            <td>{{ $patient->responsibility_center ?? 'SE22B' }}</td>
            <td>
                
            </td>
            <td></td>
            <td></td>
            <td>₱{{ number_format($amount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="4" class="right"><strong>Total</strong></td>
            <td>₱{{ number_format($amount, 2) }}</td>
        </tr>
    </table>

    <!-- CERTIFICATIONS A & B -->
    <table>
        <tr>
            <td style="width: 50%;">
                <div class="cert-head">A. Certified:</div>
                Charges to appropriation/allotment are necessary, lawful and under my direct supervision, and supporting
                documents valid, proper and legal.
                <div class="cert-body"></div>
                Signature: <span class="line"></span><br>
                Printed Name: <span class="line">RODERIC O. VERENA</span><br>
                Position: <span class="line">Chief SRS, SEPRD</span><br>
                Date: <span class="line"></span>
            </td>
            <td style="width: 50%;">
                <div class="cert-head">B. Certified:</div>
                Allotment available and obligated for the purpose/adjustment necessary as indicated above
                <div class="cert-body"></div>
                Signature: <span class="line"></span><br>
                Printed Name: <span class="line">MA. TERESA T. DIÑO</span><br>
                Position: <span class="line">AOV</span><br>
                Date: <span class="line"></span>
            </td>
        </tr>
    </table>

    <!-- C. STATUS OF OBLIGATION (grouped headers like the form) -->
    <table class="status">
        <tr>
            <th colspan="2">Reference</th>
            <th colspan="3">Amount</th>
            <th colspan="2">Balance</th>
        </tr>
        <tr>
            <th style="width: 10%;">Date</th>
            <th style="width: 30%;">Particulars / ORS/JEV/Check/ADA/TRA No.</th>
            <th style="width: 12%;">Obligation<br>(a)</th>
            <th style="width: 12%;">Payable<br>(b)</th>
            <th style="width: 12%;">Payment<br>(c)</th>
            <th style="width: 12%;">Not Yet Due<br>(a − b)</th>
            <th style="width: 12%;">Due and Demandable<br>(b − c)</th>
        </tr>
        <tr class="pad">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <!-- Footer line -->
    <table class="footer">
        <tr>
            <td style="width: 60%;">City Budget Office ({{ now()->format('m.d.y') }})</td>
            <td class="right" style="width: 40%;"><i>*This form is generated from Budget Allocation</i></td>
        </tr>
    </table>

</body>

</html>