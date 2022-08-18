@extends('layouts.dashboard')

@section('content')
    <div class="mb-2">
        <a href=" {{ route('dashboard.users.create') }} " class="btn btn-primary ">
            <i class="fas fa-plus"></i> Register User
        </a>


    </div>

    @if (session()->has('message'))
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
                    <h3>Users</h3>
                </div>

                <div class="col-4">
                    <form method="get" action="{{ url('dashboard/users') }}">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" name="q"
                                value="{{ $request['q'] ?? '' }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if ($users->total())
                <table class="table table-borderless table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Bidang</th>
                            <th>Timpel</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $users_item)
                            <tr>
                                <th scope="row">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </th>
                                <td>{{ $users_item->name }}</td>
                                <td>{{ $users_item->email }}</td>
                                <td>{{ $users_item->role }}</td>
                                {{-- <td>{{ $users_item->bidang->namaBidang }}</td> --}}
                                <td>{{ !empty($users_item->bidang->namaBidang) ? $users_item->bidang->namaBidang : '' }}
                                </td>
                                <td>{{ !empty($users_item->timpel->namaTimpel) ? $users_item->timpel->namaTimpel : '' }}
                                </td>
                                {{-- <td>{{ $users_item->timpel->namaTimpel }}</td </tr> --}}
                                <td>
                                    <button class="btn btn-danger btn-sm hapusUsers" data-id="{{ $users_item->id }}"><i
                                            class="fas fa-trash-alt"></i></button>
                                </td>
                        @endforeach
                    </tbody>
                </table>
                {{ $users->appends($request)->links() }}
            @else
                <h4> Belum ada Data</h4>
            @endif
        </div>
    </div>

@endsection
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('body').on('click', '.hapusUsers', function() {
            let data = $(this).data('id');
            if (confirm("Apakah Anda Ingin Menghapus ?") == true) {
                $.ajax({
                    url: "/dashboard/users/delete/" + data,
                    method: "get",
                    success: function(result) {
                        window.location.reload();
                    }
                });
            }

        });
    });
</script>
