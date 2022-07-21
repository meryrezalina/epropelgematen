@extends('layouts.dashboard')

@section('content')
    <div class="mb-2">
        <a href=" {{route('dashboard.proposal.create')}} " class="btn btn-primary "> 
            <i class="fas fa-plus"></i> Tambah Proposal
        </a>
        <a href="{{ route('dashboard.proposalexport')}}" class="btn btn-primary"><i class="fas fa-download"></i></i> Download</a>
        <a href=" {{route('dashboard.proposals')}} " class="btn btn-secondary  "> 
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
                    <h3>Proposal</h3>
                </div>

                <div class="col-4">
                    <form method="get" action="{{ url('dashboard/proposal')}}">
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
            @if($proposals->total())
            <table class="table table-borderless table-striped table-hover table-bordered" id="myTable">
                <thead class="text-center">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">@sortablelink('bidangID','Bidang')</th> 
                        <th scope="col">@sortablelink('timpelID','Tim Pelayanan')</th> 
                        <th scope="col">@sortablelink('namaKegiatan','Nama Kegiatan')</th> 
                        <th scope="col">@sortablelink('nomorRAPB','Nomor RAPB')</th> 
                        <th scope="col">@sortablelink('totalBiaya','Total Biaya')</th> 
                        <th scope="col">@sortablelink('status','Status')</th> 
                        <th scope="col">@sortablelink('updated_at','Tanggal')</th> 
                        <th>&nbsp;</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($proposals as $proposal)
                    <tr>
                        <th scope="row">{{ ($proposals->currentPage() - 1) * $proposals->perPage() + $loop->iteration }}</th>
                        <td style="width: 10%">{{ $proposal->namaBidang}}</td>
                        <td style="width: 10%">{{ $proposal->namaTimpel}}</td>
                        <td style="width: 15%">{{ $proposal->namaKegiatan}}</td>
                        <td style="width: 25%; text-align:center">{{ $proposal->nomorRAPB}}</td>
                        <td>{{ number_format($proposal->totalBiaya, 0, ',', '.') }}</td>
                        <td style="width: 4px">@if ($proposal->status == 'Disetujui')
                            <i class="fas fa-check" style="color: green"></i>
                        @else
                        <i class="fas fa-times" style="color: red"></i>
                        @endif</td>
                        {{-- <td>{{ $proposal->tanggalUpdate}}</td> --}}
                        <td>{{Carbon\Carbon::parse($proposal->updated_at)->isoFormat('D MMMM Y')}}</td>

                        <td> <a href="{{route('dashboard.proposal.edit',  $proposal->proposalID)}}" class="btn btn-success btn-sm">
                            <i class="fas fa-edit"></i></a>
                            <a href="{{ route('dashboard.proposallist' ,  $proposal->proposalID) }}" class="btn btn-primary btn-sm"><i class="fas fa-download"></i><a>
                            
                        </td>
                    </tr> 
                    @endforeach
                </tbody>
                </table>

                {{ $proposals ->appends($request)->links()}}
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