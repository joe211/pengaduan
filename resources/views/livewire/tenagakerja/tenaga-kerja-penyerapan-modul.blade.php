<div>
    <div wire:loading.remove>
        {{-- Data Tahun {{ $tahun ?? date('Y') }} --}}
        <div class="text-center">
            <h3>{{$title}}</h3>
        </div>
       <div class="row">
           <div class="col">
               <div class="box">
                   <div class="box-body analytics-info">
                       <h4 class="box-title">Diagram Penyerapan TKI Pertahun</h4>
                       <div class="box-body analytics-info">
                           <div id="basic-bar" style="height:400px;"></div>
                       </div>
                   </div>
               </div>
           </div>
           <div class="col">
               <div class="box">
                   <div class="box-body analytics-info">
                       <h4 class="box-title">Diagram Penyerapan TKA Pertahun</h4>
                       <div class="box-body analytics-info">
                           <div id="basic-bar1" style="height:400px;"></div>
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
<script>
	
	document.addEventListener('livewire:load', function () {
        Livewire.on('updateChart', function (data) {
            var tki = data.dataTKI;
            var tka = data.dataTKA;
            var basicpieChart = document.getElementById('basic-bar');
            var basicpieChart1 = document.getElementById('basic-bar1');
            var myChart = echarts.init(basicpieChart, null, {
                renderer: 'canvas',
                useDirtyRect: false
            });
            var myChart1 = echarts.init(basicpieChart1, null, {
                renderer: 'canvas',
                useDirtyRect: false
            });
            // specify chart configuration item and data
            var option = {
                    // Setup grid
                    grid: {
                        left: '1%',
                        right: '2%',
                        bottom: '3%',
                        containLabel: true
                    },

                    // Add Tooltip
                    tooltip : {
                        trigger: 'axis'
                    },

                    legend: {
                        data:['TKI']
                    },
                    toolbox: {
                        show : true,
                        feature : {

                            magicType : {show: true, type: ['line', 'bar']},
                            restore : {show: true},
                            saveAsImage : {show: true}
                        }
                    },
                    color: ["#38649f"],
                    calculable : true,
                    xAxis : [
                        {
                            type : 'category',
                            data : [2022,2023]
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            name:'TKI',
                            type:'bar',
                            data:[5544,6633],
                            markPoint : {
                            data : [
                                {type : 'max', name: 'Max'},
                                {type : 'min', name: 'Min'}
                            ]
                        },
                            
                        }
                    ]
                };

            option.xAxis[0].data = data.dataTahun;
            option.series[0].data = tki;
            // use configuration item and data specified to show chart
            if (option && typeof option === 'object') {
                myChart.setOption(option);
            }
            window.addEventListener('resize', myChart.resize);
        
            var option1 = {
                    // Setup grid
                    grid: {
                        left: '1%',
                        right: '2%',
                        bottom: '3%',
                        containLabel: true
                    },

                    // Add Tooltip
                    tooltip : {
                        trigger: 'axis'
                    },

                    legend: {
                        data:['TKA']
                    },
                    toolbox: {
                        show : true,
                        feature : {

                            magicType : {show: true, type: ['line', 'bar']},
                            restore : {show: true},
                            saveAsImage : {show: true}
                        }
                    },
                    color: ["#389f99"],
                    calculable : true,
                    xAxis : [
                        {
                            type : 'category',
                            data : [2022,2023]
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            name:'TKA',
                            type:'bar',
                            data:[5544,6633],
                            markPoint : {
                            data : [
                                {type : 'max', name: 'Max'},
                                {type : 'min', name: 'Min'}
                            ]
                        },
                        }
                    ]
                };

            option1.xAxis[0].data = data.dataTahun;
            option1.series[0].data = tka;
            // use configuration item and data specified to show chart
            if (option1 && typeof option1 === 'object') {
                myChart1.setOption(option1);
            }
            window.addEventListener('resize', myChart1.resize);
        });
    });

</script>

@endpush



