<div>
    <div class="box">
        <div class="box-body analytics-info">
            {{-- <h4 class="box-title">PMDN/PMA PER LOKASI</h4> --}}
            <div class="box-body">
                <div id="basic-pie-negara-asal" style="height:400px;"></div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    #basic-pie-negara-asal {
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
    
    var basicpieChart = document.getElementById('basic-pie-negara-asal');
    var myChart = echarts.init(basicpieChart, null, {
        renderer: 'canvas',
        useDirtyRect: false
    });
        var option = {         
                title: [{
                    text: 'REALISASI INVESTASI PER NEGARA ASAL',
                    x: 'center'
                    },
                    {
                    text: 'PMA',
                    left: '50%',
                    top: '90%',
                    textAlign: 'center'
                    }
                ],
                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{b}: ({d}%)"
                },
                color: ["#5083BC",
                        "#c0504f",
                        "#9ABB60",
                        "#80649F",
                        "#4AACC7",
                        "#F6964A"
                ],
             
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
                    center: ['50%', '50%'],
                    data: @json($datapma),
                    }
                ]
            };
        
        // basicpieChart.setOption(option1);
        if (option && typeof option === 'object') {
            myChart.setOption(option);
        }

        window.addEventListener('resize', myChart.resize);

</script>
@endpush