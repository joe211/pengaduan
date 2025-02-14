<div>
    
    <div class="box">
    <div class="box-body analytics-info"> 
    <div class="row">
            <div class="col-md-4">
                <div wire:ignore>
                <div class="form-group">
                    <label>Mulai Tahun</label>
                    <input type='text' wire:model="tahun" wire:change="filter" id='tahun' autocomplete="off" class="form-control"/>
                </div>
                </div>
            </div>
            <div class="col-md-4">
                <div wire:ignore>
                <div class="form-group">
                    <label>Sampai Tahun</label>
                    <input type='text' wire:model="tahun2" wire:change="filter" id='tahun2' autocomplete="off" class="form-control" onchange="getTahunValue()"/>
                </div>
                </div>
            </div>
            
           
    </div>
    </div>
    </div>
</div>

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="{{ asset('assets/backend/vendor_plugins/datepicker/js/bootstrap-datepicker.js') }}"></script>
<script type="text/javascript">
    $(function() {
       $('#tahun').datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true
       });
    
       $('#tahun2').datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true
       });
    });

    function getTahunValue() {
        // Get the value of the "tahun" input field
        var tahunValue = document.getElementById('tahun').value;
        var tahun = document.getElementById('tahun2').value;
        // You can also pass the value to Livewire if needed
        Livewire.emit('reloadTable', tahunValue, tahun);
    }
    // document.addEventListener('livewire:load', function () {
    //     Livewire.on('reloadTable', function (tahunValue, value) {
    //         // console.log(tahunValue)
    //         console.log(tahunValue)
    //         @this.set('tahun', tahunValue);
    //         // @this.set('tahun2', value2);
    //     });
    // });

   
</script>
@endpush