<!DOCTYPE html>
<html lang="lo">
<head>
<meta charset="UTF-8" />
<style>
    @font-face {
        font-family: 'Phetsarath';
        src: url('{{ storage_path('fonts/Phetsarath-Regular.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    @font-face {
        font-family: 'Phetsarath';
        src: url('{{ storage_path('fonts/Phetsarath-Bold.ttf') }}') format('truetype');
        font-weight: bold;
        font-style: normal;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Phetsarath', sans-serif;
        font-size: 10pt;
        color: #1a1a2e;
        background: #fff;
        padding: 0;
    }

    /* ── Header ── */
    .doc-header {
        text-align: center;
        padding: 18px 30px 12px;
        border-bottom: 3px double #4a2c8a;
        margin-bottom: 14px;
    }
    .doc-header .org-name { font-size: 15pt; font-weight: bold; color: #3b0764; letter-spacing: 1px; }
    .doc-header .report-title { font-size: 13pt; font-weight: bold; margin: 6px 0 4px; color: #1e1b4b; }
    .doc-header .period { font-size: 9pt; color: #6b21a8; }
    .doc-header .meta { font-size: 8pt; color: #64748b; margin-top: 4px; }

    /* ── Section heading ── */
    .section-title {
        font-size: 10pt; font-weight: bold; color: #3b0764;
        border-left: 4px solid #7c3aed; padding-left: 8px;
        margin: 14px 0 8px;
    }

    /* ── Summary grid ── */
    .summary-grid { display: flex; gap: 10px; margin-bottom: 14px; }
    .summary-card {
        flex: 1; padding: 10px 12px; border-radius: 6px; text-align: center;
        border: 1px solid;
    }
    .summary-card .card-label { font-size: 7.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
    .summary-card .card-value { font-size: 13pt; font-weight: bold; margin: 3px 0 2px; }
    .summary-card .card-unit  { font-size: 8pt; }
    .card-income  { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
    .card-expense { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
    .card-balance-pos { background: #ede9fe; border-color: #c4b5fd; color: #4c1d95; }
    .card-balance-neg { background: #fef2f2; border-color: #fca5a5; color: #991b1b; }

    /* ── Tables ── */
    table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 9pt; }
    thead tr { background: #4a2c8a; color: #fff; }
    thead th { padding: 5px 8px; text-align: left; font-size: 8.5pt; }
    thead th.right { text-align: right; }
    tbody tr:nth-child(even) { background: #f8f5ff; }
    tbody tr td { padding: 4px 8px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
    tbody tr td.right { text-align: right; }
    tfoot tr { background: #ede9fe; font-weight: bold; }
    tfoot td { padding: 5px 8px; border-top: 2px solid #7c3aed; }
    tfoot td.right { text-align: right; }

    .badge-income  { background: #dcfce7; color: #166534; padding: 1px 5px; border-radius: 8px; font-size: 7.5pt; font-weight: bold; }
    .badge-expense { background: #fee2e2; color: #991b1b; padding: 1px 5px; border-radius: 8px; font-size: 7.5pt; font-weight: bold; }

    /* ── Progress bar ── */
    .bar-row { margin-bottom: 6px; }
    .bar-label { display: flex; justify-content: space-between; margin-bottom: 2px; font-size: 8.5pt; }
    .bar-track { height: 6px; background: #e5e7eb; border-radius: 3px; overflow: hidden; }
    .bar-fill { height: 100%; border-radius: 3px; }
    .bar-income  { background: #22c55e; }
    .bar-expense { background: #ef4444; }

    /* ── Category section ── */
    .cat-grid { display: flex; gap: 12px; margin-bottom: 14px; }
    .cat-col { flex: 1; }
    .cat-heading { font-size: 9pt; font-weight: bold; padding: 4px 8px; border-radius: 4px; margin-bottom: 6px; }
    .cat-heading-income  { background: #dcfce7; color: #166534; }
    .cat-heading-expense { background: #fee2e2; color: #991b1b; }

    /* ── Footer ── */
    .doc-footer {
        margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 10px;
        font-size: 8pt; color: #94a3b8; text-align: center;
    }
    .signature-grid { display: flex; justify-content: space-between; margin: 20px 0; }
    .signature-box { text-align: center; width: 30%; }
    .signature-line { border-top: 1px solid #374151; margin: 40px auto 4px; }
    .signature-label { font-size: 8pt; color: #6b7280; }

    /* ── Page break ── */
    .page-break { page-break-before: always; }
</style>
</head>
<body>

{{-- ══ HEADER ══ --}}
<div class="doc-header">
    <div class="org-name">{{ $orgName }}</div>
    <div class="report-title">ລາຍງານລາຍຮັບ-ລາຍຈ່າຍ</div>
    <div class="period">ໄລຍະ: {{ $from }} ຫາ {{ $to }}</div>
    <div class="meta">ວັນທີ່ອອກລາຍງານ: {{ now()->format('d/m/Y H:i') }} | ຈັດທຳໂດຍ: Buddhist EMS</div>
</div>

{{-- ══ SUMMARY ══ --}}
<div class="summary-grid">
    <div class="summary-card card-income">
        <div class="card-label">ລາຍຮັບທັງໝົດ</div>
        <div class="card-value">{{ number_format((float)$totalIncome, 0, '.', ',') }}</div>
        <div class="card-unit">ກີບ</div>
    </div>
    <div class="summary-card card-expense">
        <div class="card-label">ລາຍຈ່າຍທັງໝົດ</div>
        <div class="card-value">{{ number_format((float)$totalExpense, 0, '.', ',') }}</div>
        <div class="card-unit">ກີບ</div>
    </div>
    <div class="summary-card {{ $netBalance >= 0 ? 'card-balance-pos' : 'card-balance-neg' }}">
        <div class="card-label">ຍອດສຸດທິ</div>
        <div class="card-value">{{ number_format((float)$netBalance, 0, '.', ',') }}</div>
        <div class="card-unit">ກີບ</div>
    </div>
</div>

{{-- ══ CATEGORY BREAKDOWN ══ --}}
<div class="section-title">ສະຫຼຸບຕາມໝວດໝູ່</div>
<div class="cat-grid">
    @foreach (['income' => ['ລາຍຮັບ', 'cat-heading-income', 'bar-income', 'income'], 'expense' => ['ລາຍຈ່າຍ', 'cat-heading-expense', 'bar-expense', 'expense']] as $t => [$label, $headCls, $barCls, $typ])
    <div class="cat-col">
        <div class="cat-heading {{ $headCls }}">{{ $label }}</div>
        @if (isset($byCategory[$t]) && $byCategory[$t]->isNotEmpty())
            @php $total = $byCategory[$t]->sum('total'); @endphp
            @foreach ($byCategory[$t]->sortByDesc('total') as $row)
                @php $pct = $total > 0 ? round(($row->total / $total) * 100) : 0; @endphp
                <div class="bar-row">
                    <div class="bar-label">
                        <span>{{ $row->category->name ?? '?' }} ({{ $row->count }})</span>
                        <span>{{ number_format((float)$row->total, 0, '.', ',') }} ກີບ ({{ $pct }}%)</span>
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill {{ $barCls }}" style="width:{{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
            <div style="text-align:right; font-weight:bold; font-size:9pt; margin-top:4px; border-top:1px solid #e5e7eb; padding-top:4px;">
                ລວມ: {{ number_format((float)$total, 0, '.', ',') }} ກີບ
            </div>
        @else
            <p style="color:#94a3b8; font-size:8.5pt;">ບໍ່ມີຂໍ້ມູນ</p>
        @endif
    </div>
    @endforeach
</div>

{{-- ══ TRANSACTION LIST ══ --}}
<div class="section-title">ລາຍການທຸລະກໍາທັງໝົດ</div>
@if ($transactions->isEmpty())
    <p style="color:#94a3b8; text-align:center; padding:20px;">ບໍ່ມີຂໍ້ມູນ</p>
@else
    <table>
        <thead>
            <tr>
                <th style="width:15%">ວັນທີ່</th>
                <th style="width:12%">ປະເພດ</th>
                <th style="width:18%">ໝວດໝູ່</th>
                <th>ລາຍລະອຽດ</th>
                <th style="width:20%" class="right">ຈໍານວນ (ກີບ)</th>
                <th style="width:12%">ເລກທີ</th>
            </tr>
        </thead>
        <tbody>
            @php $runningBalance = 0; @endphp
            @foreach ($transactions as $tx)
                @php
                    if ($tx->is_income) { $runningBalance += $tx->amount; }
                    else { $runningBalance -= $tx->amount; }
                @endphp
                <tr>
                    <td>{{ $tx->transaction_date_formatted }}</td>
                    <td>
                        @if ($tx->is_income)
                            <span class="badge-income">ລາຍຮັບ</span>
                        @else
                            <span class="badge-expense">ລາຍຈ່າຍ</span>
                        @endif
                    </td>
                    <td>{{ $tx->category->name ?? '—' }}</td>
                    <td>{{ $tx->description }}</td>
                    <td class="right" style="color: {{ $tx->is_income ? '#166534' : '#991b1b' }}; font-weight:bold;">
                        {{ $tx->is_income ? '+' : '-' }}{{ number_format((float)$tx->amount, 0, '.', ',') }}
                    </td>
                    <td>{{ $tx->reference_number ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">ລວມ ({{ $transactions->count() }} ລາຍການ)</td>
                <td class="right" style="color: {{ $netBalance >= 0 ? '#166534' : '#991b1b' }}">
                    {{ $netBalance >= 0 ? '+' : '' }}{{ number_format((float)$netBalance, 0, '.', ',') }} ກີບ
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
@endif

{{-- ══ SIGNATURE ══ --}}
<div class="signature-grid">
    <div class="signature-box">
        <div class="signature-line"></div>
        <div class="signature-label">ຜູ້ຈັດທຳ</div>
    </div>
    <div class="signature-box">
        <div class="signature-line"></div>
        <div class="signature-label">ຜູ້ກວດສອບ</div>
    </div>
    <div class="signature-box">
        <div class="signature-line"></div>
        <div class="signature-label">ຜູ້ອະນຸມັດ</div>
    </div>
</div>

{{-- ══ FOOTER ══ --}}
<div class="doc-footer">
    ເອກະສານນີ້ສ້າງໂດຍລະບົບ Buddhist EMS ອັດຕະໂນມັດ | {{ now()->format('d/m/Y H:i:s') }}
</div>

</body>
</html>
