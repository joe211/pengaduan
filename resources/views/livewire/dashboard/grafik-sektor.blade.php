<div>
    <div class="box">
        <div class="box-body analytics-info">
            {{-- <h4 class="box-title">PMDN/PMA PER LOKASI</h4> --}}
            <div class="box-body">
                <div id="basic-pie-sektor" style="height:400px;"></div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    #basic-pie-sektor {
        position: relative;
        height: 100vh;
        overflow: hidden;
        }
</style>
@endpush
@push('js')
{{-- <script src="{{asset('assets/backend/vendor_components/echarts/dist/echarts-en.min.js')}}"></script>
<script src="{{asset('assets/backend/js/pages/echart-pie-doghnut.js')}}"></script> --}}
<script>
    
    var basicpieChart = document.getElementById('basic-pie-sektor');
    var myChart = echarts.init(basicpieChart, null, {
        renderer: 'canvas',
        useDirtyRect: false
    });
    var colorPalette = ["#5083BC", "#C0504F", "#9ABB60", "#80649F", "#4AACC7", "#F6964A"];
        var option = {         
                title: [{
                    text: 'REALISASI INVESTASI PER SEKTOR',
                    x: 'center'
                    },       
                    {
                    text: 'PMDN',
                    left: '15%',
                    top: '90%',
                    textAlign: 'center'
                    },
                    {
                    text: 'PMA',
                    left: '50%',
                    top: '90%',
                    textAlign: 'center'
                    },
                    {
                    text: 'PMDN + PMA',
                    left: '85%',
                    top: '90%',
                    textAlign: 'center'
                    }
                ],
                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{b}: ({d}%)"
                },
                
             
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
                        }
                    }
                },

                // Add series
                series: [
                    {
                    label: {
                        formatter: function (params) {
                            return params.name + '\n' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(params.value) + '\n' + params.percent + '%';
                        }
                    },
                    type: 'pie',
                    radius: '50%',
                    center: ['15%', '50%'],
                    data: @json($datapmdn),
                    },
                    {
                    label: {
                        formatter: function (params) {
                            return params.name + '\n' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(params.value) + '\n' + params.percent + '%';
                        }
                    },
                    type: 'pie',
                    radius: '50%',
                    center: ['50%', '50%'],
                    data: @json($datapma),
                    },
                    {
                    label: {
                        formatter: function (params) {
                            return params.name + '\n' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(params.value) + '\n' + params.percent + '%';
                        }
                    },
                    type: 'pie',
                    radius: '50%',
                    center: ['85%', '50%'],
                    data: @json($datapmapmdn),
                    }
                ]
            };
            option.series.forEach(function(seriesItem, index) {
            seriesItem.data.forEach(function(dataItem, dataIndex) {
                dataItem.itemStyle = {
                    color: colorPalette[dataIndex % colorPalette.length]
                };
            });
        });
        // basicpieChart.setOption(option1);
        if (option && typeof option === 'object') {
            myChart.setOption(option);
        }

        window.addEventListener('resize', myChart.resize);

</script>
@endpush