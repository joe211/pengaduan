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
                <form class="form" method="POST" action="{{ url('dashboard/form-praktik-kerja/update/'.$edit->id)}}" enctype="multipart/form-data">
                    @csrf @method('PATCH')
                    <div class="box-body">
                       
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group @error('nama') error @enderror">
                              <label>Nama <span class="text-danger">* Jika tidak sesuai ubah di profil</span></label>
                              <input type="text" name="nama" value="{{ old('nama',$profile->nama_user) }}" class="form-control" placeholder="Masukan Nama" readonly>
                              @error('nama')
                              <div class="help-block">
                                  <ul role="alert">
                                      <li>{{$message}}</li>
                                  </ul>
                              </div>
                              @enderror
                          </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group @error('nip') error @enderror">
                              <label>NIP <span class="text-danger">* Jika tidak sesuai ubah di profil</span></label>
                              <input type="text" name="nip" value="{{ old('nip',$profile->nip) }}" class="form-control" placeholder="Masukan NIP" readonly>
                              @error('nip')
                              <div class="help-block">
                                  <ul role="alert">
                                      <li>{{$message}}</li>
                                  </ul>
                              </div>
                              @enderror
                          </div>
                        </div>
                    </div>

                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group @error('pangkat') error @enderror">
                            <label>Pangkat <span class="text-danger">*</span></label>
                            <select name="pangkat"  id="pangkat" class="form-control select" style="width: 100%;" ">
                              <option selected="selected" value="">Pilih Pangkat</option>
                              @foreach($pangkat as $pangkat)
                                  <option @if(old('pangkat', $edit->pangkat_id) == $pangkat->id) selected="" @endif value="{{ $pangkat->id }}">{{ $pangkat->nama }}</option>
                              @endforeach
                            </select>
                            @error('pangkat')
                            <div class="help-block">
                                <ul role="alert">
                                    <li>{{$message}}</li>
                                </ul>
                            </div>
                            @enderror
                        </div>
                    </div>

                      <div class="col-md-4">
                        <div class="form-group @error('jabatan') error @enderror">
                            <label>Jabatan <span class="text-danger">*</span></label>
                            <select name="jabatan"  id="jabatan" class="form-control select" style="width: 100%;" ">
                              <option selected="selected" value="">Pilih Jabatan</option>
                              @foreach($jabatan as $jabatan)
                                  <option @if(old('jabatan', $edit->jabatan_id) == $jabatan->id) selected="" @endif value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                              @endforeach
                            </select>
                            @error('jabatan')
                            <div class="help-block">
                                <ul role="alert">
                                    <li>{{$message}}</li>
                                </ul>
                            </div>
                            @enderror
                        </div>
                    </div>

                      <div class="col-md-4">
                        <div class="form-group @error('opd') error @enderror">
                            <label>OPD <span class="text-danger">*</span></label>
                            <select name="opd"  id="opd" class="form-control select" style="width: 100%;" ">
                              <option selected="selected" value="">Pilih OPD</option>
                              @foreach($opd as $opd)
                              <option @if(old('opd', $edit->opd_id) == $opd->id) selected="" @endif value="{{ $opd->id }}">{{ $opd->nama }}</option>
                              @endforeach
                            </select>
                            @error('opd')
                            <div class="help-block">
                                <ul role="alert">
                                    <li>{{$message}}</li>
                                </ul>
                            </div>
                            @enderror
                        </div>
                    </div>

                  </div>
               

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group @error('file_opd') error @enderror">
                          <label>Surat Rekomendasi Dari Kepala OPD  <span class="text-danger">* Hanya file PDF dengan ukuran maksimum 2MB</span></label>
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <input type="file" name="file_opd" class="form-control" accept="application/pdf">
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group" >
                                <a target="_blank" href="{{ asset('uploads/file_opd/'.$edit->file_opd) }}" class="btn btn-sm btn-secondary text-left"><i class="fa fa-eye"></i> Lihat File </a>
                              </div>
                            </div>
                          </div>
                          @error('file_opd')
                          <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
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