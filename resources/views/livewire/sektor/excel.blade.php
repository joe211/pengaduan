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
                        
        <table id="tabelsektor" border="1" class="table">
                            
            <thead class="text-center">
                <tr>
                    <th style="background-color: #FFA500" rowspan="3">No</th>
                    <th style="background-color: #FFA500" rowspan="3">Sektor</th>
                    <th style="background-color: #FFA500" rowspan="3">Proyek</th>
                    <th style="background-color: #FFA500" colspan="4">PMDN</th>
                    <th style="background-color: #FFA500" rowspan="3">Proyek</th>
                    <th style="background-color: #FFA500" colspan="2">PMA</th>
                    <th style="background-color: #FFA500" rowspan="3">TKI</th>
                    <th style="background-color: #FFA500" rowspan="3">TKA</th>
                    <th style="background-color: #FFA500" colspan="4">PMA/PMDN</th>
                </tr>
                <tr>
                    <th style="background-color: #FFA500" colspan="2">Investasi</th>
                    <th style="background-color: #FFA500" rowspan="2">TKI</th>
                    <th style="background-color: #FFA500" rowspan="2">TKA</th>
                    <th style="background-color: #FFA500" colspan="2">Investasi</th>
                    <th style="background-color: #FFA500" rowspan="2">Proyek</th>
                    <th style="background-color: #FFA500" rowspan="2">Investasi (Dalam Rp.)</th>
                    <th style="background-color: #FFA500" rowspan="2">TKI</th>
                    <th style="background-color: #FFA500" rowspan="2">TKA</th>
                </tr>
                <tr>
                    <th style="background-color: #FFA500">(Dalam Juta Rp.)</th>
                    <th style="background-color: #FFA500">(Dalam Rp.)</th>
                    <th style="background-color: #FFA500">(Dalam Ribu US $)</th>
                    <th style="background-color: #FFA500">(Dalam Rp.)</th>
                </tr>
            </thead>
            <tbody>

                @php 
                    $no=1; 
                    $totproyekPMDN = 0;
                    $totinvesJtPMDN = 0;
                    $totinvesPMDN = 0;
                    $tottkiPMDN = 0;
                    $tottkaPMDN = 0;

                    $totproyekPMA = 0;
                    $totinvesJtPMA = 0;
                    $totinvesPMA = 0;
                    $tottkiPMA = 0;
                    $tottkaPMA = 0;
                    
                    $totproyekPMDNPMA = 0;
                    $totinvesPMDNPMA = 0;
                    $tottkiPMDNPMA = 0;
                    $tottkaPMDNPMA = 0;
                      
                @endphp
                {{-- @dd($data); --}}
                @foreach ($data as $row)
                @php
                // @dd($row);
                    $idsektor = explode(", ", $row->idsektor);
                    $namaSektor = explode("; ", $row->namasektor);
                    $proyekPMDN = explode(", ", $row->jumlah_proyek_pmdn);
                    $invesJtPMDN = explode(", ", $row->tambahan_investasi_juta_pmdn);
                    $invesPMDN = explode(", ", $row->tambahan_investasi_pmdn);
                    $tkiPMDN = explode(", ", $row->jumlah_tki_pmdn);
                    $tkaPMDN = explode(", ", $row->jumlah_tka_pmdn);

                    $proyekPMA = explode(", ", $row->jumlah_proyek_pma);
                    $invesJtPMA = explode(", ", $row->tambahan_investasi_juta_pma);
                    $invesPMA = explode(", ", $row->tambahan_investasi_pma);
                    $tkiPMA = explode(", ", $row->jumlah_tki_pma);
                    $tkaPMA = explode(", ", $row->jumlah_tka_pma);
                    
                    $proyekPMDNPMA = explode(", ", $row->jumlah_proyek_pmdn_pma);
                    $invesPMDNPMA = explode(", ", $row->jumlah_investasi_pmdn_pma);
                    $tkiPMDNPMA = explode(", ", $row->jumlah_tki_pmdn_pma);
                    $tkaPMDNPMA = explode(", ", $row->jumlah_tka_pmdn_pma);

                    $jumlahSektor = count($namaSektor);
                    @endphp
                    <tr class="fw-bold">
                        <td></td>
                        <td colspan="15"><span class="text-bold"><u>{{ $row->sektorutama }}</u></span></td>
                    </tr>
                    {{-- @foreach ($row as $item) --}}
                    
                    @for ($i = 0; $i < $jumlahSektor; $i++)
                        
                    <tr>
                        <td class="text-center">{{ $no++; }}</td>
                        <td>{{ $namaSektor[$i] }}</td>
                        {{-- PMDN --}}
                        <td class="text-center">{{ formatAngka($proyekPMDN[$i]) }}</td>
                        <td class="text-right">{{ formatAngkaCurrency($invesJtPMDN[$i]) }}</td>
                        <td class="text-right">{{ formatAngka($invesPMDN[$i]) }}</td>
                        <td class="text-center">{{ formatAngka($tkiPMDN[$i]) }}</td>
                        <td class="text-center">{{ formatAngka($tkaPMDN[$i]) }}</td>
                        {{-- PMA --}}
                        <td class="text-center">{{ formatAngka($proyekPMA[$i]) }}</td>
                        <td class="text-right">{{ formatAngkaCurrency($invesJtPMA[$i]) }}</td>
                        <td class="text-right">{{ formatAngka($invesPMA[$i]) }}</td>
                        <td class="text-center">{{ formatAngka($tkiPMA[$i]) }}</td>
                        <td class="text-center">{{ formatAngka($tkaPMA[$i]) }}</td>
                        {{-- PMDNPMA --}}
                        <td class="text-center">{{ formatAngka($proyekPMDNPMA[$i]) }}</td>
                        <td class="text-right">{{ formatAngka($invesPMDNPMA[$i]) }}</td>
                        <td class="text-center">{{ formatAngka($tkiPMDNPMA[$i]) }}</td>
                        <td class="text-center">{{ formatAngka($tkaPMDNPMA[$i]) }}</td>
                    </tr>
                    @php
                        $jumproyekPMDN = array_sum($proyekPMDN);
                        $juminvesJtPMDN = array_sum($invesJtPMDN);
                        $juminvesPMDN = array_sum($invesPMDN);
                        $jumtkiPMDN = array_sum($tkiPMDN);
                        $jumtkaPMDN = array_sum($tkaPMDN);

                        $jumproyekPMA = array_sum($proyekPMA);
                        $juminvesJtPMA = array_sum($invesJtPMA);
                        $juminvesPMA = array_sum($invesPMA);
                        $jumtkiPMA = array_sum($tkiPMA);
                        $jumtkaPMA = array_sum($tkaPMA);
                        
                        $jumproyekPMDNPMA = array_sum($proyekPMDNPMA);
                        $juminvesPMDNPMA = array_sum($invesPMDNPMA);
                        $jumtkiPMDNPMA = array_sum($tkiPMDNPMA);
                        $jumtkaPMDNPMA = array_sum($tkaPMDNPMA);

                        $totproyekPMDN += $proyekPMDN[$i];
                        $totinvesJtPMDN += $invesJtPMDN[$i];
                        $totinvesPMDN += $invesPMDN[$i];
                        $tottkiPMDN += $tkiPMDN[$i];
                        $tottkaPMDN += $tkaPMDN[$i];
                        
                        $totproyekPMA += $proyekPMA[$i];
                        $totinvesJtPMA += $invesJtPMA[$i];
                        $totinvesPMA += $invesPMA[$i];
                        $tottkiPMA += $tkiPMA[$i];
                        $tottkaPMA += $tkaPMA[$i];
                        
                        $totproyekPMDNPMA += $proyekPMDNPMA[$i];
                        $totinvesPMDNPMA += $invesPMDNPMA[$i];
                        $tottkiPMDNPMA += $tkiPMDNPMA[$i];
                        $tottkaPMDNPMA += $tkaPMDNPMA[$i];
                    @endphp
                    @endfor
                    <tr class="text-bold">
                        <td colspan="2" class="text-center">Jumlah</td>
                        <td class="text-center">{{ formatAngka($jumproyekPMDN) }}</td>
                        <td class="text-center">{{ formatAngkaCurrency($juminvesJtPMDN) }}</td>
                        <td class="text-center">{{ formatAngka($juminvesPMDN) }}</td>
                        <td class="text-center">{{ formatAngka($jumtkiPMDN) }}</td>
                        <td class="text-center">{{ formatAngka($jumtkaPMDN) }}</td>
                        
                        <td class="text-center">{{ formatAngka($jumproyekPMA) }}</td>
                        <td class="text-center">{{ formatAngkaCurrency($juminvesJtPMA) }}</td>
                        <td class="text-center">{{ formatAngka($juminvesPMA) }}</td>
                        <td class="text-center">{{ formatAngka($jumtkiPMA) }}</td>
                        <td class="text-center">{{ formatAngka($jumtkaPMA) }}</td>
                        
                        <td class="text-center">{{ formatAngka($jumproyekPMDNPMA) }}</td>
                        <td class="text-center">{{ formatAngka($juminvesPMDNPMA) }}</td>
                        <td class="text-center">{{ formatAngka($jumtkiPMDNPMA) }}</td>
                        <td class="text-center">{{ formatAngka($jumtkaPMDNPMA) }}</td>
                    </tr>
                    <tr>
                        <td colspan="16"></td>
                    </tr>
                       
                @endforeach
               
                
            </tbody>
            <tfoot class="text-center">
                <tr class="text-bold">
                    <td colspan="2" class="text-center">TOTAL</td>
                    <td class="text-center">{{ formatAngka($totproyekPMDN) }}</td>
                    <td class="text-center">{{ formatAngkaCurrency($totinvesJtPMDN) }}</td>
                    <td class="text-center">{{ formatAngka($totinvesPMDN) }}</td>
                    <td class="text-center">{{ formatAngka($tottkiPMDN) }}</td>
                    <td class="text-center">{{ formatAngka($tottkaPMDN) }}</td>
                    
                    <td class="text-center">{{ formatAngka($totproyekPMA) }}</td>
                    <td class="text-center">{{ formatAngkaCurrency($totinvesJtPMA) }}</td>
                    <td class="text-center">{{ formatAngka($totinvesPMA) }}</td>
                    <td class="text-center">{{ formatAngka($tottkiPMA) }}</td>
                    <td class="text-center">{{ formatAngka($tottkaPMA) }}</td>
                    
                    <td class="text-center">{{ formatAngka($totproyekPMDNPMA) }}</td>
                    <td class="text-center">{{ formatAngka($totinvesPMDNPMA) }}</td>
                    <td class="text-center">{{ formatAngka($tottkiPMDNPMA) }}</td>
                    <td class="text-center">{{ formatAngka($tottkaPMDNPMA) }}</td>
                </tr>
            </tfoot>
        </table>
</body>
</html>