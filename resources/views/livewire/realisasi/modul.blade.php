<div>
    <div class="row">
            <div class="box">
                <div class="box-body analytics-info">
                    <div id="basic-pie" style="height:400px;"></div>
                </div>
            </div>
            <div class="box">
                <div class="box-body analytics-info">
                    <div class="row">
                        <div class="col-xl-4">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>REALISASI</th>
                                        <th>Y-O-Y</th>
                                        @if ($dataTW <= 4 )
                                        <th>Q-O-Q</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $skrgPMDN = 0;
                                        $nantiPMDN = 0;
                                        $totPMDN = 0;
                                        $skrgPMA = 0;
                                        $nantiPMA = 0;
                                        $totPMA = 0;
                                        $skrgPMDNPMA = 0;
                                        $nantiPMDNPMA = 0;
                                        $totPMDNPMA = 0;
                                        $qoqPMDN = 0;
                                        $qoqPMA = 0;
                                        $qoqPMDNPMA = 0;
                                        $totalqoqPMDNPMA = 0;
                                    @endphp

                                    @foreach ($now as $item)
                                    {{-- @dd($item->type) --}}
                                        @if ($item->type == 'PMDN')
                                            @php
                                                $skrgPMDN = $item->total_value;
                                            @endphp
                                        @elseif ($item->type == 'PMA')
                                            @php
                                                $skrgPMA = $item->total_value;
                                            @endphp
                                        @else
                                            @php
                                                $skrgPMDNPMA = $item->total_value;
                                            @endphp
                                        @endif
                                    @endforeach

                                    @foreach ($past as $item)
                                        @if ($item->type == 'PMDN')
                                            @php
                                                $nantiPMDN = ($skrgPMDN - $item->total_value) / $item->total_value;
                                            @endphp
                                        @elseif ($item->type == 'PMA')
                                            @php
                                                $nantiPMA = ($skrgPMA - $item->total_value) / $item->total_value;
                                            @endphp
                                        @else
                                            @php
                                                $nantiPMDNPMA = $item->total_value;
                                            @endphp
                                        @endif

                                        @php
                                            $totPMDN = $nantiPMDN * 100;
                                            $totPMA = $nantiPMA * 100;
                                            $totPMDNPMA = ($nantiPMDNPMA != 0) ? (($skrgPMDNPMA - $item->total_value) / $nantiPMDNPMA) * 100 : 0;
                                        @endphp
                                    @endforeach

                                    @foreach ($before as $item)
                                        @if ($item->type == 'PMDN')
                                            @php
                                                $qoqPMDN = (($skrgPMDN - $item->total_value) / $item->total_value) * 100;
                                            @endphp
                                        @elseif ($item->type == 'PMA')
                                            @php
                                                $qoqPMA = (($skrgPMA - $item->total_value) / $item->total_value) * 100;
                                            @endphp
                                        @else
                                            @php
                                                $qoqPMDNPMA = $item->total_value;
                                            @endphp
                                        @endif

                                        @php
                                            $totalqoqPMDNPMA = ($qoqPMDNPMA != 0) ? (($skrgPMDNPMA - $item->total_value) / $qoqPMDNPMA) * 100 : 0;
                                        @endphp
                                    @endforeach

                                    <tr>
                                        <td>PMDN</td>
                                        <td>{{ number_format($totPMDN, 2) }}%</td>
                                        @if ($dataTW <= 4)
                                            <td>{{ number_format($qoqPMDN, 2) }}%</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>PMA</td>
                                        <td>{{ number_format($totPMA, 2) }}%</td>
                                        @if ($dataTW <= 4)
                                            <td>{{ number_format($qoqPMA, 2) }}%</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>TOTAL</td>
                                        <td>{{ number_format($totPMDNPMA, 2) }}%</td>
                                        @if ($dataTW <= 4)
                                            <td>{{ number_format($totalqoqPMDNPMA, 2) }}%</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td></td>
                                            @php
                                                if (number_format($totPMDNPMA, 2) > 0) {
                                                    $panahyoy = "mdi-arrow-up-bold bg-success";
                                                }else {
                                                    $panahyoy = "mdi-arrow-down-bold bg-danger";
                                                }
                                                if (number_format($totalqoqPMDNPMA, 2) > 0) {
                                                    $panahqoq = "mdi-arrow-up-bold bg-success";
                                                }else {
                                                    $panahqoq = "mdi-arrow-down-bold bg-danger";
                                                }
                                            @endphp
                                        <td><h2><i class="mdi {{ $panahyoy }}"></i></h2></td>
                                        @if ($dataTW <= 4)
                                        <td><h2><i class="mdi {{ $panahqoq }}"></i></h2></td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-xl-7">
                            @if($dataTW != '')
                            <ul class="mt-80 font-size-20 text-justify">
                                <li>Nilai investasi dalam Rp Triliun (T) dan kurs pada {{ $titleNow }}
                                    adalah US$ 1 = Rp {{ formatAngkaJenis($kurs) }} sesuai dengan APBN {{ $dataTahun }}.</li>
                                <li>Realisasi investasi Provinsi Riau pada {{ $titleNow }} ({{ formatNominal($skrgPMDNPMA) }})
                                    {{ (number_format($totPMDNPMA, 2) > 0) ? 'meningkat' : 'menurun' }} {{ number_format($totPMDNPMA, 2) }}% dari {{ $titlePast }} ({{ formatNominal($nantiPMDNPMA) }})
                                    @if ($dataTW <= 4 )
                                        @if ($totPMDNPMA > 0 && $totalqoqPMDNPMA > 0)
                                            serta mengalami peningkatan {{ number_format($totalqoqPMDNPMA, 2) }}% dari {{ $titleBefore }} ({{ formatNominal($qoqPMDNPMA) }})
                                        @else
                                            namun mengalami penurunan {{ number_format($totalqoqPMDNPMA, 2) }}% dari {{ $titleBefore }} ({{ formatNominal($qoqPMDNPMA) }})
                                        @endif
                                    @endif
                                </li>
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

@push('css')
    <style>
        table.borderless tbody tr:last-child {
            border-bottom: none;
        }
    </style>
@endpush
@push('js')
<script src="{{asset('assets/backend/vendor_components/echarts/dist/echarts-en.min.js')}}"></script>
{{-- <script src="{{asset('assets/backend/js/pages/echart-pie-doghnut.js')}}"></script> --}}
<script>
	function formatNominal(nominal) {
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
            var now = data.now;
            var past = data.past;
            var before = data.before;
            var basicpieChart = document.getElementById('basic-pie');

            var myChart = echarts.init(basicpieChart, null, {
                renderer: 'canvas',
                useDirtyRect: false
            });
                var option = {         
                        title: [
                            {
                            text: 'Pie label alignTo',
                            left: 'center'
                            },
                            {
                            text: 'alignTo: "none" (default)',
                            left: '16%',
                            top: '75%',
                            textAlign: 'center'
                            },
                            {
                            text: 'alignTo: "labelLine"',
                            left: '50%',
                            top: '75%',
                            textAlign: 'center'
                            },
                            {
                            text: 'alignTo: "edge"',
                            left: '83%',
                            top: '75%',
                            textAlign: 'center'
                            }
                        ],       
                        // Add tooltip
                        tooltip: {
                            trigger: 'item',
                            formatter: "{b}: ({d}%)"
                        },

                        // Add legend
                        legend: {
                            orient: 'vertical',
                            x: 'left',
                            data: []
                        },

                        // Add custom colors
                        color: ["#5083BC",
                                '#C0504F',
                                "#9ABB60",],

                        // Display toolbox
                        toolbox: {
                            show: true,
                            orient: 'vertical',
                            feature: {
                                mark: {
                                    show: true,
                                    title: {
                                        mark: 'Markline switch',
                                        markUndo: 'Undo markline',
                                        markClear: 'Clear markline'
                                    }
                                },
                                dataView: {
                                    show: true,
                                    readOnly: true,
                                    title: 'View data',
                                    lang: ['View chart data', 'Close', 'Update']
                                },
                                magicType: {
                                    show: true,
                                    title: {
                                        pie: 'Switch to pies',
                                        funnel: 'Switch to funnel',
                                    },
                                    type: ['pie', 'funnel'],
                                    option: {
                                        funnel: {
                                            x: '25%',
                                            y: '20%',
                                            width: '50%',
                                            height: '70%',
                                            funnelAlign: 'left',
                                            max: 1548
                                        }
                                    }
                                },
                                restore: {
                                    show: true,
                                    title: 'Restore'
                                },
                                saveAsImage: {
                                    show: true,
                                    title: 'Same as image',
                                    lang: ['Save']
                                }
                            }
                        },
                        // Add series
                        series: [
                            {
                                name: '',
                                type: 'pie',
                                selectedMode: 'single',
                                radius: [0, '20%'],
                                center: ['16%', '50%'],
                                label: {
                                    formatter: function (params) {
                                        return formatNominal(params.value);
                                    },
                                    position: 'center',
                                    fontSize: 14,
                                    color: '#fff',
                                },
                                labelLine: {
                                    show: false
                                },
                                data: [
                                    @json($dataNow)
                                ]
                            },{
                                name: '',
                                radius: ['35%', '25%'],
                                center: ['16%', '50%'],
                                labelLine: {
                                    length: 25
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
                                        fontSize: 14,
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
                                        fontSize: 13,
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
                                type: 'pie',
                                data: @json($dataNow),
                            },{
                                name: '',
                                type: 'pie',
                                selectedMode: 'single',
                                radius: [0, '20%'],
                                center: ['50%', '50%'],
                                label: {
                                    formatter: function (params) {
                                        return formatNominal(params.value);
                                    },
                                    position: 'center',
                                    fontSize: 14,
                                    color: '#fff',
                                },
                                labelLine: {
                                    show: false
                                },
                                data: [
                                    @json($dataPast)
                                ]
                            },{
                                name: '',
                                radius: ['35%', '25%'],
                                center: ['50%', '50%'],
                                labelLine: {
                                    length: 25
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
                                        fontSize: 14,
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
                                        fontSize: 13,
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
                                type: 'pie',
                                data: @json($dataPast),
                            },{
                                name: '',
                                type: 'pie',
                                selectedMode: 'single',
                                radius: [0, '20%'],
                                center: ['83%', '50%'],
                                label: {
                                    formatter: function (params) {
                                        return formatNominal(params.value);
                                    },
                                    position: 'center',
                                    fontSize: 14,
                                    color: '#fff',
                                },
                                labelLine: {
                                    show: false
                                },
                                data: [
                                    @json($dataBefore)
                                ]
                            },{
                                name: '',
                                radius: ['35%', '25%'],
                                center: ['83%', '50%'],
                                labelLine: {
                                    length: 25
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
                                        fontSize: 14,
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
                                        fontSize: 13,
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
                                type: 'pie',
                                data: @json($dataBefore),
                            }
                        ]
                    };
                    
                    option.title[0].text = 'Realisasi ' +data.titleNow;
                    option.title[1].text = data.titleNow;
                    option.series[0].data = [now[2]];
                    option.series[1].data = [now[0],now[1]];
                    option.title[2].text = data.titlePast;
                    option.series[2].data = [past[2]];
                    option.series[3].data = [past[0],past[1]];
                    option.title[3].text = data.titleBefore;
                    option.series[4].data = [before[2]];
                    option.series[5].data = [before[0],before[1]];
                    console.log(data)
                    // console.log(now)
                    // console.log(past)
                    // console.log(before)
                    // myChart.setOption(option, true);
                    if (option && typeof option === 'object') {
                        myChart.setOption(option, true);
                    }

                    window.addEventListener('resize', myChart.resize);
            });
        });

	

</script>
@endpush