@extends('dashboard.main')


@section('content')
@include('dashboard.include.breadcrumb')
<section class="content">
    <div class="row">
        
      <div class="col-12">
          <div class="box">
              <div class="box-header">						
                  <h4 class="box-title">{{ strtoupper($page_name) }}</h4>
                  <a href="{{ url('dashboard/'.$main_url) }}" class="btn btn-primary mb-2 mr-2 float-right"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</a>
              </div>
              <div class="box-body">
                  <div class="table-responsive">
                    <table id="tabel" class="tabel table table-striped display" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Triwulan</th>
                                <th>Tahun</th>
                                <th>Id Laporan</th>
                                <th>Periode Tahap</th>
                                <th>Jenis Badan Usaha</th>
                                <th>Nama Perusahaan</th>
                                <th>Sektor Utama</th>
                                <th>Sektor</th>
                                <th>Deskripsi KBLI</th>
                                <th>Wilayah</th>
                                <th>Kab/Kota</th>
                                <th>Provinsi</th>
                                <th>Negara</th>
                                <th>No Izin</th>
                                <th>Tambahan Investasi ($)</th>
                                <th>Total Investasi ($)</th>
                                <th>Jumlah Proyek</th>
                                <th>Jumlah TKI</th>
                                <th>Jumlah TKA</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                  </div>
              </div>
          </div>
      </div>
    </div>
</section>

@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/backend/vendor_components/datatable/datatables.css')}}">
@endpush

@push('js')
<script src="{{asset('assets/backend/vendor_components/datatable/datatables.js')}}"></script>
<script>
    $(document).ready(function(){
        
           var tabel = $('.tabel').DataTable({
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
                "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 7 ,
           processing: true,
           serverSide: true,
           ajax: {
           url: "{{ url('dashboard/form-pma/tabledetail/'.$id) }}",
           },
           columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,searchable: false},
            {
                data: 'nama_jenis_berjangka',
                name: 'nama_jenis_berjangka'
            },
            {
                data: 'tahun',
                name: 'tahun'
            },
            {
                data: 'id_laporan',
                name: 'id_laporan'
            },
            {
                data: 'periode_tahap',
                name: 'periode_tahap'
            },
            {
                data: 'jenis_badan_usaha',
                name: 'jenis_badan_usaha'
            },
            {
                data: 'nama_perusahaan',
                name: 'nama_perusahaan'
            },
            {
                data: 'nama_sektor_utama',
                name: 'nama_sektor_utama'
            },
            {
                data: 'sektor',
                name: 'sektor'
            },
            {
                data: 'deskripsi_kbli',
                name: 'deskripsi_kbli'
            },
            {
                data: 'wilayah',
                name: 'wilayah'
            },
            {
                data: 'nama_kota',
                name: 'nama_kota'
            },
            {
                data: 'provinsi',
                name: 'provinsi'
            },
            {
                data: 'negara',
                name: 'negara'
            },
            {
                data: 'no_izin',
                name: 'no_izin'
            },
            {
                data: 'tambahan_investasi',
                name: 'tambahan_investasi'
            },
            {
                data: 'total_investasi',
                name: 'total_investasi'
            },
            {
                data: 'jumlah_proyek',
                name: 'jumlah_proyek'
            },
            {
                data: 'jumlah_tki',
                name: 'jumlah_tki'
            },
            {
                data: 'jumlah_tka',
                name: 'jumlah_tka'
            }
           ],
           "initComplete": function(settings, json) {
           }
           });
          
           
       });
    </script>
@endpush