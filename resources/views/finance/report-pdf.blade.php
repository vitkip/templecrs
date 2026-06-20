<!DOCTYPE html>
<html lang="lo">
<head>
<meta charset="UTF-8" />
<style>
    /* ══ ມາດຕະຖານຂອບເຈ້ຍເອກະສານທາງການລາວ ══ */
    @page {
        margin-top: 1cm;
        margin-bottom: 1cm;
        margin-left: 2.5cm;
        margin-right: 1.5cm;
    }

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

    * { 
        box-sizing: border-box; 
    }
    
    /* Reset margins/paddings on content elements, leaving html/body to respect @page margins */
    th, td, p, div, span, h1, h2, h3, h4, h5, h6, table, img, ul, ol, li {
        margin: 0;
        padding: 0;
    }
    
    body {
        font-family: 'Phetsarath', sans-serif;
        font-size: 11pt; /* ມາດຕະຖານເອກະສານທາງການ */
        color: #000000;
        background: #ffffff;
        line-height: 1.6;
    }

    /* ══ ສ່ວນຫົວຂໍ້ຄຳຂວັນປະເທດ ══ */
    .state-header { 
        text-align: center; 
        margin-bottom: 15px; 
        line-height: 1.1; 
    }
    .state-republic { 
        font-size: 12pt; 
        font-weight: bold; 
    }
    .state-motto { 
        font-size: 11pt; 
        font-weight: bold;
        margin-top: 3px;
    }

    /* ══ ພາກສ່ວນອົງການຈັດຕັ້ງ ແລະ ເລກທີ ══ */
    .org-row {
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
        margin-bottom: 10px;
    }
    .org-left, .org-right {
        width: 45%;
        vertical-align: top;
        line-height: 1.4;
    }
    .org-left { text-align: left; }
    .org-right { text-align: right; }
    .org-mid {
        width: 10%;
        vertical-align: middle;
        text-align: center;
    }
    .org-parent { 
        font-size: 10.5pt; 
        text-align: center;
    }
    .org-name { 
        font-size: 11pt; 
        text-align: center;
        
    }
    .ref-line { 
        font-size: 10.5pt; 
    }
    .seal-img { 
        width: 60px; 
        height: 60px; 
        object-fit: contain;
    }

 

    /* ══ ຫົວຂໍ້ເອກະສານ ══ */
    .title-block { 
        text-align: center; 
        margin: 20px 0; 
        line-height: 1.1;
    }
    .title-main { 
        font-size: 16pt; 
        font-weight: bold; 
        line-height: 1.6; 
    }
    .title-sub { 
        font-size: 12pt; 
        font-weight: bold; 
        margin-top: 4px; 
    }
    .title-about { 
        font-size: 11pt; 
        margin-top: 6px; 
    }

    /* ══ ຕາຕະລາງສະຫຼຸບຕົວເລກທາງການ ══ */
    .sum-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    .sum-table th, .sum-table td {
        border: 0.5px solid #000000;
        padding: 8px 10px;
        text-align: center;
        vertical-align: middle;
    }
    .sum-table th {
        background-color: #f2f2f2;
        font-weight: bold;
        font-size: 10.5pt;
    }
    .sum-table td {
        font-size: 11pt;
        font-weight: bold;
    }

    /* ══ ຫົວຂໍ້ພາກສ່ວນ ══ */
    .sec-title { 
        font-size: 11pt; 
        font-weight: bold; 
        margin: 25px 0 10px; 
        text-decoration: underline;
    }

    /* ══ ໝວດໝູ່ລາຍຮັບ-ລາຍຈ່າຍ ══ */
    .cat-table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-bottom: 20px; 
    }
    .cat-col { 
        width: 50%; 
        vertical-align: top; 
    }
    .cat-left { 
        padding-right: 8px; 
    }
    .cat-right { 
        padding-left: 8px; 
    }
    .cat-sub-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9.5pt;
    }
    .cat-sub-table th, .cat-sub-table td {
        border: 0.5px solid #000000;
        padding: 6px 8px;
        vertical-align: middle;
        text-align: left;
    }
    .cat-sub-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    /* ══ ຕາຕະລາງລາຍການລະອຽດ ══ */
    table.dtbl { 
        width: 100%; 
        border-collapse: collapse; 
        margin-bottom: 20px; 
        font-size: 9.5pt; 
        page-break-inside: auto; 
    }
    table.dtbl thead { 
        display: table-header-group; 
    }
    table.dtbl tr { 
        page-break-inside: avoid; 
    }
    table.dtbl th, table.dtbl td {
        border: 0.5px solid #000000;
        padding: 6px 8px;
        vertical-align: middle;
    }
    table.dtbl thead th { 
        background: #f2f2f2; 
        font-weight: bold; 
        text-align: left; 
    }
    table.dtbl thead th.r, table.dtbl td.r { 
        text-align: right; 
    }
    table.dtbl thead th.c, table.dtbl td.c { 
        text-align: center; 
    }
    table.dtbl tfoot tr { 
        font-weight: bold; 
        background: #f9fafb;
    }
    table.dtbl tfoot td { 
        border-top: 1.5px solid #000000; 
        border-bottom: 1.5px double #000000; 
    }

    /* ══ ພາກສ່ວນລາຍເຊັນ ແລະ ກາປະທັບ ══ */
    .sig-section { 
        margin-top: 30px; 
        margin-bottom: 50px; 
        page-break-inside: avoid; 
    }
    .sig-table { 
        width: 100%; 
        border-collapse: collapse; 
    }
    .sig-table td { 
        vertical-align: top; 
    }
    .distribution-list {
        width: 40%;
        text-align: left;
        font-size: 9pt;
        line-height: 1.5;
        padding-right: 20px;
    }
    .dist-title {
        font-weight: bold;
        text-decoration: underline;
        margin-bottom: 4px;
    }
    .dist-item {
        padding-left: 8px;
    }
    .signature-block {
        width: 60%;
        text-align: center;
    }
    .sig-role-label { 
        font-size: 11pt; 
        font-weight: bold; 
        margin-bottom: 70px; /* ພື້ນທີ່ຫວ່າງສຳລັບເຊັນ ແລະ ປະທັບກາ */
        line-height: 1.4; 
    }
    .sig-name-val { 
        font-size: 11pt; 
        font-weight: bold; 
        line-height: 1.4; 
    }
    .sig-rank { 
        font-size: 10pt; 
        line-height: 1.4; 
        margin-top: 2px;
    }

    /* ══ ສ່ວນທ້າຍເອກະສານ ══ */
    .doc-footer {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        border-top: 1.5px solid #000000;
        padding-top: 4px;
        font-size: 8.5pt;
        text-align: left;
        line-height: 1;
    }
    .page-number:before {
        content: "ໜ້າທີ " counter(page);
    }
</style>
</head>
<body>

@php
    $laoMonths = ['ມັງກອນ', 'ກຸມພາ', 'ມີນາ', 'ເມສາ', 'ພຶດສະພາ', 'ມິຖຸນາ', 'ກໍລະກົດ', 'ສິງຫາ', 'ກັນຍາ', 'ຕຸລາ', 'ພະຈິກ', 'ທັນວາ'];
    
    $fromTime = strtotime($from);
    $toTime = strtotime($to);
    
    $fromDay = date('j', $fromTime);
    $fromMonth = $laoMonths[date('n', $fromTime) - 1];
    $fromYear = date('Y', $fromTime);
    
    $toDay = date('j', $toTime);
    $toMonth = $laoMonths[date('n', $toTime) - 1];
    $toYear = date('Y', $toTime);
    
    $currentDay = now()->day;
    $currentMonth = $laoMonths[now()->month - 1];
    $currentYear = now()->year;
@endphp

{{-- ══ ຫົວຂໍ້ເອກະສານທາງການ ສປປ ລາວ ══ --}}
<div class="state-header">
    <div class="state-republic">ສາທາລະນະລັດ ປະຊາທິປະໄຕ ປະຊາຊົນລາວ</div>
    <div class="state-motto">ສັນຕິພາບ ເອກະລາດ ປະຊາທິປະໄຕ ເອກະພາບ ວັດທະນະຖາວອນ</div>
</div>

<table class="org-row">
    <tr>
        <td class="org-left">
            <div class="org-parent">ສູນກາງອົງການພຸດທະສາສະໜາສຳພັນ</div>
            <div class="org-name">ແຫ່ງ ສປປ ລາວ</div>
            <div class="org-name">ກັມມາທິການສາທາຣະນູປະການ</div>
        </td>
        <td class="org-mid">
            @if ($orgLogoPath)
                <img src="file://{{ $orgLogoPath }}" class="seal-img" />
            @else
                <div style="width:60px;height:60px;border:1.5px solid #000;border-radius:50%;display:inline-block;text-align:center;line-height:56px;font-size:22pt;color:#000;">☸</div>
            @endif
        </td>
        <td class="org-right" style="padding-top: 45pt;>
            <div class="ref-line">ເລກທີ:......../ກສປ</div>
            <div class="ref-line">ນະຄອນຫຼວງວຽງຈັນ, ວັນທີ {{ $currentDay }} ເດືອນ {{ $currentMonth }} ປີ {{ $currentYear }}</div>
        </td>
    </tr>
</table>


{{-- ══ ຫົວຂໍ້ບົດລາຍງານ ══ --}}
<div class="title-block">
    <div class="title-main">ບົດລາຍງານສະຫຼຸບ ລາຍຮັບ-ລາຍຈ່າຍ</div>
    <div class="title-about">ກັມມາທິການສາທາຣະນູປະການ ສູນກາງ ອພສ ຄະນະກໍາມະການຮັບຜິດຊອບການເງີນ-ການບັນຊີ ຂໍສະຫລຸບລາຍຮັບລາຍຈ່າຍ ໃນຄັ້ງ: ວັນທີ {{ $fromDay }} {{ $fromMonth }} {{ $fromYear }} ຫາ ວັນທີ {{ $toDay }} {{ $toMonth }} {{ $toYear }}</div>
</div>

{{-- ══ ຕາຕະລາງສະຫຼຸບຕົວເລກຫຼັກ ══ --}}
<table class="sum-table">
    <thead>
        <tr>
            <th>ລາຍຮັບທັງໝົດ</th>
            <th>ລາຍຈ່າຍທັງໝົດ</th>
            <th>ຍອດສຸດທິ (ຍອດເຫຼືອ)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ number_format((float)$totalIncome, 0, '.', ',') }} ກີບ</td>
            <td>{{ number_format((float)$totalExpense, 0, '.', ',') }} ກີບ</td>
            <td>{{ ($netBalance >= 0 ? '+' : '') . number_format((float)$netBalance, 0, '.', ',') }} ກີບ</td>
        </tr>
    </tbody>
</table>

{{-- ══ ສະຫຼຸບຕາມໝວດໝູ່ ══ --}}
<div class="sec-title">I. ສະຫຼຸບຕາມໝວດໝູ່ລາຍຮັບ-ລາຍຈ່າຍ</div>
<table class="cat-table">
    <tr>
        <td class="cat-col cat-left">
            <table class="cat-sub-table">
                <thead>
                    <tr>
                        <th colspan="3" style="text-align: center;">ໝວດໝູ່ລາຍຮັບ (Income Summary)</th>
                    </tr>
                    <tr>
                        <th>ຊື່ໝວດໝູ່</th>
                        <th style="text-align: center; width: 20%;">ຈຳນວນ</th>
                        <th style="text-align: right; width: 40%;">ມູນຄ່າ (ກີບ)</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($byCategory['income']) && $byCategory['income']->isNotEmpty())
                        @php $incTotal = $byCategory['income']->sum('total'); @endphp
                        @foreach ($byCategory['income']->sortByDesc('total') as $row)
                            <tr>
                                <td>{{ $row->category->name ?? 'ບໍ່ມີຊື່' }}</td>
                                <td style="text-align: center;">{{ $row->count }}</td>
                                <td style="text-align: right;">{{ number_format((float)$row->total, 0, '.', ',') }}</td>
                            </tr>
                        @endforeach
                        <tr style="font-weight: bold; background-color: #f9fafb;">
                            <td colspan="2">ລວມລາຍຮັບທັງໝົດ</td>
                            <td style="text-align: right;">{{ number_format((float)$incTotal, 0, '.', ',') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="3" style="text-align: center; color: #6b7280; font-style: italic;">ບໍ່ມີຂໍ້ມູນລາຍຮັບ</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </td>
        <td class="cat-col cat-right">
            <table class="cat-sub-table">
                <thead>
                    <tr>
                        <th colspan="3" style="text-align: center;">ໝວດໝູ່ລາຍຈ່າຍ (Expense Summary)</th>
                    </tr>
                    <tr>
                        <th>ຊື່ໝວດໝູ່</th>
                        <th style="text-align: center; width: 20%;">ຈຳນວນ</th>
                        <th style="text-align: right; width: 40%;">ມູນຄ່າ (ກີບ)</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($byCategory['expense']) && $byCategory['expense']->isNotEmpty())
                        @php $expTotal = $byCategory['expense']->sum('total'); @endphp
                        @foreach ($byCategory['expense']->sortByDesc('total') as $row)
                            <tr>
                                <td>{{ $row->category->name ?? 'ບໍ່ມີຊື່' }}</td>
                                <td style="text-align: center;">{{ $row->count }}</td>
                                <td style="text-align: right;">{{ number_format((float)$row->total, 0, '.', ',') }}</td>
                            </tr>
                        @endforeach
                        <tr style="font-weight: bold; background-color: #f9fafb;">
                            <td colspan="2">ລວມລາຍຈ່າຍທັງໝົດ</td>
                            <td style="text-align: right;">{{ number_format((float)$expTotal, 0, '.', ',') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="3" style="text-align: center; color: #6b7280; font-style: italic;">ບໍ່ມີຂໍ້ມູນລາຍຈ່າຍ</td>
                        </tr>
                    @endif
                </tbody>
            </table>
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
                <th style="width:12%; text-align: center;">ປະເພດ</th>
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
                <td style="text-align: center;">
                    {{ $tx->is_income ? 'ລາຍຮັບ' : 'ລາຍຈ່າຍ' }}
                </td>
                <td>{{ $tx->category->name ?? '—' }}</td>
                <td>{{ $tx->description }}</td>
                <td class="r" style="font-weight:bold;">
                    {{ $tx->is_income ? '+' : '-' }}{{ number_format((float)$tx->amount, 0, '.', ',') }}
                </td>
                <td>{{ $tx->reference_number ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">ລວມຍອດສຸດທິ ({{ $transactions->count() }} ລາຍການ)</td>
                <td class="r">
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
              
            </td>
            
            <!-- ບລັອກລາຍເຊັນຂອງຜູ້ອະນຸມັດ -->
            <td class="signature-block">
                <div class="sig-role-label">
                    ຜູ້ລາຍງານ / ຫົວໜ້າຄະນະກໍາມະການການເງິນ<br/>                   
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- ══ ສ່ວນທ້າຍເອກະສານ (Footer) ══ --}}
<div class="doc-footer">
    ຫ້ອງການ {{ $orgName }}ກັມມາທິການສາທາຣະນູປະການ ສູນກາງອົງການພຸດທະສາສະໜາສຳພັນ ແຫ່ງ ສປປ ລາວ  ສໍານັກງານ ຕັ້ງຢູ່ທີ່ {{ $orgAddress }}
    @if ($orgPhone)ໂທ: {{ $orgPhone }}@endif
    @if ($orgEmail) ອີເມວ: {{ $orgEmail }}@endif
    @if ($orgWebsite) website:{{ $orgWebsite }}@endif
</div>

</body>
</html>