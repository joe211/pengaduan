<div>
    {{-- <div class="col-md-4">
        <label>Pilih TW</label>
        <div class="form-group">
            <div class="c-inputs-stacked">
                @foreach ($tws as $item)
                    <input type="checkbox" wire:model="tw" wire:change="filter" value="{{ $item->id }}" id="{{ $item->id }}">
                    <label for="{{ $item->id }}" class="block mr-3">{{ $item->nama }}</label>
                @endforeach
            </div>
        </div>
    </div> --}}
    <div class="box">
        <div class="box-body analytics-info">
    <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Tahun Data</label>
                    <input type='text' wire:model="tahun" wire:change="filter" id='tahun' autocomplete="off" class="form-control" onchange="Livewire.emit('reloadTable', this.dataValue, this.value, this.tw)"/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Jenis</label>
                    <select wire:model="data" wire:change="filter" id="data" class="form-control">
                        <option value="">PMDN/PMA</option>
                        <option value="PMDN">PMDN</option>
                        <option value="PMA">PMA</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Triwulan</label>
                    <select wire:model="tw" wire:change="filter" id="data" class="form-control">
                        @foreach ($tws as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
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
        Livewire.on('reloadTable', function (dataValue, value, tw) {
            // @this.set('data', dataValue);
            @this.set('tahun', value);
            // @this.set('tw', tw);
        });
    });


</script>
@endpush
