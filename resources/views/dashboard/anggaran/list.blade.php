@extends('layouts.dashboard')

@section('content')
    <div class="mb-2">
        <a href=" {{route('dashboard.anggaran.create')}} " class="btn btn-primary "> 
            <i class="fas fa-plus"></i> Anggaran Program Pelayanan
        </a>
    </div>

    @if(session()->has('message'))
        <div class="alert alert-success">
            <strong>{{ session()->get('message') }}</strong>
            <button class="close" type="button" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-8 align-self-center">
                    <h3>Anggaran Program Pelayanan</h3>
                </div>

                <div class="col-4">
                    <form method="get" action="{{ url('dashboard/anggaran')}}">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" name="q" value="{{ $request['q'] ?? ''}}">
                            <div class="input-group-append">
                                <button type= "submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($anggaranpropel->total())
                <table class="table table-borderless table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Anggaran Deskripsi</th>
                        <th>Harga Satuan</th> 
                        <th>Kuantitas</th>
                        <th>Sumber</th> 
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($anggaranpropel as $anggaran_item)
                    <tr>
                        <th scope="row">{{ ($anggaranpropel->currentPage() - 1) * $anggaranpropel->perPage() + $loop->iteration }}</th>
                        <td>{{ $anggaran_item->propel->namaKegiatan}}</td>
                        <td>{{ $anggaran_item->anggaranDeskripsi}}</td>
                        <td>{{ $anggaran_item->hargaSatuan}}</td>
                        <td>{{ $anggaran_item->kuantitas}}</td>
                        <td>{{ $anggaran_item->sumberdana->sumberDeskripsi}}</td>
                        <td> <a href="{{route('dashboard.anggaran.edit',  $anggaran_item->anggaranPropelID)}}" class="btn btn-success btn-sm">
                            <i class="fas fa-edit"></i></a></td>
                    </tr> 
                    @endforeach
                </tbody>
                </table>
                {{ $anggaranpropel ->appends($request)->links()}}
            @else
                <h4> Belum ada Data</h4>
            @endif 
        </div>
    </div>

@endsection