@extends('layouts.dashboard')

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <div class="mb-2">
        <a href=" {{route('dashboard.propel.create')}} " class="btn btn-primary "> 
            <i class="fas fa-plus"></i> Tambah Propel
        </a>
        <a href="{{ route('dashboard.propelexport')}}" class="btn btn-primary"><i class="fas fa-download"></i> Download</a>
        <a href=" {{route('dashboard.propel')}} " class="btn btn-secondary  "> 
            <i class="fas fa-redo"></i> Muat Ulang
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
                    <h3>Propel</h3>
                </div>

                <div class="col-4">
                    <form method="get" action="{{ url('dashboard/propel')}}">
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
            @if($propel->total())
                <table class="table table-borderless table-striped table-hover table-bordered" id="myTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">@sortablelink('bidangID','Bidang')</th> 
                        <th scope="col">@sortablelink('timpelID','Tim Pelayanan')</th> 
                        <th scope="col">@sortablelink('namaKegiatan','Nama Kegiatan')</th> 
                        <th scope="col">@sortablelink('nomorRAPB','Nomor RAPB')</th> 
                        <th scope="col">@sortablelink('namaPJ','Nama PJ')</th> 
                        <th scope="col">@sortablelink('status','Status')</th> 
                        <th scope="col">@sortablelink('created_at','Tanggal')</th> 
                        <th>&nbsp;</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($propel as $propel_item)
                    <tr>
                        <th scope="row">{{ ($propel->currentPage() - 1) * $propel->perPage() + $loop->iteration }}</th>
                        <td>{{ $propel_item->namaBidang}}</td>
                        <td>{{ $propel_item->namaTimpel}}</td>
                        <td>{{ $propel_item->namaKegiatan}}</td>
                        <td>{{ $propel_item->nomorRAPB}}</td>
                        <td>{{ $propel_item->namaPJ}}</td>
                        <td> @if($propel_item->status == 'proposal')
                            <a href="{{ route('dashboard.proposal.store') }}"></a>
                            @else
                            {{ $propel_item->status}}
                            @endif
                        </td>
                        <td>{{Carbon\Carbon::parse($propel_item->updated_at)->isoFormat('D MMMM Y')}}</td>
                        <td> 
                            <a href="{{route('dashboard.propel.edit',  $propel_item->propelID)}}" class="btn btn-success btn-sm">
                            <i class="fas fa-edit"></i></a>
                            <a href="{{ route('dashboard.dataexport2' ,  $propel_item->propelID) }}" class="btn btn-primary btn-sm"><i class="fas fa-download"></i></a>
                        </td>
                        
                    </tr> 
                    @endforeach
                </tbody>
                </table>
                {{ $propel ->appends($request)->links()}}
                {{-- {!! $propel->appends(\Request::except('page'))->render() !!} --}}

            @else
                <h4> Belum ada Data</h4>
            @endif 
        </div>
    </div>

    @push('scripts')
        <script src="//cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="js/scripts.js"></script>
        
        <script>
            $(document).ready( function () {
        $('#myTable').DataTable();
    } );
        </script>
    @endpush
@endsection
