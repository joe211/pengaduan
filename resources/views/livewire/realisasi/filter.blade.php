<div>
    <div class="box">
        <div class="box-body analytics-info">  
            <div class="row">
                <div class="col-md-4">
                <div wire:ignore>
                    <div class="form-group">
                        <label>Tahun Data</label>
                        <input type='text' wire:model="tahun" wire:change="filter" id='tahun' autocomplete="off" class="form-control" onchange="Livewire.emit('reloadTable', this.value, this.tw)"/>
                    </div>
                </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Triwulan</label>
                        <select wire:model="tw" wire:change="filter" id="data" class="form-control">
                            <option value="">Pilih TW</option>
                            @foreach ($tws as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                            {{-- <option value="5">TW 1 dan TW 2</option> --}}

                        </select>
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
    });

    document.addEventListener('livewire:load', function () {
        Livewire.on('reloadTable', function (value, tw) {
            // @this.set('data', dataValue);
            @this.set('tahun', value);
            @this.set('tw', tw);
        });
    });

   
</script>
@endpush