<!DOCTYPE html>
<html lang="lo">
<head>
<meta charset="UTF-8" />
<style>
    @font-face {
        font-family: 'Phetsarath';
        src: url('file://{{ storage_path('fonts/Phetsarath-Regular.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    @font-face {
        font-family: 'Phetsarath';
        src: url('file://{{ storage_path('fonts/Phetsarath-Bold.ttf') }}') format('truetype');
        font-weight: bold;
        font-style: normal;
    }

    /* ══ ມາດຕະຖານຂອບເຈ້ຍເອກະສານທາງການລາວ ══ 
       ເທິງ: 2.0cm, ລຸ່ມ: 2.0cm, ຊ້າຍ (ໄວ້ຫຍິບແຟ້ມ): 3.0cm, ຂວາ: 1.5cm - 2.0cm 
    */
    @page {
        margin-top: 2.0cm;
        margin-bottom: 2.0cm;
        margin-left: 3.0cm;
        margin-right: 1.5cm;
        margin: 2.0cm 1.5cm 2.0cm 3.0cm; /* Standard fallback for different engines */
    }

    * { 
        margin: 0; 
        padding: 0; 
        box-sizing: border-box; 
    }
    
    body {
        font-family: 'Phetsarath', sans-serif;
        font-size: 11pt; /* ຂະໜາດຕົວໜັງສືມາດຕະຖານທາງການ */
        color: #111827;
        background: #fff;
        line-height: 1.6;
    }

    /* ══ ສ່ວນຫົວຂໍ້ຄຳຂວັນປະເທດ ══ */
    .state-header { 
        text-align: center; 
        margin-bottom: 15px; 
        line-height: 1.4; 
    }
    .state-republic { 
        font-size: 12pt; 
        font-weight: bold; 
        color: #000000; 
    }
    .state-motto { 
        font-size: 10.5pt; 
        font-weight: bold;
        color: #1f2937; 
        margin-top: 3px;
    }

    /* ══ ພາກສ່ວນອົງການຈັດຕັ້ງ ແລະ ເລກທີ ══ */
    .org-row { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 5px;
        margin-bottom: 10px; 
    }
    .org-left { 
        width: 42%; 
        vertical-align: top; 
        text-align: left;
    }
    .org-mid { 
        width: 16%; 
        vertical-align: middle; 
        text-align: center; 
    }
    .org-right { 
        width: 42%; 
        vertical-align: top; 
        text-align: right; 
    }
    .org-parent { 
        font-size: 10.5pt; 
        line-height: 1.4; 
        color: #111827; 
    }
    .org-name { 
        font-size: 11pt; 
        font-weight: bold; 
        line-height: 1.4; 
        color: #000000; 
        text-transform: uppercase;
    }
    .ref-line { 
        font-size: 10.5pt; 
        line-height: 1.5; 
        color: #111827; 
    }
    .seal-img { 
        width: 55px; 
        height: 55px; 
        object-fit: contain;
    }

    /* ══ ເסັ້ນຂັ້ນຫົວເອກະສານທາງການ ══ */
    .rule-thick { 
        border: 0; 
        border-top: 2px solid #000000; 
        margin: 6px 0 2px; 
    }
    .rule-thin { 
        border: 0; 
        border-top: 0.5px solid #000000; 
        margin: 0 0 15px; 
    }

    /* ══ ຫົວຂໍ້ເອກະສານ ══ */
    .title-block { 
        text-align: center; 
        margin: 15px 0 20px; 
    }
    .title-main { 
        font-size: 15pt; 
        font-weight: bold; 
        text-decoration: none; 
        line-height: 1.6; 
        color: #000000; 
    }
    .title-sub { 
        font-size: 12pt; 
        font-weight: bold; 
        margin-top: 4px; 
        color: #111827; 
    }
    .title-about { 
        font-size: 10.5pt; 
        margin-top: 6px; 
        color: #374151; 
        font-style: italic;
    }

    /* ══ ຕາຕະລາງສະຫຼຸບຕົວເລກ (ຫຼຸດສີສັນໃຫ້ເປັນທາງການຂຶ້ນ) ══ */
    .sum-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 6px 0;
        margin: 15px 0;
    }
    .sum-table td {
        width: 33.33%;
        padding: 12px;
        text-align: center;
        border-radius: 4px;
    }
    .sum-label { 
        font-size: 9pt; 
        font-weight: bold; 
        color: #374151;
    }
    .sum-value { 
        font-size: 14pt; 
        font-weight: bold; 
        margin: 4px 0 1px; 
        color: #000000;
    }
    .sum-unit { 
        font-size: 8.5pt; 
        color: #4b5563;
    }
    
    /* ປັບໂທນສີໃຫ້ອ່ອນລົງຫຼາຍ ເໝາະກັບການພິມ (Printer-friendly) */
    .c-income { 
        background: #f3faf6; 
        color: #166534; 
        border: 1px solid #d1fae5; 
    }
    .c-expense { 
        background: #fdf2f2; 
        color: #991b1b; 
        border: 1px solid #fee2e2; 
    }
    .c-bal-pos { 
        background: #fefbeb; 
        color: #b45309; 
        border: 1px solid #fef3c7; 
    }
    .c-bal-neg { 
        background: #fdf2f2; 
        color: #991b1b; 
        border: 1px solid #fca5a5; 
    }

    /* ══ ຫົວຂໍ້ພາກສ່ວນ ══ */
    .sec-title { 
        font-size: 11pt; 
        font-weight: bold; 
        border-left: 3.5px solid #111827; 
        padding-left: 8px; 
        margin: 22px 0 10px; 
        color: #000000; 
    }

    /* ══ ໝວດໝູ່ລາຍຮັບ-ລາຍຈ່າຍ ══ */
    .cat-table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-bottom: 15px; 
    }
    .cat-table td { 
        width: 50%; 
        vertical-align: top; 
    }
    .cat-table .cat-left { 
        padding-right: 8px; 
    }
    .cat-table .cat-right { 
        padding-left: 8px; 
    }
    .cat-box { 
        border: 1px solid #e5e7eb; 
        border-radius: 4px; 
        background: #fafafa; 
        padding: 12px; 
    }
    .cat-head { 
        font-size: 9.5pt; 
        font-weight: bold; 
        padding: 5px 8px; 
        margin-bottom: 10px; 
        border-radius: 3px; 
    }
    .cat-head-income { 
        background: #e6f4ea; 
        color: #137333; 
    }
    .cat-head-expense { 
        background: #fce8e6; 
        color: #c5221f; 
    }
    
    .bar-row { 
        margin-bottom: 8px; 
    }
    .bar-lbl { 
        width: 100%; 
        border-collapse: collapse; 
        margin-bottom: 4px; 
        font-size: 8.5pt; 
        color: #1f2937; 
    }
    .bar-lbl td { 
        padding: 0; 
    }
    .bar-track { 
        height: 6px; 
        background: #e5e7eb; 
        border-radius: 3px; 
        overflow: hidden; 
    }
    .bar-fill { 
        height: 6px; 
        display: block; 
        border-radius: 3px; 
    }
    .bar-income { 
        background: #34a853; 
    }
    .bar-expense { 
        background: #ea4335; 
    }
    .cat-total { 
        font-size: 9pt; 
        font-weight: bold; 
        text-align: right; 
        border-top: 1px solid #e5e7eb; 
        padding-top: 6px; 
        margin-top: 8px; 
        color: #000000; 
    }

    /* ══ ຕາຕະລາງລາຍການລະອຽດ (Formal Style) ══ */
    table.dtbl { 
        width: 100%; 
        border-collapse: collapse; 
        margin-bottom: 15px; 
        font-size: 9pt; 
        page-break-inside: auto; 
    }
    table.dtbl thead { 
        display: table-header-group; 
    }
    table.dtbl tr { 
        page-break-inside: avoid; 
    }
    table.dtbl thead tr { 
        background: #f3f4f6; 
        color: #000000; 
        border-top: 1.5px solid #000000;
        border-bottom: 1.5px solid #000000;
    }
    table.dtbl thead th { 
        padding: 8px 8px; 
        text-align: left; 
        font-weight: bold; 
    }
    table.dtbl thead th.r { 
        text-align: right; 
    }
    table.dtbl tbody tr:nth-child(even) { 
        background: #f9fafb; 
    }
    table.dtbl tbody td { 
        padding: 7px 8px; 
        border-bottom: 1px solid #e5e7eb; 
        vertical-align: top; 
        color: #1f2937; 
    }
    table.dtbl tbody td.r { 
        text-align: right; 
    }
    table.dtbl tfoot tr { 
        background: #f9fafb; 
        font-weight: bold; 
    }
    table.dtbl tfoot td { 
        padding: 8px 8px; 
        border-top: 1.5px solid #000000; 
        border-bottom: 1.5px double #000000; 
        color: #000000; 
    }
    table.dtbl tfoot td.r { 
        text-align: right; 
    }
    
    /* ປ້າຍສະແດງປະເພດແບບສຸພາບ */
    .badge-i { 
        background: #e6f4ea; 
        color: #137333; 
        border: 1px solid #ceead6; 
        padding: 2px 6px; 
        font-size: 7.5pt; 
        font-weight: bold; 
        border-radius: 2px; 
    }
    .badge-e { 
        background: #fce8e6; 
        color: #c5221f; 
        border: 1px solid #fad2cf; 
        padding: 2px 6px; 
        font-size: 7.5pt; 
        font-weight: bold; 
        border-radius: 2px; 
    }

    /* ══ ພາກສ່ວນລາຍເຊັນ ແລະ ກາປະທັບ (Lao Official Format) ══ */
    .sig-section { 
        margin-top: 30px; 
        page-break-inside: avoid; 
    }
    
    /* ຕາຕະລາງລາຍເຊັນ */
    .sig-table { 
        width: 100%; 
        border-collapse: collapse; 
    }
    .sig-table td { 
        vertical-align: top; 
    }
    
    /* ສ່ວນ "ບ່ອນສົ່ງ" ທາງດ້ານຊ້າຍລຸ່ມ ເຊິ່ງເປັນເອກະລັກຂອງເອກະສານທາງການລາວ */
    .distribution-list {
        width: 40%;
        text-align: left;
        font-size: 8.5pt;
        line-height: 1.5;
        color: #374151;
        padding-right: 20px;
    }
    .dist-title {
        font-weight: bold;
        text-decoration: underline;
        margin-bottom: 4px;
        color: #000000;
    }
    .dist-item {
        padding-left: 8px;
    }
    
    /* ບລັອກລາຍເຊັນດ້ານຂວາ */
    .signature-block {
        width: 60%;
        text-align: center;
    }
    .sig-role-label { 
        font-size: 11pt; 
        font-weight: bold; 
        margin-bottom: 65px; /* ເພີ່ມພື້ນທີ່ຫວ່າງໄວ້ເພື່ອເຊັນ ແລະ ປະທັບກາແທ້ */
        line-height: 1.4; 
        color: #000000; 
    }
    .sig-name-val { 
        font-size: 11pt; 
        font-weight: bold; 
        line-height: 1.4; 
        color: #000000; 
    }
    .sig-rank { 
        font-size: 9.5pt; 
        color: #4b5563; 
        line-height: 1.4; 
        margin-top: 2px;
    }

    /* ══ ສ່ວນທ້າຍເອກະສານ ══ */
    .doc-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        border-top: 0.5px solid #d1d5db;
        padding-top: 4px;
        font-size: 8pt;
        color: #4b5563;
        text-align: center;
        line-height: 1.5;
    }
    .page-number:before {
        content: "ໜ້າທີ " counter(page) " / " counter(pages);
    }

    .page-break { 
        page-break-before: always; 
    }
</style>
</head>
<body>

{{-- ══ ຫົວຂໍ້ເອກະສານທາງການ ສປປ ລາວ ══ --}}
<div class="state-header">
    <div class="state-republic">ສາທາລະນະລັດ ປະຊາທິປະໄຕ ປະຊາຊົນລາວ</div>
    <div class="state-motto">ສັນຕິພາບ ເອກະລາດ ປະຊາທິປະໄຕ ເອກະພາບ ວັດທະນະຖາວອນ</div>
</div>

<table class="org-row">
    <tr>
        <td class="org-left">
            <div class="org-parent">ສູນກາງອົງການພຸດທະສາສະໜາສຳພັນ</div>
            <div class="org-parent">ແຫ່ງ ສປປ ລາວ</div>
            <div class="org-name">{{ $orgName }}</div>
        </td>
        <td class="org-mid">
            @if ($orgLogoPath)
                <img src="file://{{ $orgLogoPath }}" class="seal-img" />
            @else
                <div style="width:55px;height:55px;border:1.5px solid #000;border-radius:50%;display:inline-block;text-align:center;line-height:52px;font-size:20pt;color:#000;">☸</div>
            @endif
        </td>
        <td class="org-right">
            <div class="ref-line">ເລກທີ: .................../ກສປ</div>
            <div class="ref-line">ນະຄອນຫຼວງວຽງຈັນ, ວັນທີ {{ now()->format('d') }} ເດືອນ {{ now()->format('m') }} ປີ {{ now()->format('Y') }}</div>
        </td>
    </tr>
</table>

<hr class="rule-thick" />
<hr class="rule-thin" />


{{-- ══ ຫົວຂໍ້ບົດລາຍງານ ══ --}}
<div class="title-block">
    <div class="title-main">ບົດລາຍງານ ລາຍຮັບ-ລາຍຈ່າຍ</div>
    <div class="title-sub">{{ $orgName }}</div>
    <div class="title-about">ວ່າດ້ວຍການສະຫຼຸບລາຍຮັບ-ລາຍຈ່າຍ ໄລຍະ: {{ $from }} ຫາ {{ $to }}</div>
</div>


{{-- ══ ຕາຕະລາງສະຫຼຸບຕົວເລກຫຼັກ ══ --}}
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


{{-- ══ ສະຫຼຸບຕາມໝວດໝູ່ ══ --}}
<div class="sec-title">I. ສະຫຼຸບຕາມໝວດໝູ່ລາຍຮັບ-ລາຍຈ່າຍ</div>
<table class="cat-table">
    <tr>
        <td class="cat-left">
            <div class="cat-box">
                <div class="cat-head cat-head-income">ລາຍຮັບ (Income Summary)</div>
                @if (isset($byCategory['income']) && $byCategory['income']->isNotEmpty())
                    @php $incTotal = $byCategory['income']->sum('total'); @endphp
                    @foreach ($byCategory['income']->sortByDesc('total') as $row)
                        @php $pct = $incTotal > 0 ? round(($row->total / $incTotal) * 100) : 0; @endphp
                        <div class="bar-row">
                            <table class="bar-lbl"><tr>
                                <td>{{ $row->category->name ?? 'ບໍ່ມີຊື່' }} ({{ $row->count }})</td>
                                <td style="text-align:right;">{{ number_format((float)$row->total,0,'.',',') }} ({{ $pct }}%)</td>
                            </tr></table>
                            <div class="bar-track"><div class="bar-fill bar-income" style="width:{{ $pct }}%;"></div></div>
                        </div>
                    @endforeach
                    <div class="cat-total">ລວມລາຍຮັບ: {{ number_format((float)$incTotal,0,'.',',') }} ກີບ</div>
                @else
                    <p style="color:#6b7280;font-size:9pt;padding:4px 0;font-style:italic;">ບໍ່ມີຂໍ້ມູນລາຍຮັບ</p>
                @endif
            </div>
        </td>
        <td class="cat-right">
            <div class="cat-box">
                <div class="cat-head cat-head-expense">ລາຍຈ່າຍ (Expense Summary)</div>
                @if (isset($byCategory['expense']) && $byCategory['expense']->isNotEmpty())
                    @php $expTotal = $byCategory['expense']->sum('total'); @endphp
                    @foreach ($byCategory['expense']->sortByDesc('total') as $row)
                        @php $pct = $expTotal > 0 ? round(($row->total / $expTotal) * 100) : 0; @endphp
                        <div class="bar-row">
                            <table class="bar-lbl"><tr>
                                <td>{{ $row->category->name ?? 'ບໍ່ມີຊື່' }} ({{ $row->count }})</td>
                                <td style="text-align:right;">{{ number_format((float)$row->total,0,'.',',') }} ({{ $pct }}%)</td>
                            </tr></table>
                            <div class="bar-track"><div class="bar-fill bar-expense" style="width:{{ $pct }}%;"></div></div>
                        </div>
                    @endforeach
                    <div class="cat-total">ລວມລາຍຈ່າຍ: {{ number_format((float)$expTotal,0,'.',',') }} ກີບ</div>
                @else
                    <p style="color:#6b7280;font-size:9pt;padding:4px 0;font-style:italic;">ບໍ່ມີຂໍ້ມູນລາຍຈ່າຍ</p>
                @endif
            </div>
        </td>
    </tr>
</table>


{{-- ══ ຕາຕະລາງທຸລະກໍາ ══ --}}
<div class="sec-title">II. ຕາຕະລາງລາຍການທຸລະກໍາຢ່າງລະອຽດ</div>
@if ($transactions->isEmpty())
    <p style="text-align:center;color:#6b7280;padding:20px;font-style:italic;">ບໍ່ມີຂໍ້ມູນທຸລະກໍາໃນໄລຍະນີ້</p>
@else
    <table class="dtbl">
        <thead>
            <tr>
                <th style="width:12%">ວັນທີ</th>
                <th style="width:10%">ປະເພດ</th>
                <th style="width:18%">ໝວດໝູ່</th>
                <th>ລາຍລະອຽດ</th>
                <th style="width:20%" class="r">ຈໍານວນ (ກີບ)</th>
                <th style="width:12%">ເລກທີອ້າງອີງ</th>
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
                <td class="r" style="color:{{ $tx->is_income ? '#137333' : '#c5221f' }};font-weight:bold;">
                    {{ $tx->is_income ? '+' : '-' }}{{ number_format((float)$tx->amount, 0, '.', ',') }}
                </td>
                <td>{{ $tx->reference_number ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">ລວມຍອດທັງໝົດ ({{ $transactions->count() }} ລາຍການ)</td>
                <td class="r" style="color:{{ $netBalance >= 0 ? '#137333' : '#c5221f' }};">
                    {{ $netBalance >= 0 ? '+' : '' }}{{ number_format((float)$netBalance, 0, '.', ',') }} ກີບ
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
@endif


{{-- ══ ພາກສ່ວນລາຍເຊັນ ແລະ ກາປະທັບ ══ --}}
<div class="sig-section">
    <table class="sig-table">
        <tr>
            <!-- ບ່ອນສົ່ງ (Distribution list) ຕາມຮູບແບບທາງການລາວ -->
            <td class="distribution-list">
                <div class="dist-title">ບ່ອນສົ່ງ:</div>
                <div class="dist-item">- ຫ້ອງການ ກສປ "ເພື່ອຊາບ"</div>
                <div class="dist-item">- ຄະນະກໍາມະການ "ເພື່ອຕິດຕາມ"</div>
                <div class="dist-item">- ເກັບມ້ຽນເອກະສານ 1 ສະບັບ</div>
            </td>
            
            <!-- ບລັອກລາຍເຊັນຂອງຜູ້ອະນຸມັດ -->
            <td class="signature-block">
                <div class="sig-role-label">
                    ຜູ້ລາຍງານ / ຫົວໜ້າຄະນະກໍາມະການການເງິນ<br/>
                    (ເຊັນ ແລະ ປະທັບກາ)
                </div>
                <div class="sig-name-val">( ພຣະອາຈານໃຫຍ່ ບຸນທະວີ ປະສິດທິສັກ )</div>
                <div class="sig-rank">ຫົວໜ້າ ກັມມາທິການສາທາຣະນູປະການ ສູນກາງ ອພສ</div>
            </td>
        </tr>
    </table>
</div>


{{-- ══ ສ່ວນທ້າຍເອກະສານ (Footer) ══ --}}
<div class="doc-footer">
    ຫ້ອງການ {{ $orgName }} ສູນກາງອົງການພຸດທະສາສະໜາສຳພັນ ແຫ່ງ ສປປ ລາວ &nbsp;|&nbsp; ສໍານັກງານ: {{ $orgAddress }}<br/>
    @if ($orgPhone)ໂທ: {{ $orgPhone }}@endif
    @if ($orgEmail) &nbsp;|&nbsp; ອີເມວ: {{ $orgEmail }}@endif
    &nbsp;|&nbsp; ລະບົບ Buddhist EMS ວັນທີ {{ now()->format('d/m/Y H:i') }}
    &nbsp;|&nbsp; <span class="page-number"></span>
</div>

</body>
</html>