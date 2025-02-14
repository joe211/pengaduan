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
                        <div class="text-center mb-3">
                       
                        @if($triwulan)
                                <button wire:click="ExcelSektor" class="btn btn-sm btn-primary">Export to Excel</button>
                        @endif
                        </div>
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
                        </div>                      
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="box">
                    <div class="box-body analytics-info">
                        
                        <div class="box-body analytics-info">
                            <div id="basic-pie" style="height:400px;"></div>
                        </div>
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

@push('js')
<script src="{{asset('assets/backend/vendor_components/echarts/dist/echarts-en.min.js')}}"></script>
{{-- <script src="{{asset('assets/backend/js/pages/echart-pie-doghnut.js')}}"></script> --}}
<script>
	document.addEventListener('livewire:load', function () {
        Livewire.on('updateChart', function (data) {
        var basicpieChart = echarts.init(document.getElementById('basic-pie'));
        // var chartData = @json($dataset);
        var option = {
                title: {
                    text: '',
                    x: 'center'
                },
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
                        "#9ABB60",
                        "#80649F",
                        "#4AACC7",
                        "#F6964A"],

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
                series: [{
                    name: @json($title),
                    label: {
                        formatter: function (params) {
                            return params.name + '\n' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(params.value) + '\n' + params.percent + '%';
                        }
                    },
                    type: 'pie',
                    radius: '70%',
                    center: ['50%', '50%'],
                    data: @json($dataset),
                }]
            };
            // console.log(option.series[0].data)
        
       
            option.series[0].name = data.judul;
            option.series[0].data = data.datasets;
            option.title.text = data.judul;
            console.log(@json($dataset))
            basicpieChart.setOption(option, true);
            // console.log(option.series[0].name)
            // basicpieChart.resize();
        });

        // basicpieChart.setOption(option);
    });
	document.addEventListener('livewire:load', function () {
    
    var basicpieChart1 = echarts.init(document.getElementById('basic-pie'));
        // var chartData = @json($dataset);
        var option1 = {         
                title: {
                    text: @json($title),
                    x: 'center'
                },       
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
                        "#9ABB60",
                        "#80649F",
                        "#4AACC7",
                        "#F6964A"],

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
                series: [{
                    name: @json($title),
                    label: {
                        formatter: function (params) {
                            return params.name + '\n' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(params.value) + '\n' + params.percent + '%';
                        }
                    },
                    type: 'pie',
                    radius: '70%',
                    center: ['50%', '57.5%'],
                    data: @json($dataset),
                }]
            };
            // console.log(option.series[0].data)
        
       
         
            // console.log(option1.series[0].name)
            // basicpieChart.resize();

        basicpieChart1.setOption(option1);
    });

</script>
@endpush
