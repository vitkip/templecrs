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

    @page { margin: 20mm 20mm 20mm 30mm; }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Phetsarath', sans-serif;
        font-size: 10pt;
        color: #111;
        background: #fff;
    }

    /* ══ State header ══ */
    .state-header { text-align: center; margin-bottom: 4px; line-height: 1.5; }
    .state-republic { font-size: 11pt; font-weight: bold; }
    .state-motto    { font-size: 10pt; }

    /* ══ Three-column org row (table-based for DomPDF) ══ */
    .org-row { width: 100%; border-collapse: collapse; margin: 8px 0 6px; }
    .org-left  { width: 38%; vertical-align: top; }
    .org-mid   { width: 24%; vertical-align: middle; text-align: center; }
    .org-right { width: 38%; vertical-align: top; text-align: right; }
    .org-parent { font-size: 9pt; line-height: 1.6; }
    .org-name   { font-size: 9pt; font-weight: bold; line-height: 1.6; }
    .ref-line   { font-size: 9pt; line-height: 1.7; }
    .seal-img   { width: 58px; height: 58px; }

    /* ══ Dividers ══ */
    .rule-thick { border: 0; border-top: 3px solid #111; margin: 5px 0 2px; }
    .rule-thin  { border: 0; border-top: 1px solid #111; margin: 0 0 10px; }

    /* ══ Document title ══ */
    .title-block { text-align: center; margin: 10px 0 14px; }
    .title-main  { font-size: 14pt; font-weight: bold; text-decoration: underline; line-height: 1.6; }
    .title-sub   { font-size: 11pt; font-weight: bold; margin-top: 2px; }
    .title-about { font-size: 10pt; margin-top: 4px; }

    /* ══ Summary (3-column table) ══ */
    .sum-table { width: 100%; border-collapse: collapse; margin: 10px 0 14px; }
    .sum-table td { width: 33.33%; padding: 8px 12px; text-align: center; border: 1px solid #bbb; }
    .sum-label { font-size: 7.5pt; font-weight: bold; text-transform: uppercase; }
    .sum-value { font-size: 13pt; font-weight: bold; margin: 4px 0 2px; }
    .sum-unit  { font-size: 8pt; }
    .c-income  { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
    .c-expense { background: #fef2f2; color: #991b1b; border-color: #fecaca; }
    .c-bal-pos { background: #ede9fe; color: #4c1d95; border-color: #c4b5fd; }
    .c-bal-neg { background: #fef2f2; color: #991b1b; border-color: #fca5a5; }

    /* ══ Section heading ══ */
    .sec-title { font-size: 10pt; font-weight: bold; border-left: 4px solid #333; padding-left: 7px; margin: 14px 0 7px; }

    /* ══ Category breakdown (table-based) ══ */
    .cat-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    .cat-table td { width: 50%; vertical-align: top; }
    .cat-table .cat-left  { padding-right: 8px; }
    .cat-table .cat-right { padding-left: 8px; }
    .cat-head         { font-size: 9pt; font-weight: bold; padding: 4px 8px; margin-bottom: 5px; }
    .cat-head-income  { background: #dcfce7; color: #166534; }
    .cat-head-expense { background: #fee2e2; color: #991b1b; }
    .bar-row { margin-bottom: 5px; }
    .bar-lbl  { width: 100%; border-collapse: collapse; margin-bottom: 2px; font-size: 8pt; }
    .bar-lbl td { padding: 0; }
    .bar-track { height: 5px; background: #e5e7eb; }
    .bar-fill  { height: 5px; display: block; }
    .bar-income  { background: #22c55e; }
    .bar-expense { background: #ef4444; }
    .cat-total { font-size: 8.5pt; font-weight: bold; text-align: right; border-top: 1px solid #ddd; padding-top: 3px; margin-top: 3px; }

    /* ══ Data table ══ */
    table.dtbl { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 8.5pt; }
    table.dtbl thead tr { background: #2c2c2c; color: #fff; }
    table.dtbl thead th { padding: 5px 7px; text-align: left; }
    table.dtbl thead th.r { text-align: right; }
    table.dtbl tbody tr:nth-child(even) { background: #f7f7f7; }
    table.dtbl tbody td { padding: 4px 7px; border-bottom: 1px solid #ddd; vertical-align: top; }
    table.dtbl tbody td.r { text-align: right; }
    table.dtbl tfoot tr { background: #e8e8e8; font-weight: bold; }
    table.dtbl tfoot td { padding: 5px 7px; border-top: 2px solid #444; }
    table.dtbl tfoot td.r { text-align: right; }
    .badge-i { background:#dcfce7; color:#166534; border:1px solid #86efac; padding:1px 5px; font-size:7pt; font-weight:bold; }
    .badge-e { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; padding:1px 5px; font-size:7pt; font-weight:bold; }

    /* ══ Signature ══ */
    .sig-section { margin-top: 22px; }
    .sig-committee-title {
        font-size: 9pt; font-weight: bold; text-align: center;
        border: 1px solid #bbb; background: #f5f5f5;
        padding: 4px 10px; margin-bottom: 12px;
    }
    .sig-table { width: 100%; border-collapse: collapse; }
    .sig-table td { width: 33.33%; text-align: center; vertical-align: top; padding: 0 10px; }
    .sig-role-label { font-size: 9pt; font-weight: bold; margin-bottom: 44px; line-height: 1.5; }
    .sig-line { border-top: 1px solid #333; margin: 0 8px 4px; }
    .sig-rank { font-size: 8pt; color: #555; line-height: 1.5; }
    .sig-name-val { font-size: 8.5pt; font-weight: bold; line-height: 1.5; }

    /* ══ Footer ══ */
    .doc-footer { margin-top: 18px; border-top: 2px solid #111; padding-top: 5px; font-size: 7.5pt; color: #555; text-align: center; line-height: 1.7; }

    .page-break { page-break-before: always; }
</style>
</head>
<body>

{{-- ══ OFFICIAL DOCUMENT HEADER ══ --}}
<div class="state-header">
    <div class="state-republic">ສາທາລະນະລັດ ປະຊາທິປະໄຕ ປະຊາຊົນລາວ</div>
    <div class="state-motto">ສັນຕິພາບ ເອກະລາດ ປະຊາທິປະໄຕ ເອກະພາບ ວັດທະນະຖາວອນ</div>
</div>

<table class="org-row">
    <tr>
        <td class="org-left">
            <div class="org-parent">ສູນກາງອົງການພຸດທະສາສະໜາສໍາພັນ</div>
            <div class="org-parent">ແຫ່ງ ສປປ ລາວ</div>
            <div class="org-name">{{ $orgName }}</div>
        </td>
        <td class="org-mid">
            @if ($orgLogoPath)
                <img src="{{ $orgLogoPath }}" class="seal-img" />
            @else
                <div style="width:56px;height:56px;border:2px solid #444;border-radius:28px;display:inline-block;text-align:center;line-height:56px;font-size:20pt;">☸</div>
            @endif
        </td>
        <td class="org-right">
            <div class="ref-line">ເລກທີ __________/ກສປ</div>
            <div class="ref-line">ນະຄອນຫຼວງວຽງຈັນ, ວັນທີ {{ now()->format('d/m/Y') }}</div>
        </td>
    </tr>
</table>

<hr class="rule-thick" />
<hr class="rule-thin" />

{{-- ══ TITLE ══ --}}
<div class="title-block">
    <div class="title-main">ລາຍງານລາຍຮັບ-ລາຍຈ່າຍ</div>
    <div class="title-sub">{{ $orgName }}</div>
    <div class="title-about">ວ່າດ້ວຍການສະຫຼຸບລາຍຮັບ-ລາຍຈ່າຍ ໄລຍະ: {{ $from }} ຫາ {{ $to }}</div>
</div>

{{-- ══ SUMMARY ══ --}}
<table class="sum-table">
    <tr>
        <td class="c-income">
            <div class="sum-label">ລາຍຮັບທັງໝົດ</div>
            <div class="sum-value">{{ number_format((float)$totalIncome, 0, '.', ',') }}</div>
            <div class="sum-unit">ກີບ</div>
        </td>
        <td class="c-expense">
            <div class="sum-label">ລາຍຈ່າຍທັງໝົດ</div>
            <div class="sum-value">{{ number_format((float)$totalExpense, 0, '.', ',') }}</div>
            <div class="sum-unit">ກີບ</div>
        </td>
        <td class="{{ $netBalance >= 0 ? 'c-bal-pos' : 'c-bal-neg' }}">
            <div class="sum-label">ຍອດສຸດທິ</div>
            <div class="sum-value">{{ ($netBalance > 0 ? '+' : '') . number_format((float)$netBalance, 0, '.', ',') }}</div>
            <div class="sum-unit">ກີບ</div>
        </td>
    </tr>
</table>

{{-- ══ CATEGORY BREAKDOWN ══ --}}
<div class="sec-title">ສະຫຼຸບຕາມໝວດໝູ່</div>
<table class="cat-table">
    <tr>
        <td class="cat-left">
            <div class="cat-head cat-head-income">ລາຍຮັບ</div>
            @if (isset($byCategory['income']) && $byCategory['income']->isNotEmpty())
                @php $incTotal = $byCategory['income']->sum('total'); @endphp
                @foreach ($byCategory['income']->sortByDesc('total') as $row)
                    @php $pct = $incTotal > 0 ? round(($row->total / $incTotal) * 100) : 0; @endphp
                    <div class="bar-row">
                        <table class="bar-lbl"><tr>
                            <td>{{ $row->category->name ?? '?' }} ({{ $row->count }})</td>
                            <td style="text-align:right;">{{ number_format((float)$row->total,0,'.',',') }} ({{ $pct }}%)</td>
                        </tr></table>
                        <div class="bar-track"><div class="bar-fill bar-income" style="width:{{ $pct }}%;"></div></div>
                    </div>
                @endforeach
                <div class="cat-total">ລວມ: {{ number_format((float)$incTotal,0,'.',',') }} ກີບ</div>
            @else
                <p style="color:#888;font-size:8.5pt;padding:4px 0;">ບໍ່ມີຂໍ້ມູນ</p>
            @endif
        </td>
        <td class="cat-right">
            <div class="cat-head cat-head-expense">ລາຍຈ່າຍ</div>
            @if (isset($byCategory['expense']) && $byCategory['expense']->isNotEmpty())
                @php $expTotal = $byCategory['expense']->sum('total'); @endphp
                @foreach ($byCategory['expense']->sortByDesc('total') as $row)
                    @php $pct = $expTotal > 0 ? round(($row->total / $expTotal) * 100) : 0; @endphp
                    <div class="bar-row">
                        <table class="bar-lbl"><tr>
                            <td>{{ $row->category->name ?? '?' }} ({{ $row->count }})</td>
                            <td style="text-align:right;">{{ number_format((float)$row->total,0,'.',',') }} ({{ $pct }}%)</td>
                        </tr></table>
                        <div class="bar-track"><div class="bar-fill bar-expense" style="width:{{ $pct }}%;"></div></div>
                    </div>
                @endforeach
                <div class="cat-total">ລວມ: {{ number_format((float)$expTotal,0,'.',',') }} ກີບ</div>
            @else
                <p style="color:#888;font-size:8.5pt;padding:4px 0;">ບໍ່ມີຂໍ້ມູນ</p>
            @endif
        </td>
    </tr>
</table>

{{-- ══ TRANSACTION TABLE ══ --}}
<div class="sec-title">ລາຍການທຸລະກໍາທັງໝົດ</div>
@if ($transactions->isEmpty())
    <p style="text-align:center;color:#888;padding:14px;">ບໍ່ມີຂໍ້ມູນ</p>
@else
    <table class="dtbl">
        <thead>
            <tr>
                <th style="width:11%">ວັນທີ</th>
                <th style="width:9%">ປະເພດ</th>
                <th style="width:16%">ໝວດໝູ່</th>
                <th>ລາຍລະອຽດ</th>
                <th style="width:18%" class="r">ຈໍານວນ (ກີບ)</th>
                <th style="width:10%">ເລກທີ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $tx)
            <tr>
                <td>{{ $tx->transaction_date_formatted }}</td>
                <td>
                    @if ($tx->is_income)
                        <span class="badge-i">ລາຍຮັບ</span>
                    @else
                        <span class="badge-e">ລາຍຈ່າຍ</span>
                    @endif
                </td>
                <td>{{ $tx->category->name ?? '—' }}</td>
                <td>{{ $tx->description }}</td>
                <td class="r" style="color:{{ $tx->is_income ? '#166534' : '#991b1b' }};font-weight:bold;">
                    {{ $tx->is_income ? '+' : '-' }}{{ number_format((float)$tx->amount, 0, '.', ',') }}
                </td>
                <td>{{ $tx->reference_number ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">ລວມທັງໝົດ ({{ $transactions->count() }} ລາຍການ)</td>
                <td class="r" style="color:{{ $netBalance >= 0 ? '#166534' : '#991b1b' }};">
                    {{ $netBalance >= 0 ? '+' : '' }}{{ number_format((float)$netBalance, 0, '.', ',') }} ກີບ
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
@endif

{{-- ══ SIGNATURE ══ --}}
<div class="sig-section">
    <div class="sig-committee-title">ຄະນະກໍາມະການຮັບຜິດຊອບການເງິນ ແລະ ການບັນຊີ</div>
    <table class="sig-table">
        <tr>
            <td style="width:40%;">&nbsp;</td>
            <td style="width:60%; text-align:center; padding: 0 10px;">
                <div class="sig-role-label">ຜູ້ອະນຸມັດ / ປະທານ</div>
                <div class="sig-line"></div>
                <div class="sig-name-val">ພຣະອາຈານໃຫຍ່ ບຸນທະວີ ປະສິດທິສັກ</div>
                <div class="sig-rank">ຫົວໜ້າ ກັມມາທິການສາທາຣະນູປະການ</div>
            </td>
        </tr>
    </table>
</div>

{{-- ══ FOOTER ══ --}}
<div class="doc-footer">
    ຫ້ອງການ {{ $orgName }} ສູນກາງອົງການພຸດທະສາສະໜາສໍາພັນ ແຫ່ງ ສປປ ລາວ &nbsp;|&nbsp; ສໍານັກງານ ຕັ້ງຢູ່ທີ່: {{ $orgAddress }}<br/>
    @if ($orgPhone)ໂທ: {{ $orgPhone }}@endif
    @if ($orgEmail) &nbsp;|&nbsp; {{ $orgEmail }}@endif
    &nbsp;|&nbsp; ສ້າງໂດຍ Buddhist EMS ວັນທີ {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>
