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
                            <option value="{{$proposal->proposalID}}" placeholder="Pilih Film">{{
                                $proposal->namaKegiatan }} </option>
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
                                @if (isset($lpj))
                                    <anggaran-lpj-component :sumber-dana="{{ $sum ?? json_encode(old('sum') ?? []) }}"
                                        :list-of-anggaran="{{ json_encode($anggarans) ?? json_encode(old('anggarans') ?? []) }}"
                                        :jenis-data="{{ json_encode('edit') }}"></anggaran-lpj-component>
                                @else
                                    <anggaran-lpj-component :sumber-dana="{{ $sum ?? json_encode(old('sum') ?? []) }}"
                                        :list-of-anggaran="{{ json_encode($anggarans) ?? json_encode(old('anggarans') ?? []) }}"
                                        :jenis-data="{{ json_encode('tambah') }}"></anggaran-lpj-component>
                                @endif
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
                                    <tbody id="dataIndikator">
                                        @if (isset($lpj))
                                            @foreach ($indikators as $indkts)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $indkts->indikatorDeskripsi }}</td>
                                                    <td>{{ $indkts->target }}</td>
                                                    <td>{{ $indkts->pencapaianLPJ }}</td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-outline-success btn-sm edit-indikator"
                                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                                            data-id="{{ $indkts->indikatorLpjID . '_' . $indkts->indikatorDeskripsi . '_' . $indkts->target . '_' . $indkts->pencapaianLPJ }}"
                                                            value="editIndikator"><i class="fas fa-edit"></i></button>
                                                        <button type="button"
                                                            class="btn btn-outline-danger btn-sm hapus-indikator"
                                                            data-id="{{ $indkts->indikatorLpjID }}"><i
                                                                class="fas fa-times"></i></button>
                                                    </td>

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
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                                {{-- <div class="text-center">
                                <button type="button" class="btn btn-success btn-sm tambah-indikator"
                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop" value="tambahIndikator"><i
                                        class="fas fa-plus"></i> Tambah
                                    Data</button>
                            </div> --}}

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
                                placeholder="Masukkan Indikator Deskripsi" required>
                        </div>

                        <div class="form-group">
                            <label for="target" class="col-form-label">Target :</label>
                            <input type="text" class="form-control" id="target" placeholder="Masukkan Target"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="pencapaianLPJ" class="col-form-label">Pencapaian LPJ:</label>
                            <input type="text" class="form-control" id="pencapaianLPJ"
                                placeholder="Masukkan Pencapaian LPJ" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="submitIndikator" type="button" class="btn btn-primary"
                        data-bs-dismiss="modal">Submit</button>
                </div>
            </div>
        </div>
    @endsection

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/formcreatelpj.js') }}"></script>
    <script src="{{ asset('assets/js/formeditlpj.js') }}"></script>
    <script>
        let proposal = <?php echo json_encode($pro); ?>;
        let lpj = <?php echo isset($lpj) ? json_encode($lpj) : json_encode('kosong'); ?>;
        let indikatorJS = <?php echo json_encode($indikators); ?>;

        let eventIndikator = "tambahIndikator";

        indikatorJS.forEach((item, index, arr) => {
            arr[index]['isDeleted'] = 'false';
        });

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

                        proposalSelect = prop.proposalID;

                        let filterIndikator = indikatorJS.filter(obj => obj.proposalID == prop
                            .proposalID);
                        displayIndikatorCreate(filterIndikator);
                    }
                });
            });

            $('body').on('click', '.edit-indikator', function() {
                let data = $(this).data('id').split("_");

                if (lpj != 'kosong') {
                    let index = editIndikatorEdit(data, indikatorJS);
                    eventIndikator = $('.edit-indikator').val() + "_" + index[0] + "_" + index[1];
                } else {
                    let index = editIndikatorCreate(data, indikatorJS);
                    eventIndikator = $('.edit-indikator').val() + "_" + index[0] + "_" + index[1];
                }
            });

            $('body').on('click', '.hapus-indikator', function() {
                let data = $(this).data('id');

                if (lpj != 'kosong') {
                    if (indikatorJS.length == 1) {
                        alert("Indikator Tidak Boleh Kosong");
                    } else {
                        let index = hapusIndikatorEdit(data, indikatorJS);
                        indikatorJS[index].isDeleted = 'true';
                        displayIndikatorEdit(indikatorJS);
                    }

                } else {
                    let cekDataHapus = indikatorJS.filter(obj => obj.proposalID == proposalSelect);
                    if (cekDataHapus.length == 1) {
                        alert("Indikator Tidak Boleh Kosong");
                    } else {
                        let index = hapusIndikatorCreate(data, indikatorJS);
                        indikatorJS[index].isDeleted = 'true';
                        let filterIndikator = indikatorJS.filter(obj => obj.proposalID == proposalSelect);
                        displayIndikatorCreate(filterIndikator);
                    }



                }
            });

            $("#submitIndikator").click(function() {
                if ($('#indikatorDeskripsi').val() == '' || $('#target').val() == '' || $('#pencapaianLPJ')
                    .val() == '') {
                    alert("Data Tidak Boleh Kosong");
                } else {
                    if (lpj != 'kosong') {
                        const newIndikator = {
                            lpjID: lpj.lpjID,
                            indikatorDeskripsi: $('#indikatorDeskripsi').val(),
                            target: $('#target').val(),
                            pencapaianLPJ: $('#pencapaianLPJ').val(),
                            created_at: '',
                            updated_at: '',
                            isDeleted: 'false'
                        }

                        let indexIndikator = eventIndikator.split("_");
                        newIndikator.indikatorLpjID = indexIndikator[2];
                        indikatorJS[indexIndikator[1]] = newIndikator;

                        console.log(indikatorJS);
                        displayIndikatorEdit(indikatorJS);

                    } else {
                        const newIndikator = {
                            indikatorID: (indikatorJS[indikatorJS.length - 1]['indikatorID'] + 1),
                            proposalID: proposalSelect,
                            indikatorDeskripsi: $('#indikatorDeskripsi').val(),
                            target: $('#target').val(),
                            pencapaianLPJ: $('#pencapaianLPJ').val(),
                            isDeleted: 'false'
                        }

                        let indexIndikator = eventIndikator.split("_");
                        newIndikator.indikatorID = parseInt(indexIndikator[2]);
                        indikatorJS[indexIndikator[1]] = newIndikator;
                        console.log(indikatorJS);

                        let filterIndikator = indikatorJS.filter(obj => obj.proposalID == proposalSelect);
                        displayIndikatorCreate(filterIndikator);
                    }
                }
            });
        });
    </script>
