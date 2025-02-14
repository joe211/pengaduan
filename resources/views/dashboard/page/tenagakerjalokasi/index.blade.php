@extends('dashboard.main')

@section('content')
@include('dashboard.include.breadcrumb')
<section class="content">		    
    <div class="row">
        <div class="col-xl-12 col-12">
            {{-- <div class="box">
                <div class="box-body analytics-info"> --}}
                    @livewire('tenagakerja.filter-tenaga-kerja-lokasi')

                    @livewire('tenagakerja.tenaga-kerja-lokasi-modul')
                {{-- </div>
            </div> --}}
        </div>
    </div>

</section>
@endsection