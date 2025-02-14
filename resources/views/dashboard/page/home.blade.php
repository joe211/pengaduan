@php
use Carbon\Carbon;
@endphp
@extends('dashboard.main')

@section('content')
<section class="content">		    
			<div class="row">
				<div class="col-xl-12 col-12">
                    <div class="box bg-primary-light">
                        <div class="box-body d-flex px-0">
                        <div class="flex-grow-1 p-30 flex-grow-1 bg-img dask-bg bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url('{{ asset('assets/backend/images/svg-icon/color-svg/custom-5.svg') }}')">

                                <div class="row">
                                    <div class="col-12 col-xl-7">
                                        <h2>Selamat Datang, <strong>{{ Auth()->user()->nama }}</strong></h2>

                                        <p class="text-dark my-10 font-size-16">
                                            Di Dashboard <strong class="text-warning">PENGADUAN</strong> <strong class="text-success">ONLINE</strong>.
                                        </p>
                                    </div>
                                    <div class="col-12 col-xl-5">


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @livewire('realisasi.filter') --}}
                    {{-- @livewire('realisasi.modul') --}}
                </div>

			</div>
		</section>
@endsection


