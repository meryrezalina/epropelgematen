@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-8 align-self-center">
                    <h3>E-LPJ</h3>
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
                    <form method="post" action="{{ route($url, $lpj->lpjID ?? '') }}" enctype="multipart/form-data">
                        @csrf
                        @if (isset($lpj))
                            @method('put')
                        @endif


                        <div class="form-group">
                            <label for="proposal">Proposal</label>
                            <select name="proposalID" class="form-control" id="proposal-select">
                                {{-- <option value="">Pilih Proposal</option> --}}
                                {{-- @foreach ($pro as $proposal)
                                    @if ($proposal->proposalID == (old('proposalID') ?? ($lpj->proposalID ?? '')))
                                         <option value="{{$proposal->proposalID}}" selected>{{ $proposal->namaKegiatan }} </option>
                                    @else
                                    <option value="{{$proposal->proposalID}}" placeholder="Pilih Film">{{ $proposal->namaKegiatan }} </option>
                                    @endif
                                @endforeach --}}
                            </select>
                            {{-- @error('proposalID')
                            <span class="text-danger"> {{$message}} </span>   
                            @enderror --}}
                        </div>

                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <label for="anggaran">Pengeluaran LPJ</label>
                                </div>
                                <anggaran-lpj-component :sumber-dana="{{ $sum ?? json_encode(old('sum') ?? []) }}"
                                    :list-of-anggaran="{{ json_encode($anggarans) ?? json_encode(old('anggarans') ?? []) }}">
                                </anggaran-lpj-component>
                            </div>
                            @error('columns')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="saldo">Anggaran Proposal</label>
                            <input type="hidden" class="form-control" name="saldo" id="pengeluaran">
                            <input type="text" class="form-control" id="saldo-pengeluaran" disabled>
                            @error('saldo')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>
                        {{-- <div id="indikator-target" target-indikator="{{old('indikators')}}">

                    </div> --}}

                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <label for="indikator">Indikator Target</label>
                                </div>
                                <table class="table table-borderless table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Indikator Deskripsi</th>
                                            <th>Target</th>
                                            <th>Pencapaian LPJ</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($lpj))
                                            @foreach ($indikators as $indkts)
                                                <div id="dataindikator">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $indkts->indikatorDeskripsi }}</td>
                                                    <td>{{ $indkts->target }}</td>
                                                    <td>{{ $indkts->pencapaianLPJ }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-outline-success btn-sm"
                                                            data-toggle="modal" data-target="#indikatorModal"><i
                                                                class="fas fa-edit"></i></button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm"><i
                                                                class="fas fa-times"></i></button>
                                                    </td>
                                                </div>

                                                <div id="inputIndikatorHidden">
                                                    <input type="hidden" class="form-control"
                                                        name="indikators[{{ $loop->index }}][indikatorDeskripsi]"
                                                        value="{{ $indkts->indikatorDeskripsi }}">
                                                    <input type="hidden" class="form-control"
                                                        name="indikators[{{ $loop->index }}][target]"
                                                        value="{{ $indkts->target }}">
                                                    <input type="hidden" class="form-control"
                                                        name="indikators[{{ $loop->index }}][pencapaianLPJ]"
                                                        value="{{ $indkts->pencapaianLPJ }}">
                                                    <input type="hidden" class="form-control"
                                                        name="indikators[{{ $loop->index }}][id]"
                                                        value="{{ $indkts->indikatorLpjID }}">
                                                    <input type="hidden" class="form-control"
                                                        name="indikators[{{ $loop->index }}][isDeleted]" value="false">
                                                </div>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                                <div class="text-center">
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop"><i class="fas fa-plus"></i> Tambah
                                        Data</button>
                                </div>

                            </div>
                        </div>
                        @error('columns')
                            <span class="text-danger"> {{ $message }} </span>
                        @enderror

                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <label for="rincian">Rencana dan Pelaksanaan</label>
                                </div>
                                <rincian-lpj-component
                                    :list-of-rincian="{{ json_encode($rincians) ?? json_encode(old('rincians') ?? []) }}"
                                    :old="{{ json_encode(old('rincians')) }}"></rincian-lpj-component>
                            </div>
                            @error('columns')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <div class="form-group mb-0">
                                <label for="status">Status</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="approvedbyRomo" class="form-check-input" value="Disetujui"
                                    id="approvedbyRomo" @if ((old('approvedbyRomo') ?? ($lpj->approvedbyRomo ?? '')) == 'Disetujui') checked @endif>
                                <label for="approvedbyRomo" class="form-check-label">Disetujui Romo</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="approvedbyKabid" class="form-check-input" value="Disetujui"
                                    id="approvedbyKabid" @if ((old('approvedbyKabid') ?? ($lpj->approvedbyKabid ?? '')) == 'Disetujui') checked @endif>
                                <label for="approvedbyKabid" class="form-check-label">Disetujui Kabid</label>
                            </div>
                            @error('status')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="button" onclick="window.history.back()"
                                class="btn btn-sm btn-secondary">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm">{{ $button }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    @if (isset($lpj))
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
                        <form action="{{ route('dashboard.lpj.delete', $lpj->lpjID) }}" method="post">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Delete</button>
                        </form>
                        <button data-dismiss="modal" class="btn btn-secondary btn-sm"><i class="fas fa-sign-out"></i>
                            Keluar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Indikator Target</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="indikatorDeskripsi" class="col-form-label">Indikator Deskripsi:</label>
                            <input type="text" class="form-control" id="indikatorDeskripsi"
                                placeholder="Masukkan Indikator Deskripsi">
                        </div>

                        <div class="form-group">
                            <label for="target" class="col-form-label">Target :</label>
                            <input type="text" class="form-control" id="target" placeholder="Masukkan Target">
                        </div>

                        <div class="form-group">
                            <label for="pencapaianLPJ" class="col-form-label">Pencapaian LPJ:</label>
                            <input type="text" class="form-control" id="pencapaianLPJ"
                                placeholder="Masukkan Pencapaian LPJ">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="submitIndikator" type="button" class="btn btn-primary" data-bs-dismiss="modal"
                        onclick="addIndikator()">Submit</button>
                </div>
            </div>
        </div>
    @endsection

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script>
        let proposal = <?php echo json_encode($pro); ?>;
        let lpj = <?php echo isset($lpj) ? json_encode($lpj) : json_encode('kosong'); ?>;
        let indikatorJS = <?php echo json_encode($indikators); ?>;

        function tandaPemisahTitik(b) {
            var _minus = false;
            if (b < 0) _minus = true;
            b = b.toString();
            b = b.replace(".", "");
            b = b.replace("-", "");
            c = "";
            panjang = b.length;
            j = 0;
            for (i = panjang; i > 0; i--) {
                j = j + 1;
                if (((j % 3) == 1) && (j != 1)) {
                    c = b.substr(i - 1, 1) + "." + c;
                } else {
                    c = b.substr(i - 1, 1) + c;
                }
            }
            if (_minus) c = "-" + c;
            return c;
        }

        function addIndikator() {
            $.each(indikatorJS, function(index, data) {

                $('#inputIndikatorHidden').empty();
                $('#dataindikator').empty();

                $('#inputIndikatorHidden').append(
                    '<' +
                    'input type = "hidden"' +
                    'class = "form-control"' +
                    'name = "indikators[' + index + '][indikatorDeskripsi]"' +
                    'value = "' + data.indikatorDeskripsi + '" >' +
                    '<' +
                    'input type = "hidden"' +
                    'class = "form-control"' +
                    'name = "indikators[' + index + '][target]"' +
                    'value = "' + data.target + '" >' +
                    '<' +
                    'input type = "hidden"' +
                    'class = "form-control"' +
                    'name = "indikators[' + index + '][pencapaianLPJ]"' +
                    'value = "' + data.pencapaianLPJ + '" >' +
                    '<' +
                    'input type = "hidden"' +
                    'class = "form-control"' +
                    'name = "indikators[' + index + '][id]"' +
                    'value = "' + data.indikatorLpjID + '" >' +
                    '<' +
                    'input type = "hidden"' +
                    'class = "form-control"' +
                    'name = "indikators[' + index + '][isDeleted]"' +
                    'value = "false" >'
                );

                $('#dataindikator').append(
                    '<td> ' + index + 1 + '</td>' +
                    '<td> ' + data.indikatorDeskripsi + ' </td>' +
                    '<td> ' + data.target + ' </td>' +
                    '<td> ' + data.pencapaianLPJ + ' </td>' +
                    '<td>' +
                    '<button type = "button" class = "btn btn-outline-success btn-sm" data-toggle = "modal" data-target = "#indikatorModal" > < i class = "fas fa-edit" > < /i></button>' +
                    '<button type = "button" class = "btn btn-outline-danger btn-sm" > < i class = "fas fa-times" > < /i></button>' +
                    '</td>'
                );


            });

            // $('#inputIndikatorHidden').append(
            //     '<' +
            //     'input type = "hidden"' +
            //     'class = "form-control"' +
            //     'name = "indikators[' + indikatorJS.length + '][indikatorDeskripsi]"' +
            //     'value = "' + $('#indikatorDeskripsi').val() + '" >' +
            //     '<' +
            //     'input type = "hidden"' +
            //     'class = "form-control"' +
            //     'name = "indikators[' + indikatorJS.length + '][target]"' +
            //     'value = "' + data.target + '" >' +
            //     '<' +
            //     'input type = "hidden"' +
            //     'class = "form-control"' +
            //     'name = "indikators[' + indikatorJS.length + '][pencapaianLPJ]"' +
            //     'value = "' + data.pencapaianLPJ + '" >' +
            //     '<' +
            //     'input type = "hidden"' +
            //     'class = "form-control"' +
            //     'name = "indikators[' + indikatorJS.length + '][id]"' +
            //     'value = "' + data.indikatorLpjID + '" >' +
            //     '<' +
            //     'input type = "hidden"' +
            //     'class = "form-control"' +
            //     'name = "indikators[' + indikatorJS.length + '][isDeleted]"' +
            //     'value = "false" >' +
            // );


        }

        $(document).ready(function() {
            if (lpj != 'kosong') {
                $.each(proposal, function(index, data) {
                    if (data.proposalID == lpj.proposalID) {
                        $('#proposal-select').append('<option value="' + data.proposalID + '" selected>' +
                            data.namaKegiatan + '</option>');
                        $("#saldo-pengeluaran").val(tandaPemisahTitik(data.totalBiaya));
                        $("#pengeluaran").val(data.totalBiaya);
                    }
                });
            } else {
                $('#proposal-select').append('<option value="">Pilih Proposal</option>');
                $.each(proposal, function(index, data) {
                    $('#proposal-select').append('<option value="' + data.proposalID + '">' + data
                        .namaKegiatan + '</option>');
                });

            }

            $("#proposal-select").change(function() {
                proposal.forEach(prop => {
                    if ($("#proposal-select").val() == prop.proposalID) {
                        $("#saldo-pengeluaran").val(tandaPemisahTitik(prop.totalBiaya));
                        $("#pengeluaran").val(prop.totalBiaya);
                    }
                });
            });





        });
    </script>
