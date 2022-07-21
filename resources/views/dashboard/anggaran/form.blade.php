@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-8 align-self-center">
                    <h3>Anggaran Program Pelayanan</h3>
                </div>

                <div class="col-4 text-right">
                    <button class=" btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body ">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form method="post" action="{{ route($url, $anggaranpropel->anggaranPropelID ?? '') }}" enctype="multipart/form-data">
                        @csrf
                        @if (isset($anggaranpropel))
                            @method('put')
                        @endif

                        <div class="form-group">
                            <label for="namaKegiatan">Nama Kegiatan</label>
                            <select name="propelID" class="form-control" name="propelID" id="propelID">
                                <option value="">Pilih Nama Kegiatan</option>
                                @foreach ($pro as $propel)
                                    <option value="{{ $propel->propelID }}"> {{ $propel->namaKegiatan }} </option>
                                @endforeach
                            </select>
                            @error('propelID')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="anggaranDeskripsi">Anggaran Deskripsi</label>
                            <input type="text" class="form-control @error('anggaranDeskripsi') {{'is-invalid'}} @enderror" name="anggaranDeskripsi" placeholder="Anggaran Deskripsi" value="{{old('anggaranDeskripsi') ?? $anggaranpropel->anggaranDeskripsi ?? ''}}">
                            @error('anggaranDeskripsi')
                            <span class="text-danger"> {{$message}} </span>   
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="hargaSatuan">Harga Satuan</label>
                            <input type="number" class="form-control @error('hargaSatuan') {{'is-invalid'}} @enderror" name="hargaSatuan" placeholder="10000" value=" {{ old('hargaSatuan') ?? $anggaranpropel->hargaSatuan ?? ''}} ">
                            @error('hargaSatuan')
                            <span class="text-danger"> {{$message}} </span>   
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="kuantitas">Kuantitas</label>
                            <input type="number" class="form-control @error('kuantitas') {{'is-invalid'}} @enderror" name="kuantitas" placeholder="1" value=" {{ old('kuantitas') ?? $anggaranpropel->kuantitas ?? ''}} ">
                            @error('kuantitas')
                            <span class="text-danger"> {{$message}} </span>   
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sumberDana">Sumber Dana</label>
                            <select name="sumberID" class="form-control" name="sumberID" id="sumberID">
                                <option value="">Pilih Sumber Dana</option>
                                @foreach ($sum as $sumber)
                                    <option value="{{ $sumber->sumberID }}"> {{ $sumber->sumberDeskripsi }} </option>
                                @endforeach
                            </select>
                            @error('sumberID')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-sm">{{ $button }}</button>
                            <button type="button" onclick="window.history.back()"
                                class="btn btn-sm btn-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- @if (isset($movie))
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Delete</h5>
                </div>

                <div class="modal-body">
                    <p>Hapus film {{$movie->namaKegiatan}}????????</p>
                </div>

                <div class="modal-footer">
                    <form action="{{route('dashboard.movies.delete', $movie->id)}}" method="post">
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
