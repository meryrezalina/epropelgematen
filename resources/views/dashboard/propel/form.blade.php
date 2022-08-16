@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-8 align-self-center">
                    <h3>E-Propel</h3>
                </div>

                @if (Request::path() == 'dashboard/propel/create')
                @else
                    <div class="col-4 text-right">
                        <button class=" btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-body ">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form method="post" action="{{ route($url, $propel->propelID ?? '') }}" enctype="multipart/form-data">
                        @csrf
                        @if (isset($propel))
                            @method('put')
                        @endif
                        @php
                            if (isset($propel)) {
                                $optionBidang = $propel->bidangID;
                                $optionTimpel = $propel->timpelID;
                            } else {
                                $optionBidang = old('bidangID');
                                $optionTimpel = old('timpelID');
                            }
                        @endphp
                        <div class="form-group">
                            <label for="bidang">Bidang</label>

                            <select name="bidangID" class="form-control" name="bidangID" id="bidangID"
                                onchange="setTimpel()">
                                <option value="">Pilih Bidang</option>
                                @foreach ($bid as $bidang)
                                    <option value="{{ $bidang->bidangID }}"
                                        @if ($bidang->bidangID == $optionBidang) selected="selected" @endif>
                                        {{ $bidang->namaBidang }} </option>
                                @endforeach
                            </select>
                            @error('bidangID')
                                <span class="text-danger"> Bidang tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="timpel">Tim Pelayanan</label>
                            <select name="timpelID" class="form-control" name="timpelID" id="timpelID">
                                <option value="">Pilih Timpel</option>
                                @foreach ($timByBidang as $timpel)
                                    <option value="{{ $timpel->timpelID }}"
                                        @if ($timpel->timpelID == $optionTimpel) selected = "selected" @endif>
                                        {{ $timpel->namaTimpel }} </option>
                                @endforeach
                            </select>
                            @error('timpelID')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="namaKegiatan">Nama Kegiatan</label>
                            <input type="text" class="form-control @error('namaKegiatan') {{ 'is-invalid' }} @enderror"
                                name="namaKegiatan" placeholder="Masukkan Nama Kegiatan"
                                value="{{ old('namaKegiatan') ?? ($propel->namaKegiatan ?? '') }}">
                            @error('namaKegiatan')
                                <span class="text-danger"> Nama kegiatan tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nomorRAPB">Nomor RAPB</label>
                            <input type="text" class="form-control @error('nomorRAPB') {{ 'is-invalid' }} @enderror"
                                name="nomorRAPB" placeholder="Massukan Nomor RAPB"
                                value="{{ old('nomorRAPB') ?? ($propel->nomorRAPB ?? '') }}">
                            @error('nomorRAPB')
                                <span class="text-danger"> Nomor RAPB tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="namaPJ">Nama Penanggung Jawab</label>
                            <input type="text" class="form-control @error('namaPJ') {{ 'is-invalid' }} @enderror"
                                name="namaPJ" placeholder="Masukkan Nama Penanggung Jawab"
                                value="{{ old('namaPJ') ?? ($propel->namaPJ ?? '') }}">
                            @error('namaPJ')
                                <span class="text-danger"> Nama penanggung jawab tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sasaranStrategis">Sasaran Strategis</label>
                            <input type="text"
                                class="form-control @error('sasaranStrategis') {{ 'is-invalid' }} @enderror"
                                name="sasaranStrategis" placeholder="Masukkan Sasaran Strategis"
                                value="{{ old('sasaranStrategis') ?? ($propel->sasaranStrategis ?? '') }}">
                            @error('sasaranStrategis')
                                <span class="text-danger"> Sasaran strategis tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <label for="anggaran">Anggaran</label>
                                </div>
                                @if (isset($propel))
                                    <anggaran-coba-component :sumber-dana="{{ $sum ?? json_encode(old('sum') ?? []) }}"
                                        :list-of-anggaran="{{ json_encode($anggarans) ?? json_encode(old('anggarans') ?? []) }}"
                                        :jenis-data="{{ json_encode('edit') }}"></anggaran-coba-component>
                                @else
                                    <anggaran-coba-component :sumber-dana="{{ $sum ?? json_encode(old('sum') ?? []) }}"
                                        :list-of-anggaran="{{ json_encode($anggarans) ?? json_encode(old('anggarans') ?? []) }}"
                                        :jenis-data="{{ json_encode('tambah') }}"></anggaran-coba-component>
                                @endif

                            </div>
                            @error('anggarans')
                                <span class="text-danger"> Anggaran tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <label for="indikator">Indikator Target</label>
                                </div>

                                @if (isset($propel))
                                    <indikator-component
                                        :list-of-indikator="{{ json_encode($indikators) ?? json_encode(old('indikators') ?? []) }}"
                                        :jenis-data="{{ json_encode('edit') }}"></indikator-component>
                                @else
                                    <indikator-component
                                        :list-of-indikator="{{ json_encode($indikators) ?? json_encode(old('indikators') ?? []) }}"
                                        :jenis-data="{{ json_encode('tambah') }}"></indikator-component>
                                @endif
                            </div>
                            @error('indikators')
                                <span class="text-danger"> Indikator Target tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <label for="rincian">Rencana dan Pelaksanaan</label>
                                </div>
                                {{-- <rincian-component
                                    :list-of-rincian="{{ json_encode($rincians) ?? json_encode(old('rincians') ?? []) }}">
                                </rincian-component> --}}

                                @if (isset($propel))
                                    <rincian-component
                                        :list-of-indikator="{{ json_encode($rincians) ?? json_encode(old('rincians') ?? []) }}"
                                        :jenis-data="{{ json_encode('edit') }}"></rincian-component>
                                @else
                                    <rincian-component
                                        :list-of-rincian="{{ json_encode($rincians) ?? json_encode(old('rincians') ?? []) }}"
                                        :jenis-data="{{ json_encode('tambah') }}"></rincian-component>
                                @endif
                            </div>
                            @error('rincians')
                                <span class="text-danger"> Rencana dan Pelaksanaan tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <div class="form-group mb-0">
                                <label for="status">Status</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="radio" name="status" class="form-check-input" value="Ditunda" id="Ditunda"
                                    @if ((old('status') ?? ($propel->status ?? '')) == 'Ditunda') checked @endif>
                                <label for="Ditunda" class="form-check-label">Ditunda</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="radio" name="status" class="form-check-input" value="Review Ulang"
                                    id="Review Ulang" @if ((old('status') ?? ($propel->status ?? '')) == 'Review Ulang') checked @endif>
                                <label for="Review Ulang" class="form-check-label">Review Ulang</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="radio" name="status" class="form-check-input" value="Disetujui"
                                    id="Disetujui" @if ((old('status') ?? ($propel->status ?? '')) == 'Disetujui') checked @endif>
                                <label for="Disetujui" class="form-check-label">Disetujui</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="radio" name="status" class="form-check-input" value="Proposal"
                                    id="Proposal" @if ((old('status') ?? ($propel->status ?? '')) == 'Proposal') checked @endif>
                                <label for="Proposal" class="form-check-label">Proposal</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="radio" name="status" class="form-check-input" value="LPJ"
                                    id="LPJ" @if ((old('status') ?? ($propel->status ?? '')) == 'LPJ') checked @endif>
                                <label for="LPJ" class="form-check-label">LPJ</label>
                            </div>

                            @error('status')
                                <span class="text-danger"> Status tidak boleh kosong </span>
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
    @if (isset($propel))
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
                        <form action="{{ route('dashboard.propel.delete', $propel->propelID) }}" method="post">
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

    {{-- FILTER BIDANG PER TIMPEL --}}
    <script>
        function setTimpel() {
            var bidangID = document.getElementById("bidangID").value;
            var timpel = <?php echo $tim; ?>;
            // Untuk menyimpan timpelId yang ada di propel
            var currentSelectedTimpelId = <?php echo isset($optionTimpel) ? $optionTimpel : 0; ?>;

            var timpelByBidangID = timpel.filter(function(el) {
                return el.bidangID == bidangID;
            })
            var opt = "<option value = ''>Pilih Timpel</option>";
            var selectedIdx = 0;
            for (let i = 0; i < timpelByBidangID.length; i++) {
                if (timpelByBidangID[i].timpelID == currentSelectedTimpelId) {
                    selectedIdx = i + 1;
                }
                opt = opt + "<option value=" + timpelByBidangID[i].timpelID + ">" + timpelByBidangID[i].namaTimpel +
                    "</option>";
            }
            console.log(opt);
            var timpelObject = document.getElementById("timpelID");
            timpelObject.innerHTML = opt;
            timpelObject.getElementsByTagName('option')[selectedIdx].selected = 'selected';
        }
    </script>
@endsection
