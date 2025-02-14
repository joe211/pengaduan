<div>
    
    <div wire:loading.remove>
        {{-- Data Tahun {{ $tahun ?? date('Y') }} --}}
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-body analytics-info">  
                        <div class="text-center">
                            <h3>Realisasi {{ $header }} {{ $tahun ?? date('Y') }} berdasarkan SEKTOR</h3>
                            
                        </div>
                        <div class="box">
                            <div class="box-body analytics-info">
                                <div id="basic-pie" style="height:380px;"></div>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            @if($triwulan)
                            <a href="{{ route('export', [$tahun, $triwulan] )}}" class="btn btn-sm btn-primary">Export to Excel</a>
                            @endif
                        </div>
                        <div class="table-responsive-sm">
                        
                            <table id="tabelsektor" class="table table-bordered">
                            
                                <thead class="text-center">
                                    <tr bgcolor="orange">
                                        <th rowspan="3">No</th>
                                        <th rowspan="3">Sektor</th>
                                        <th rowspan="3">Proyek</th>
                                        <th colspan="4">PMDN</th>
                                        <th rowspan="3">Proyek</th>
                                        <th colspan="2">PMA</th>
                                        <th rowspan="3">TKI</th>
                                        <th rowspan="3">TKA</th>
                                        <th colspan="4">PMA/PMDN</th>
                                    </tr>
                                    <tr bgcolor="orange">
                                        <th colspan="2">Investasi</th>
                                        <th rowspan="2">TKI</th>
                                        <th rowspan="2">TKA</th>
                                        <th colspan="2">Investasi</th>
                                        <th rowspan="2">Proyek</th>
                                        <th rowspan="2">Investasi (Dalam Rp.)</th>
                                        <th rowspan="2">TKI</th>
                                        <th rowspan="2">TKA</th>
                                    </tr>
                                    <tr bgcolor="orange">
                                        <th>(Dalam Juta Rp.)</th>
                                        <th>(Dalam Rp.)</th>
                                        <th>(Dalam Ribu US $)</th>
                                        <th>(Dalam Rp.)</th>
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
                                        <td colspan="2">TOTAL</td>
                                        <td>{{ formatAngka($totproyekPMDN) }}</td>
                                        <td>{{ formatAngkaCurrency($totinvesJtPMDN) }}</td>
                                        <td>{{ formatAngka($totinvesPMDN) }}</td>
                                        <td>{{ formatAngka($tottkiPMDN) }}</td>
                                        <td>{{ formatAngka($tottkaPMDN) }}</td>
                                        
                                        <td>{{ formatAngka($totproyekPMA) }}</td>
                                        <td>{{ formatAngkaCurrency($totinvesJtPMA) }}</td>
                                        <td>{{ formatAngka($totinvesPMA) }}</td>
                                        <td>{{ formatAngka($tottkiPMA) }}</td>
                                        <td>{{ formatAngka($tottkaPMA) }}</td>
                                        
                                        <td>{{ formatAngka($totproyekPMDNPMA) }}</td>
                                        <td>{{ formatAngka($totinvesPMDNPMA) }}</td>
                                        <td>{{ formatAngka($tottkiPMDNPMA) }}</td>
                                        <td>{{ formatAngka($tottkaPMDNPMA) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-10">
                        <div wire:loading class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                            <span class="visually-hidden"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="{{asset('assets/backend/vendor_components/echarts/dist/echarts-en.min.js')}}"></script>
{{-- <script src="{{asset('assets/backend/js/pages/echart-pie-doghnut.js')}}"></script> --}}
<script>
	function formatNominal(nominal) {
        if (nominal == 0) {
            return hasilFormatted = "Rp 0";
        }
        // Daftar pembilang untuk setiap satuan
        var satuanPembilang = ["", "K", "M", "B", "T"];

        // Hitung jumlah digit dalam nominal
        var digitCount = Math.floor(Math.log10(nominal)) + 1;

        // Tentukan pembilang yang sesuai
        var pembilangIndex = Math.floor((digitCount - 1) / 3);

        // Hitung nilai dengan pembilang
        var nilaiFormatted = nominal / Math.pow(10, pembilangIndex * 3);

        // Ambil satu digit desimal tanpa pembulatan
        nilaiFormatted = Math.floor(nilaiFormatted * 10) / 10;

        // Gabungkan nilai dan pembilang
        var hasilFormatted = "Rp " + nilaiFormatted + " " + satuanPembilang[pembilangIndex];

        return hasilFormatted;
    
    }
	document.addEventListener('livewire:load', function () {
        Livewire.on('updateChart', function (data) {
        var basicpieChart = document.getElementById('basic-pie');
        var myChart = echarts.init(basicpieChart, null, {
            renderer: 'canvas',
            useDirtyRect: false
        });
        var option;

        option = {
        title: [
            {
            subtext: 'PMDN',
            left: '15%',
            top: '70%',
            textAlign: 'center'
            },
            {
            subtext: 'PMA',
            left: '50%',
            top: '70%',
            textAlign: 'center'
            },
            {
            subtext: 'PMDN + PMA',
            left: '85%',
            top: '70%',
            textAlign: 'center'
            }
        ],
        toolbox: {
            show: true,
            orient: 'vertical',
            feature: {
                saveAsImage: {
                    show: true,
                    title: 'Download',
                    lang: ['Save']
                }
            }
        },
        series: [
            {
            type: 'pie',
            radius: '30%',
            center: ['15%', '45%'],
            data: @json($chartPMDN),
            labelLine: {
                length: 20
            },
            label: {
                formatter: function (params) {
                    return '{a|' + params.name + '}{abg|}\n{hr|}\n  {b|' + formatNominal(params.value) + '}  {per|' + params.percent + '%}  ';
                },
                // formatter: '{a|{b}}{abg|}\n{hr|}\n  {b|{c}：}{per|{d}%}  ',
                backgroundColor: '#F6F8FC',
                borderColor: '#8C8D8E',
                borderWidth: 1,
                borderRadius: 4,
                rich: {
                a: {
                    color: '#6E7079',
                    lineHeight: 22,
                    fontSize: 11,
                    align: 'center',
                    fontWeight: 'bold'
                },
                hr: {
                    borderColor: '#8C8D8E',
                    width: '100%',
                    borderWidth: 1,
                    height: 0
                },
                b: {
                    color: '#4C5058',
                    fontSize: 11,
                    fontWeight: 'bold',
                    lineHeight: 33
                },
                per: {
                    color: '#fff',
                    backgroundColor: '#4C5058',
                    padding: [3, 4],
                    borderRadius: 4
                }
                }
            },
            },
            {
            type: 'pie',
            radius: '30%',
            center: ['50%', '45%'],
            data: @json($chartPMA),
            labelLine: {
                length: 20
            },
            label: {
                formatter: function (params) {
                    return '{a|' + params.name + '}{abg|}\n{hr|}\n  {b|' + formatNominal(params.value) + '}  {per|' + params.percent + '%}  ';
                },
                // formatter: '{a|{b}}{abg|}\n{hr|}\n  {b|{c}：}{per|{d}%}  ',
                backgroundColor: '#F6F8FC',
                borderColor: '#8C8D8E',
                borderWidth: 1,
                borderRadius: 4,
                rich: {
                a: {
                    color: '#6E7079',
                    lineHeight: 22,
                    fontSize: 11,
                    align: 'center',
                    fontWeight: 'bold'
                },
                hr: {
                    borderColor: '#8C8D8E',
                    width: '100%',
                    borderWidth: 1,
                    height: 0
                },
                b: {
                    color: '#4C5058',
                    fontSize: 11,
                    fontWeight: 'bold',
                    lineHeight: 33
                },
                per: {
                    color: '#fff',
                    backgroundColor: '#4C5058',
                    padding: [3, 4],
                    borderRadius: 4
                }
                }
            },
            },
            {
            type: 'pie',
            radius: '30%',
            center: ['85%', '45%'],
            data: @json($chartPMAPMDN),
            labelLine: {
                length: 20
            },
            label: {
                formatter: function (params) {
                    return '{a|' + params.name + '}{abg|}\n{hr|}\n  {b|' + formatNominal(params.value) + '}  {per|' + params.percent + '%}  ';
                },
                // formatter: '{a|{b}}{abg|}\n{hr|}\n  {b|{c}：}{per|{d}%}  ',
                backgroundColor: '#F6F8FC',
                borderColor: '#8C8D8E',
                borderWidth: 1,
                borderRadius: 4,
                rich: {
                a: {
                    color: '#6E7079',
                    lineHeight: 22,
                    fontSize: 11,
                    align: 'center',
                    fontWeight: 'bold'
                },
                hr: {
                    borderColor: '#8C8D8E',
                    width: '100%',
                    borderWidth: 1,
                    height: 0
                },
                b: {
                    color: '#4C5058',
                    fontSize: 11,
                    fontWeight: 'bold',
                    lineHeight: 33
                },
                per: {
                    color: '#fff',
                    backgroundColor: '#4C5058',
                    padding: [3, 4],
                    borderRadius: 4
                }
                }
            },
            }
        ]
        };

        option.series[0].data = data.chartPMDN;
        option.series[1].data = data.chartPMA;
        option.series[2].data = data.chartPMAPMDN;
        if (option && typeof option === 'object') {
            myChart.setOption(option, true);
        }

        window.addEventListener('resize', myChart.resize);
    });

    });

</script>
@endpush
