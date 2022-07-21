@extends('layouts.app')

@section('content')
<body style=" background-image: url('assets/img/login.jpg');  background-repeat: no-repeat; background-size: cover">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="border-radius: 20px; size: 10px">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="bidang" class="col-md-4 col-form-label text-md-right">Bidang</label>
                                <div class="col-md-6">
                                <select name="bidangID" class="form-control" name="bidangID" id="bidangID" onchange="setTimpel()">
                                    <option value="">Pilih Bidang</option>
                                    {{-- @foreach ($bid as $bidang)
                                        <option value="{{ $bidang->bidangID }}"
                                            @if ($bidang->bidangID == $optionBidang)
                                                selected="selected"
                                            @endif
                                            > {{ $bidang->namaBidang }} </option>
                                    @endforeach --}}
                                </select>
                                @error('bidangID')
                                    <span class="text-danger"> {{ $message }} </span>
                                @enderror
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="timpel" class="col-md-4 col-form-label text-md-right">Tim Pelayanan</label>
                                <div class="col-md-6">
                                <select name="timpelID" class="form-control" name="timpelID" id="timpelID">
                                    <option value="">Pilih Timpel</option>
                                    {{-- @foreach ($timByBidang as $timpel)
                                        <option value="{{ $timpel->timpelID }}"
                                            
                                            @if ($timpel->timpelID == $optionTimpel)
                                                selected = "selected"
                                            @endif
                                            > {{ $timpel->namaTimpel }} </option>
                                    @endforeach --}}
                                </select>
                                @error('timpelID')
                                    <span class="text-danger"> {{ $message }} </span>
                                @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection
