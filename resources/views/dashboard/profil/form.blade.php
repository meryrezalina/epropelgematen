
@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 align-self-center">
                    <h3>Profil</h3>
                </div>

                <div class="col-6 text-right">
                    <button class=" btn btn-sm btn-primary" data-toggle="modal" data-target="#passwordModal">
                        Ganti Password <i class="fas fa-key"></i></i>
                    </button>
                </div>
            </div>
            @if(session()->has('error'))
                    <span class="alert alert-danger">
                        <strong>{{ session()->get('error') }}</strong>
                    </span>
                @endif
                
                @if(session()->has('success'))
                    <span class="alert alert-success">
                        <strong>{{ session()->get('success') }}</strong>
                    </span>
                @endif
                @if(session()->has('success_update'))
                    <span class="alert alert-success">
                        <strong>{{ session()->get('success_update') }}</strong>
                    </span>
                @endif
        </div>

        

        <div class="card-body ">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form method="post" action="{{route('dashboard.profil.update') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($users))
                        @method('put')
                        @endif


                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control @error('name') {{'is-invalid'}} @enderror" name="name" placeholder= "Nama" value="{{ Auth::user()->name }}">
                        @error('name')
                        <span class="text-danger"> {{$message}} </span>   
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control @error('email') {{'is-invalid'}} @enderror" name="email" placeholder= "Nama" value="{{ Auth::user()->email }}">
                        @error('email')
                        <span class="text-danger"> {{$message}} </span>   
                        @enderror
                    </div>
                      
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-sm">{{ $button }}</button>
                            <button type="button" onclick="window.history.back()" class="btn btn-sm btn-secondary">Cancel</button>
                        </div>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>

    {{-- CHANGE PASSWORD MODAL --}}
    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Ganti Password</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            
            <div class="modal-body">
              <form action="{{route('dashboard.profil.update_password')}}" method="POST">
                  @csrf
                <div class="form-group">
                  <label for="recipient-name" class="col-form-label">Password lama:</label>
                  <input type="password" class="form-control" id="pass_lama" name="pass_lama">
                  @if($errors->any('pass_lama'))
                  <span class="text-danger">{{$errors->first('pass_lama')}}</span>
                  @endif
                </div>
                <div class="form-group">
                  <label for="message-text" class="col-form-label">Password Baru:</label>
                  <input type="password" class="form-control" id="pass_baru" name="pass_baru">
                  @if($errors->any('pass_baru'))
                  <span class="text-danger">{{$errors->first('pass_baru')}}</span>
                  @endif
                </div>
                <div class="form-group">
                    <label for="message-text" class="col-form-label">Konfirmasi Password:</label>
                    <input type="password" class="form-control" id="konfirmasi" name="konfirmasi">
                    @if($errors->any('konfirmasi'))
                    <span class="text-danger">{{$errors->first('konfirmasi')}}</span>
                    @endif
                  </div>
                  <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>
          </div>
        </div>
      </div>

    {{-- DELETE MODAL --}}
    {{-- @if(isset($lpj))
        <div class="modal fade" id="deleteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Delete</h5>
                    </div>
                    
                    <div class="modal-body">
                        <p>Anda yakin ingin menghapus data ini?</p>
                    </div>
                    
                    <div class="modal-footer">
                        <form action="{{route('dashboard.lpj.delete', $lpj->lpjID)}}" method="post">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif --}}

@endsection