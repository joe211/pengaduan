@extends('dashboard.main')


@section('content')
@include('dashboard.include.breadcrumb')
<section class="content">
    <div class="row">			  
        <div class="col-lg-12 col-12">
              <div class="box">
                <div class="box-header with-border">
                  <h4 class="box-title">{{ strtoupper($page_name) }}</h4>
                </div>
                <!-- /.box-header -->
                <form class="form" method="POST" action="{{ action('Backend\pmdnController@store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">

                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group @error('file') error @enderror">
                          <label>File PMDN Excel <span class="text-danger">*</span></label>
                          <input type="file" name="file" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                          @error('file')
                          <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
                          @enderror
                        </div>
                      </div>

                    <div class="col-md-4">
                        <div class="form-group @error('jenis_berjangka_id') error @enderror">
                            <label>Triwulan <span class="text-danger">*</span></label>
                            <select name="jenis_berjangka_id"  id="jenis_berjangka_id" class="form-control select" style="width: 100%;" ">
                              <option selected="selected" value="">Pilih Triwulan</option>
                              @foreach($jenis_berjangka_id as $jenis_berjangka_id)
                                  <option @if(old('jenis_berjangka_id') == $jenis_berjangka_id->id) selected="" @endif value="{{ $jenis_berjangka_id->id }}">{{ $jenis_berjangka_id->nama }}</option>
                              @endforeach
                            </select>
                            @error('jenis_berjangka_id')
                            <div class="help-block">
                                <ul role="alert">
                                    <li>{{$message}}</li>
                                </ul>
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group @error('tahun') error @enderror">
                          <label>Tahun <span class="text-danger">*</span></label>
                          <select name="tahun" id="tahun" class="form-control select" style="width: 100%;">
                              <option selected="selected" value="">Pilih Tahun</option>
                              @for ($i = date('Y'); $i >= 2000; $i--)
                                  <option @if(old('tahun') == $i) selected="" @endif value="{{ $i }}">{{ $i }}</option>
                              @endfor
                          </select>
                          @error('tahun')
                            <div class="help-block">
                                <ul role="alert">
                                    <li>{{$message}}</li>
                                </ul>
                            </div>
                            @enderror
                      </div>
                  </div>

                  </div>

                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{{ url('dashboard/'.$main_url) }}" type="button" class="btn btn-rounded btn-warning btn-outline mr-1">
                          <i class="ti-trash"></i> Batalkan
                        </a>
                        <button type="submit" id="button-simpan" class="btn btn-rounded btn-primary btn-outline">
                          <i id="icon-simpan" class="ti-save-alt"></i> <i id="loading-simpan" class="fa fa-spin fa-refresh d-none"></i> Simpan
                        </button>
                    </div>  
                </form>
              </div>
              <!-- /.box -->			
        </div>  

        

    </div>

  </div>
  <!-- /.row -->

</section>
@endsection
@push('css')

@endpush
@push('js')
  <script src="{{ asset('assets/backend/vendor_components/select2/dist/js/select2.full.js')}}"></script>
  <script>  
    $(document).ready(function() {
      $(".select").select2();
    });
  </script> 

  <script>
      @if(old('provinsi'))
                $('#kota').select2({
                    ajax: {
                        url:"{{url('dashboard/form-tugas-belajar/list-kota')}}/" + {{ old('provinsi') }},
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        }
                    }
                });
        @endif
           
        $('#provinsi').on('change',function()
        {
            var id_provinsi = $(this).val();
            $('#kota').empty().trigger('change');
            $('#kota').select2({
                
                placeholder: "Pilih Kota",
                ajax: {
                    url:"{{url('dashboard/form-tugas-belajar/list-kota')}}/" + id_provinsi,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });
        })    
  </script>
@endpush