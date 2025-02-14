<?php
$dateDay= date("d-m-Y H:i:s");
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=".$dateDay."-laporan-sektor.xls");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .table > thead > tr > th {
            vertical-align: middle;
        }
        .text-bold{
            font-weight: 700;
        }
        .text-center {
            text-align: center !important;
        }
        .text-right {
            text-align: right !important;
        }
    </style>
</head>
<body>
    <h2>Realisasi {{ $header }} {{ $tahun ?? date('Y') }} berdasarkan SEKTOR</h2>
    <div class="table-responsive-sm">
                        
    <table id="sektor" class="table table-bordered">
                                <thead class="text-center">
                                    <tr>
                                        <th rowspan="2" class="align-middle">No</th>
                                        <th rowspan="2" class="align-middle">Bidang Usaha</th>
                                        <th rowspan="2" class="align-middle">Proyek</th>
                                        <th colspan="3">{{ $selectedValue ?: "PMDN/PMA" }}</th>
                                    </tr>
                                    <tr>
                                        <th>Investasi (Dlm Rp)</th>
                                        <th>TKI</th>
                                        <th>TKA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no=1; @endphp
                                    @foreach ($PmdnClass as $items)
                                    {{-- @dd($items) --}}
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $items->namasektor }}</td>
                                        <td class="text-center">{{ number_format($items->jumlah_proyek, 0, '.', '.') }}</td>
                                        <td class="text-right">{{ number_format($items->tambahan_investasi, 0, '.', '.') }}</td>
                                        <td class="text-center">{{ number_format($items->jumlah_tki, 0, '.', '.') }}</td>
                                        <td class="text-center">{{ number_format($items->jumlah_tka, 0, '.', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="text-center">
                                    <tr>
                                        <td colspan="2">JUMLAH</td>
                                        <td>{{ number_format($subTotalJumProyek, 0, '.', '.') }}</td>
                                        <td>{{ number_format($subTotalJumInvestasi, 0, '.', '.') }}</td>
                                        <td>{{ number_format($subTotalJumTki, 0, '.', '.') }}</td>
                                        <td>{{ number_format($subTotalJumTka, 0, '.', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
</body>
</html>