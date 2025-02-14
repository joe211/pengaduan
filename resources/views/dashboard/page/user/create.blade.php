@extends('dashboard.main')


@section('content')
@include('dashboard.include.breadcrumb')
<section class="content">
    <div class="row">			  
        <div class="col-lg-10 col-12">
              <div class="box">
                <div class="box-header with-border">
                  <h4 class="box-title">{{ strtoupper($page_name) }}</h4>
                </div>
                <!-- /.box-header -->
                <form class="form" method="POST" action="{{ action('Backend\UserController@store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">

                      <div class="row">
                          <div class="col-md-12">
                            <div class="form-group @error('nama') error @enderror">
                              <label>Nama <span class="text-danger">*</span></label>
                              <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" placeholder="Masukan Nama">
                              @error('nama')
                              <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
                              @enderror
                            </div>
                          </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group @error('username') error @enderror">
                            <label>Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="Masukan Username">
                            @error('username')
                            <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
                            @enderror
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group @error('email') error @enderror">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="text" name="email" value="{{ old('email') }}" class="form-control" placeholder="Masukan Email">
                            @error('email')
                            <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
                            @enderror
                          </div>
                        </div>
                      </div>

                      <div class="row">
                          <div class="col-md-12">
                              <div class="form-group @error('alamat') error @enderror">
                                  <label>Alamat <span class="text-danger">*</span></label>
                                  <textarea name="alamat" class="form-control" placeholder="Masukan No. Handphone">{{ old('alamat') }}</textarea>
                                  @error('alamat')
                                  <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
                                  @enderror
                              </div>
                          </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group @error('nomor_whatsapp') error @enderror">
                            <label>Nomor WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_whatsapp" value="{{ old('nomor_whatsapp') }}" class="form-control" placeholder="Masukan No. Handphone">
                            @error('nomor_whatsapp')
                            <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
                            @enderror
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group @error('password') error @enderror">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="text" name="password" value="{{ old('password') }}" class="form-control" placeholder="Masukan Password">
                            @error('password')
                            <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
                            @enderror
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group @error('password_konfirmasi') error @enderror">
                            <label>Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="text" name="password_konfirmasi" value="{{ old('password_konfirmasi') }}" class="form-control" placeholder="Masukan Konfirmasi Password">
                            @error('password_konfirmasi')
                            <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
                            @enderror
                          </div>
                        </div>
                      </div>

                      <div class="row">
                          <div class="col-md-12">
                            <div class="form-group @error('level_user') error @enderror">
                              <label>Level User <span class="text-danger">*</span></label>
                              <select name="level_user"  id="level_user" class="form-control select" style="width: 100%;" ">
                                <option selected="selected" value="">Pilih Level User</option>
                                @foreach($level as $level)
                                    <option @if(old('level_user') == $level->id) selected="" @endif value="{{ $level->id }}">{{ $level->nama }}</option>
                                @endforeach
                              </select>
                              @error('level_user')
                              <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
                              @enderror
                            </div>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-md-12">
                            <div class="form-group @error('jenis_bank') error @enderror">
                              <label>Jenis Bank <span class="text-danger">*</span></label>
                              <select name="jenis_bank"  id="jenis_bank" class="form-control select" style="width: 100%;" ">
                                <option selected="selected" value="">Pilih Bank</option>
                                @foreach($bank as $bank)
                                    <option @if(old('jenis_bank') == $bank->id) selected="" @endif value="{{ $bank->id }}">{{ $bank->nama }}</option>
                                @endforeach
                              </select>
                              @error('jenis_bank')
                              <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
                              @enderror
                            </div>
                          </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group @error('nomor_rekening') error @enderror">
                            <label>Nomor Rekening <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening') }}" class="form-control" placeholder="Masukan Nomor Rekening">
                            @error('nomor_rekening')
                            <div class="help-block"><ul role="alert"><li>{{$message}}</li></ul></div>
                            @enderror
                          </div>
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
@endpush