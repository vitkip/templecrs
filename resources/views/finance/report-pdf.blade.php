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

    * { box-sizing: border-box; }

    th, td, p, div, span, h1, h2, h3, h4, h5, h6, table, img, ul, ol, li {
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Phetsarath', sans-serif;
        font-size: 11pt;
        color: #000000;
        background: #ffffff;
        line-height: 1.6;
    }

    /* ══ ສ່ວນຫົວຂໍ້ຄຳຂວັນປະເທດ ══ */
    .state-header { text-align: center; margin-bottom: 15px; line-height: 1.1; }
    .state-republic { font-size: 12pt; font-weight: bold; }
    .state-motto { font-size: 11pt; font-weight: bold; margin-top: 3px; }

    /* ══ ພາກສ່ວນອົງການຈັດຕັ້ງ ══ */
    .org-row { width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 10px; }
    .org-left, .org-right { width: 45%; vertical-align: top; line-height: 1.4; }
    .org-left { text-align: left; }
    .org-right { text-align: right; }
    .org-mid { width: 10%; vertical-align: middle; text-align: center; }
    .org-parent { font-size: 10.5pt; text-align: center; }
    .org-name { font-size: 11pt; text-align: center; }
    .ref-line { font-size: 10.5pt; }
    .seal-img { width: 60px; height: 60px; object-fit: contain; }

    /* ══ ຫົວຂໍ້ເອກະສານ ══ */
    .title-block { text-align: center; margin: 20px 0; line-height: 1.1; }
    .title-main { font-size: 16pt; font-weight: bold; line-height: 1.6; }
    .title-sub { font-size: 12pt; font-weight: bold; margin-top: 4px; }
    .title-about { font-size: 11pt; margin-top: 6px; }

    /* ══ ຕາຕະລາງສະຫຼຸບຕາມສະກຸນເງີນ ══ */
    .sum-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    .sum-table th, .sum-table td {
        border: 0.5px solid #000000;
        padding: 7px 10px;
        text-align: center;
        vertical-align: middle;
    }
    .sum-table th {
        background-color: #f2f2f2;
        font-weight: bold;
        font-size: 10.5pt;
    }
    .sum-table td { font-size: 10.5pt; }
    .sum-table td.currency-label { font-weight: bold; text-align: left; }
    .sum-table td.positive { color: #166534; font-weight: bold; }
    .sum-table td.negative { color: #991b1b; font-weight: bold; }
    .sum-table td.neutral { font-weight: bold; }
    .sum-table tfoot td {
        font-weight: normal;
        background: #f9fafb;
        border-top: 1.5px solid #000;
        font-size: 9.5pt;
        color: #444444;
    }

    /* ══ ຫົວຂໍ້ພາກສ່ວນ ══ */
    .sec-title {
        font-size: 11pt;
        font-weight: bold;
        margin: 25px 0 10px;
        text-decoration: underline;
    }
    .currency-section-title {
        font-size: 10.5pt;
        font-weight: bold;
        background: #e8e8e8;
        border: 0.5px solid #000;
        padding: 5px 10px;
        margin: 12px 0 6px;
    }

    /* ══ ໝວດໝູ່ລາຍຮັບ-ລາຍຈ່າຍ ══ */
    .cat-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    .cat-col { width: 50%; vertical-align: top; }
    .cat-left { padding-right: 6px; }
    .cat-right { padding-left: 6px; }
    .cat-sub-table { width: 100%; border-collapse: collapse; font-size: 9.5pt; }
    .cat-sub-table th, .cat-sub-table td {
        border: 0.5px solid #000000;
        padding: 5px 7px;
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
        margin-bottom: 6px;
        font-size: 9.5pt;
        page-break-inside: auto;
    }
    table.dtbl thead { display: table-header-group; }
    table.dtbl tr { page-break-inside: avoid; }
    table.dtbl th, table.dtbl td {
        border: 0.5px solid #000000;
        padding: 5px 7px;
        vertical-align: middle;
    }
    table.dtbl thead th {
        background: #f2f2f2;
        font-weight: bold;
        text-align: left;
    }
    table.dtbl thead th.r, table.dtbl td.r { text-align: right; }
    table.dtbl thead th.c, table.dtbl td.c { text-align: center; }
    table.dtbl tfoot tr { font-weight: bold; background: #f9fafb; }
    table.dtbl tfoot td {
        border-top: 1.5px solid #000000;
        border-bottom: 1.5px double #000000;
    }
    .dtbl-currency-header {
        font-size: 10pt;
        font-weight: bold;
        background: #e8e8e8;
        border: 0.5px solid #000;
        padding: 5px 10px;
        margin: 14px 0 4px;
    }

    /* ══ ພາກສ່ວນລາຍເຊັນ ══ */
    .sig-section { margin-top: 30px; margin-bottom: 50px; page-break-inside: avoid; }
    .sig-table { width: 100%; border-collapse: collapse; }
    .sig-table td { vertical-align: top; }
    .distribution-list { width: 40%; text-align: left; font-size: 9pt; line-height: 1.5; padding-right: 20px; }
    .dist-title { font-weight: bold; text-decoration: underline; margin-bottom: 4px; }
    .dist-item { padding-left: 8px; }
    .signature-block { width: 60%; text-align: center; }
    .sig-role-label { font-size: 11pt; font-weight: bold; margin-bottom: 70px; line-height: 1.4; }
    .sig-name-val { font-size: 11pt; font-weight: bold; line-height: 1.4; }
    .sig-rank { font-size: 10pt; line-height: 1.4; margin-top: 2px; }

    /* ══ ສ່ວນທ້າຍເອກະສານ ══ */
    .doc-footer {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        border-top: 1.5px solid #000000;
        padding-top: 4px;
        font-size: 8.5pt;
        text-align: left;
        line-height: 1;
    }
    .page-number:before { content: "ໜ້າທີ " counter(page); }

    .empty-note { text-align: center; color: #6b7280; padding: 12px; font-size: 9.5pt; }
</style>
</head>
<body>

@php
    $laoMonths = ['ມັງກອນ','ກຸມພາ','ມີນາ','ເມສາ','ພຶດສະພາ','ມິຖຸນາ','ກໍລະກົດ','ສິງຫາ','ກັນຍາ','ຕຸລາ','ພະຈິກ','ທັນວາ'];

    $fromTime = strtotime($from);
    $toTime   = strtotime($to);

    $fromDay   = date('j', $fromTime);
    $fromMonth = $laoMonths[date('n', $fromTime) - 1];
    $fromYear  = date('Y', $fromTime);

    $toDay   = date('j', $toTime);
    $toMonth = $laoMonths[date('n', $toTime) - 1];
    $toYear  = date('Y', $toTime);

    $currentDay   = now()->day;
    $currentMonth = $laoMonths[now()->month - 1];
    $currentYear  = now()->year;

    // helper: format amount for a given currency code
    $fmt = function(float $amount, string $code) use ($currencies): string {
        $cfg = $currencies[$code] ?? ['decimals' => 0, 'name_lo' => $code];
        return number_format($amount, $cfg['decimals'], '.', ',') . ' ' . $cfg['name_lo'];
    };

    // currencies that actually have data in this period
    $activeCurrencies = array_keys($byCurrencyMap);
    $totalTxCount     = $transactions->count();
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
        <td class="org-right" style="padding-top: 45pt;">
            <div class="ref-line">ເລກທີ:......../ກສປ</div>
            <div class="ref-line">ນະຄອນຫຼວງວຽງຈັນ, ວັນທີ {{ $currentDay }} ເດືອນ {{ $currentMonth }} ປີ {{ $currentYear }}</div>
        </td>
    </tr>
</table>

{{-- ══ ຫົວຂໍ້ບົດລາຍງານ ══ --}}
<div class="title-block">
    <div class="title-main">ບົດລາຍງານສະຫຼຸບ ລາຍຮັບ-ລາຍຈ່າຍ</div>
    <div class="title-about">
        ກັມມາທິການສາທາຣະນູປະການ ສູນກາງ ອພສ ຄະນະກໍາມະການຮັບຜິດຊອບການເງີນ-ການບັນຊີ
        ຂໍສະຫລຸບລາຍຮັບລາຍຈ່າຍ ໃນຄັ້ງ: ວັນທີ {{ $fromDay }} {{ $fromMonth }} {{ $fromYear }}
        ຫາ ວັນທີ {{ $toDay }} {{ $toMonth }} {{ $toYear }}
    </div>
</div>

{{-- ══ I. ຕາຕະລາງສະຫຼຸບຕາມສະກຸນເງີນ ══ --}}
<div class="sec-title">I. ສະຫຼຸບລາຍຮັບ-ລາຍຈ່າຍ ແຍກຕາມສະກຸນເງີນ</div>

@if (empty($byCurrencyMap))
    <p class="empty-note">ບໍ່ມີຂໍ້ມູນທຸລະກໍາໃນໄລຍະນີ້</p>
@else
    <table class="sum-table">
        <thead>
            <tr>
                <th style="width:22%; text-align:left;">ສະກຸນເງີນ</th>
                <th style="width:26%;">ລາຍຮັບທັງໝົດ</th>
                <th style="width:26%;">ລາຍຈ່າຍທັງໝົດ</th>
                <th style="width:26%;">ຍອດສຸດທິ (ຍອດເຫຼືອ)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($byCurrencyMap as $code => $row)
                @php
                    $cfg     = $currencies[$code] ?? ['symbol' => '', 'name_lo' => $code, 'decimals' => 2];
                    $balance = $row['balance'];
                @endphp
                <tr>
                    <td class="currency-label">{{ $cfg['symbol'] }} {{ $code }} ({{ $cfg['name_lo'] }})</td>
                    <td>{{ $fmt($row['income'], $code) }}</td>
                    <td>{{ $fmt($row['expense'], $code) }}</td>
                    <td class="{{ $balance > 0 ? 'positive' : ($balance < 0 ? 'negative' : 'neutral') }}">
                        {{ ($balance >= 0 ? '+' : '') . $fmt(abs($balance), $code) }}
                        @if ($balance < 0)(ຂາດດຸນ)@elseif($balance > 0)(ເກີນດຸນ)@endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        @if (count($byCurrencyMap) > 1)
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:center;">
                    ໝາຍເຫດ: ຈໍານວນເງີນແຕ່ລະສະກຸນເງີນ ບໍ່ໄດ້ລວມກັນ ເພາະໃຊ້ຫົວໜ່ວຍສະກຸນເງີນຕ່າງກັນ
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
@endif

{{-- ══ II. ສະຫຼຸບຕາມໝວດໝູ່ (ແຍກຕາມສະກຸນເງີນ) ══ --}}
<div class="sec-title">II. ສະຫຼຸບຕາມໝວດໝູ່ ລາຍຮັບ-ລາຍຈ່າຍ</div>

@php
    $hasCategoryData = false;
    foreach ($activeCurrencies as $code) {
        if (!empty($byCategory['income'][$code]) || !empty($byCategory['expense'][$code])) {
            $hasCategoryData = true;
            break;
        }
    }
@endphp

@if (!$hasCategoryData)
    <p class="empty-note">ບໍ່ມີຂໍ້ມູນໝວດໝູ່</p>
@else
    @foreach ($activeCurrencies as $code)
        @php
            $cfg        = $currencies[$code] ?? ['symbol' => '', 'name_lo' => $code, 'decimals' => 2];
            $incRows    = $byCategory['income'][$code]  ?? collect();
            $expRows    = $byCategory['expense'][$code] ?? collect();
            $hasInc     = !empty($incRows) && (is_a($incRows, 'Illuminate\Support\Collection') ? $incRows->isNotEmpty() : count($incRows) > 0);
            $hasExp     = !empty($expRows) && (is_a($expRows, 'Illuminate\Support\Collection') ? $expRows->isNotEmpty() : count($expRows) > 0);
        @endphp
        @if ($hasInc || $hasExp)
            <div class="currency-section-title">
                {{ $cfg['symbol'] }} {{ $code }} — {{ $cfg['name_lo'] }}
            </div>
            <table class="cat-table">
                <tr>
                    <td class="cat-col cat-left">
                        <table class="cat-sub-table">
                            <thead>
                                <tr>
                                    <th colspan="3" style="text-align:center;">ໝວດໝູ່ລາຍຮັບ (Income)</th>
                                </tr>
                                <tr>
                                    <th>ຊື່ໝວດໝູ່</th>
                                    <th style="text-align:center; width:16%;">ຈຳນວນ</th>
                                    <th style="text-align:right; width:38%;">ມູນຄ່າ ({{ $cfg['name_lo'] }})</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($hasInc)
                                    @php $incTotal = $incRows->sum('total'); @endphp
                                    @foreach ($incRows as $row)
                                        <tr>
                                            <td>{{ $row->category->name ?? 'ບໍ່ມີຊື່' }}</td>
                                            <td style="text-align:center;">{{ $row->count }}</td>
                                            <td style="text-align:right;">{{ number_format((float)$row->total, $cfg['decimals'], '.', ',') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr style="font-weight:bold; background:#f9fafb;">
                                        <td colspan="2">ລວມລາຍຮັບ</td>
                                        <td style="text-align:right;">{{ number_format((float)$incTotal, $cfg['decimals'], '.', ',') }}</td>
                                    </tr>
                                @else
                                    <tr><td colspan="3" class="empty-note">ບໍ່ມີ</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </td>
                    <td class="cat-col cat-right">
                        <table class="cat-sub-table">
                            <thead>
                                <tr>
                                    <th colspan="3" style="text-align:center;">ໝວດໝູ່ລາຍຈ່າຍ (Expense)</th>
                                </tr>
                                <tr>
                                    <th>ຊື່ໝວດໝູ່</th>
                                    <th style="text-align:center; width:16%;">ຈຳນວນ</th>
                                    <th style="text-align:right; width:38%;">ມູນຄ່າ ({{ $cfg['name_lo'] }})</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($hasExp)
                                    @php $expTotal = $expRows->sum('total'); @endphp
                                    @foreach ($expRows as $row)
                                        <tr>
                                            <td>{{ $row->category->name ?? 'ບໍ່ມີຊື່' }}</td>
                                            <td style="text-align:center;">{{ $row->count }}</td>
                                            <td style="text-align:right;">{{ number_format((float)$row->total, $cfg['decimals'], '.', ',') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr style="font-weight:bold; background:#f9fafb;">
                                        <td colspan="2">ລວມລາຍຈ່າຍ</td>
                                        <td style="text-align:right;">{{ number_format((float)$expTotal, $cfg['decimals'], '.', ',') }}</td>
                                    </tr>
                                @else
                                    <tr><td colspan="3" class="empty-note">ບໍ່ມີ</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        @endif
    @endforeach
@endif

{{-- ══ III. ລາຍງານທຸລະກໍາລະອຽດ (ແຍກຕາມສະກຸນເງີນ) ══ --}}
<div class="sec-title">III. ຕາຕະລາງລາຍການທຸລະກໍາຢ່າງລະອຽດ</div>

@if ($transactions->isEmpty())
    <p class="empty-note">ບໍ່ມີຂໍ້ມູນທຸລະກໍາໃນໄລຍະນີ້</p>
@else
    @php
        $txByCurrency = [];
        foreach ($activeCurrencies as $code) {
            $group = $transactions->where('currency', $code)->values();
            if ($group->isNotEmpty()) {
                $txByCurrency[$code] = $group;
            }
        }
        // catch any currency not in byCurrencyMap (shouldn't happen but safe)
        foreach ($transactions->pluck('currency')->unique() as $code) {
            if (!isset($txByCurrency[$code])) {
                $txByCurrency[$code] = $transactions->where('currency', $code)->values();
            }
        }
    @endphp

    @foreach ($txByCurrency as $code => $txGroup)
        @php
            $cfg      = $currencies[$code] ?? ['symbol' => '', 'name_lo' => $code, 'decimals' => 2];
            $summary  = $byCurrencyMap[$code] ?? ['income' => 0, 'expense' => 0, 'balance' => 0];
            $netBal   = $summary['balance'];
        @endphp

        <div class="dtbl-currency-header">
            {{ $cfg['symbol'] }} {{ $code }} ({{ $cfg['name_lo'] }}) — {{ $txGroup->count() }} ລາຍການ
            &nbsp;|&nbsp; ລາຍຮັບ: {{ $fmt($summary['income'], $code) }}
            &nbsp;|&nbsp; ລາຍຈ່າຍ: {{ $fmt($summary['expense'], $code) }}
            &nbsp;|&nbsp; ສຸດທິ: {{ ($netBal >= 0 ? '+' : '') . $fmt(abs($netBal), $code) }}
        </div>

        <table class="dtbl">
            <thead>
                <tr>
                    <th style="width:11%;">ວັນທີ</th>
                    <th style="width:11%;" class="c">ປະເພດ</th>
                    <th style="width:17%;">ໝວດໝູ່</th>
                    <th>ລາຍລະອຽດ</th>
                    <th style="width:20%;" class="r">ຈໍານວນ ({{ $cfg['name_lo'] }})</th>
                    <th style="width:12%;">ອ້າງອີງ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($txGroup as $tx)
                <tr>
                    <td>{{ $tx->transaction_date_formatted }}</td>
                    <td class="c">
                        {{ $tx->is_income ? 'ລາຍຮັບ' : 'ລາຍຈ່າຍ' }}
                    </td>
                    <td>{{ $tx->category->name ?? '—' }}</td>
                    <td>{{ $tx->description }}</td>
                    <td class="r" style="font-weight:bold;">
                        {{ $tx->is_income ? '+' : '-' }}{{ number_format((float)$tx->amount, $cfg['decimals'], '.', ',') }}
                    </td>
                    <td>{{ $tx->reference_number ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">
                        ຍອດສຸດທິ {{ $code }} ({{ $txGroup->count() }} ລາຍການ)
                    </td>
                    <td class="r">
                        {{ $netBal >= 0 ? '+' : '' }}{{ number_format(abs($netBal), $cfg['decimals'], '.', ',') }} {{ $cfg['name_lo'] }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    @endforeach

    @if (count($txByCurrency) > 1)
    <p style="font-size:9pt; text-align:center; margin-top:6px; color:#444444;">
        ທັງໝົດ {{ $totalTxCount }} ລາຍການ ໃນ {{ count($txByCurrency) }} ສະກຸນເງີນ — ຈຳນວນເງີນໃນແຕ່ລະສະກຸນເງີນ ບໍ່ສາມາດລວມກັນໄດ້
    </p>
    @endif
@endif

{{-- ══ ພາກສ່ວນລາຍເຊັນ ══ --}}
<div class="sig-section">
    <table class="sig-table">
        <tr>
            <td class="distribution-list"></td>
            <td class="signature-block">
                <div class="sig-role-label">
                    ຜູ້ລາຍງານ / ຫົວໜ້າຄະນະກໍາມະການການເງິນ<br/>
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- ══ ສ່ວນທ້າຍເອກະສານ ══ --}}
<div class="doc-footer">
    ຫ້ອງການ {{ $orgName }}ກັມມາທິການສາທາຣະນູປະການ ສູນກາງອົງການພຸດທະສາສະໜາສຳພັນ ແຫ່ງ ສປປ ລາວ
    ສໍານັກງານ ຕັ້ງຢູ່ທີ່ {{ $orgAddress }}
    @if ($orgPhone) ໂທ: {{ $orgPhone }}@endif
    @if ($orgEmail) ອີເມວ: {{ $orgEmail }}@endif
    @if ($orgWebsite) website: {{ $orgWebsite }}@endif
</div>

</body>
</html>
