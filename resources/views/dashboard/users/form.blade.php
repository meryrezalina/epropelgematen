@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-8 align-self-center">
                    <h3>Regitrasi User</h3>
                </div>

                <div class="card-body">
                    <form method="post" action="{{ route($url, $users->id ?? '') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @if (isset($users))
                            @method('put')
                        @endif
                        @php
                            if(isset($users)) {
                                $optionBidang = $users->bidangID;
                                $optionTimpel = $users->timpelID;
                            } else {
                                $optionBidang = old('bidangID');
                                $optionTimpel = old('timpelID');
                            }
                        @endphp
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" placeholder="Nama" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

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
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email">

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
                                    @foreach ($bid as $bidang)
                                        <option value="{{ $bidang->bidangID }}" @if ($bidang->bidangID == $optionBidang)
                                            selected="selected"
                                        @endif> {{ $bidang->namaBidang }} </option>
                                    @endforeach
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
                                    @foreach ($tim as $timpel)
                                        <option value="{{ $timpel->timpelID }}" @if ($timpel->timpelID == $optionTimpel)
                                            selected = "selected"
                                        @endif> {{ $timpel->namaTimpel }} </option>
                                    @endforeach
                                </select>
                                @error('timpelID')
                                    <span class="text-danger"> {{ $message }} </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>

                            <div class="col-md-6">
                                <input id="role" type="role" class="form-control @error('role') is-invalid @enderror" placeholder="Role" name="role" value="{{ old('role') }}" required autocomplete="role">

                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" placeholder="Password min 6 karakter" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password_confirmation" placeholder="Konfirmasi Password" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary btn-sm">{{ $button }}</button>
                                <button type="button" onclick="window.history.back()"
                                class="btn btn-sm btn-secondary">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
    </div>
    <script>
        function setTimpel() {
                var bidangID = document.getElementById("bidangID").value;
                var timpel = <?php echo $tim; ?>;
                // Untuk menyimpan timpelId yang ada di propel
                var currentSelectedTimpelId = <?php echo isset($optionTimpel) ? $optionTimpel : 0; ?>;
                
                var timpelByBidangID = timpel.filter(function (el) {
                    return el.bidangID == bidangID;
                }) 
                var opt = "<option value = ''>Pilih Timpel</option>";
                var selectedIdx = 0;
                for (let i=0; i<timpelByBidangID.length; i++) {
                    if (timpelByBidangID[i].timpelID == currentSelectedTimpelId) {
                        selectedIdx = i+1;
                    }
                    opt =opt+ "<option value="  +  timpelByBidangID[i].timpelID +">" + timpelByBidangID[i].namaTimpel + "</option>";
                }
                console.log(opt);
                var timpelObject = document.getElementById("timpelID");
                timpelObject.innerHTML = opt;
                timpelObject.getElementsByTagName('option')[selectedIdx].selected = 'selected';
            }
    </script>
@endsection