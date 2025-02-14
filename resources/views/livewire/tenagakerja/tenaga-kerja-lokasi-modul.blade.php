<div>
    <div wire:loading.remove>
        {{-- Data Tahun {{ $tahun ?? date('Y') }} --}}
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-body analytics-info">
                    <div class="text-center">
                           @if ($triwulan)
                            <a href="route('export', [$tahun, $triwulan] )" class="btn btn-sm btn-primary">Export to Excel</a>
                            @endif
                            
                    <h3>Penyerapan Tenaga Kerja {{ $header }} Tahun {{$tahun ?? date('Y') }} berdasarkan Lokasi</h3>
                    </div>
                        <table class="table table-bordered b-1 border-primary ">
                            <thead class="text-center bg-primary">
                                <tr>
                                    <th rowspan="3" class="align-middle">No</th>
                                    <th rowspan="3" class="align-middle">Kabupaten / Kota</th>
                                    <th colspan="9">TENAGA KERJA</th>
                                </tr>
                                <tr>
                                <th colspan="3">PMDN</th>
                                <th colspan="3">PMA</th>
                                <th colspan="3">PMDN/PMA</th>
                                </tr>
                                <tr>
                                    <th>TKI</th>
                                    <th>TKA</th>
                                    <th>TKI/TKA</th>
                                    <th>TKI</th>
                                    <th>TKA</th>
                                    <th>TKI/TKA</th>
                                    <th>TKI</th>
                                    <th>TKA</th>
                                    <th>TKI/TKA</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php 
                                $no=1; 
                                $arr=0;
                                @endphp
                                @foreach ($data as $items)
                                <tr class="odd">
                                    <td class="text-center">{{ $no++; }}</td>
                                    <td>{{ $items->nama_kota }}</td>
                                    <td class="text-center">{{ isset($items->jumlah_tki_pmdn) ? ($items->jumlah_tki_pmdn) : '0' }}</td>
                                    <td class="text-center">{{ isset($items->jumlah_tka_pmdn) ? ($items->jumlah_tka_pmdn) : '0' }} </td>
                                    <td class="text-center bg-primary">{{ 
                                        (isset($items->jumlah_tki_pmdn) ? ($items->jumlah_tki_pmdn) : '0') +
                                        (isset($items->jumlah_tka_pmdn) ? ($items->jumlah_tka_pmdn) : '0')
                                    }}
                                    </td>
                                    <td class="text-center">{{ isset($items->jumlah_tki_pma) ? ($items->jumlah_tki_pma) : '0' }}</td>
                                    <td class="text-center">{{ isset($items->jumlah_tka_pma) ? ($items->jumlah_tka_pma) : '0' }} </td>
                                    <td class="text-center bg-primary">{{ 
                                        (isset($items->jumlah_tki_pma) ? ($items->jumlah_tki_pma) : '0') +
                                        (isset($items->jumlah_tka_pma) ? ($items->jumlah_tka_pma) : '0')
                                    }}</td>
                                    <td class="text-center">{{ (isset($items->jumlah_tki_pma) ? ($items->jumlah_tki_pma) : '0') + (isset($items->jumlah_tki_pmdn) ? ($items->jumlah_tki_pmdn) : '0') }}</td>
                                    <td class="text-center">{{ (isset($items->jumlah_tka_pma) ? ($items->jumlah_tka_pma) : '0') + (isset($items->jumlah_tka_pmdn) ? ($items->jumlah_tka_pmdn) : '0') }}</td>
                                    <td class="text-center bg-primary">{{ 
                                        ((isset($items->jumlah_tki_pma) ? ($items->jumlah_tki_pma) : '0') + (isset($items->jumlah_tki_pmdn) ? ($items->jumlah_tki_pmdn) : '0')) +
                                        ((isset($items->jumlah_tka_pma) ? ($items->jumlah_tka_pma) : '0') + (isset($items->jumlah_tka_pmdn) ? ($items->jumlah_tka_pmdn) : '0'))
                                    }}</td>
                                </tr>
                                @php
                                    $subTotalJumTki +=  isset($items->jumlah_tki_pmdn) ? ($items->jumlah_tki_pmdn) : '0';
                                    $subTotalJumTka +=  isset($items->jumlah_tka_pmdn) ? ($items->jumlah_tka_pmdn) : '0' ;
                                    $subTotalJumTkiTka +=  (isset($items->jumlah_tki_pmdn) ? ($items->jumlah_tki_pmdn) : '0') +
                                                            (isset($items->jumlah_tka_pmdn) ? ($items->jumlah_tka_pmdn) : '0') ;
                                    $subTotalJumTkipma +=   isset($items->jumlah_tki_pma) ? ($items->jumlah_tki_pma) : '0' ;
                                    $subTotalJumTkapma +=  isset($items->jumlah_tka_pma) ? ($items->jumlah_tka_pma) : '0' ;
                                    $subTotalJumTkiTkapma +=  (isset($items->jumlah_tki_pma) ? ($items->jumlah_tki_pma) : '0') +
                                                            (isset($items->jumlah_tka_pma) ? ($items->jumlah_tka_pma) : '0') ; 
                                    $subTotalJumTkipmapmdn +=   (isset($items->jumlah_tki_pma) ? ($items->jumlah_tki_pma) : '0') + (isset($items->jumlah_tki_pmdn) ? ($items->jumlah_tki_pmdn) : '0') ;
                                    $subTotalJumTkapmapmdn +=  (isset($items->jumlah_tka_pma) ? ($items->jumlah_tka_pma) : '0') + (isset($items->jumlah_tka_pmdn) ? ($items->jumlah_tka_pmdn) : '0') ;
                                    $subTotalJumTkiTkapmapmdn +=   ((isset($items->jumlah_tki_pma) ? ($items->jumlah_tki_pma) : '0') + (isset($items->jumlah_tki_pmdn) ? ($items->jumlah_tki_pmdn) : '0')) +
                                        ((isset($items->jumlah_tka_pma) ? ($items->jumlah_tka_pma) : '0') + (isset($items->jumlah_tka_pmdn) ? ($items->jumlah_tka_pmdn) : '0')) ; 
                                    $arr++;
                                @endphp
                                @endforeach
                            </tbody>
                            <tfoot class="text-center bg-primary">
                                <tr>
                                    <td colspan="2">JUMLAH</td>
                                    <td>{{ number_format($subTotalJumTki, 0, '.', '.')  }}</td>
                                    <td>{{ number_format($subTotalJumTka, 0, '.', '.')  }}</td>
                                    <td>{{ number_format($subTotalJumTkiTka, 0, '.', '.')  }}</td>
                                    <td>{{ number_format($subTotalJumTkipma, 0, '.', '.')  }}</td>
                                    <td>{{ number_format($subTotalJumTkapma, 0, '.', '.')  }}</td>
                                    <td>{{ number_format($subTotalJumTkiTkapma, 0, '.', '.')  }}</td>
                                    <td>{{ number_format($subTotalJumTkipmapmdn, 0, '.', '.')  }}</td>
                                    <td>{{ number_format($subTotalJumTkapmapmdn, 0, '.', '.')  }}</td>
                                    <td>{{ number_format($subTotalJumTkiTkapmapmdn, 0, '.', '.')  }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-10">
        <div wire:loading class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
            <span class="visually-hidden"></span>
        </div>
    </div>
</div>




