@extends('layouts.dashboard')

@section('content')
    <div class="mb-2">
        <a href=" {{route('dashboard.lpj.create')}} " class="btn btn-primary "> 
            <i class="fas fa-plus"></i> LPJ
        </a>
        <a href=" {{route('dashboard.lpjs')}} " class="btn btn-secondary  "> 
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
                    <h3>LPJ</h3>
                </div>

                <div class="col-4">
                    <form method="get" action="{{ url('dashboard/lpj')}}">
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
            @if($lpj->total())
            <table class="table table-borderless table-striped table-hover table-bordered" id="myTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th scope="col">@sortablelink('proposalID','Proposal')</th>
                        <th scope="col">@sortablelink('aprovedbyRomo','Approved By Romo')</th>
                        <th scope="col">@sortablelink('approvedbyKabid','Approved By Kabid')</th> 
                        <th scope="col">@sortablelink('saldo','Biaya Proposal')</th>
                        <th scope="col">@sortablelink('totalPengeluaran','Total Pengeluaran')</th>
                        <th scope="col">@sortablelink('created_at','Tanggal')</th> 
                        <th>&nbsp;</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lpj as $lpj_item)
                    <tr>
                        <th scope="row">{{ ($lpj->currentPage() - 1) * $lpj->perPage() + $loop->iteration }}</th>
                        <td>{{ $lpj_item->namaKegiatan}}</td>

                        <td style="width: 4px">@if ($lpj_item->approvedbyRomo == 'Disetujui')
                            <i class="fas fa-check" style="color: green"></i>
                            @else
                            <i class="fas fa-times" style="color: red"></i>
                            @endif
                        </td>
                        <td style="width: 4px">@if ($lpj_item->approvedbyKabid == 'Disetujui')
                            <span><i class="fas fa-check" style="color: green"></i></span>
                            {{-- <i class="fas fa-check" style="color: green"></i> --}}
                            @else
                            <i class="fas fa-times" style="color: red"></i>
                            @endif
                        </td>
                        <td>{{ $lpj_item->saldo}}</td>
                        <td>{{ $lpj_item->totalPengeluaran}}</td>
                        <td>{{Carbon\Carbon::parse($lpj_item->updated_at)->isoFormat('D MMMM Y')}}</td>
                        
                        

                        <td> <a href="{{route('dashboard.lpj.edit',  $lpj_item->lpjID)}}" class="btn btn-success btn-sm">
                            <i class="fas fa-edit"></i></a>
                        </td>
                    </tr> 
                    @endforeach
                </tbody>
                </table>

                {{ $lpj ->appends($request)->links()}}
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