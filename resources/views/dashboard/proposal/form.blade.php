@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-8 align-self-center">
                    <h3>E-Proposal</h3>
                </div>

                @if (Request::path() == 'dashboard/proposal/create')
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
                    <form method="post" action="{{ route($url, $proposals->proposalID ?? '') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @if (isset($proposals))
                            @method('put')
                        @endif
                        @php
                            if (isset($proposals)) {
                                $optionBidang = $proposals->bidangID;
                                $optionTimpel = $proposals->timpelID;
                            } else {
                                $optionBidang = old('bidangID');
                                $optionTimpel = old('timpelID');
                            }
                        @endphp
                        {{-- {{ $bid->bidangID }} --}}
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
                                @foreach ($tim as $timpel)
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
                            <label for="jenisProposal">Jenis Proposal</label>
                            <input type="text" class="form-control @error('jenisProposal') {{ 'is-invalid' }} @enderror"
                                name="jenisProposal" placeholder="Masukkan Jenis Proposal"
                                value="{{ old('jenisProposal') ?? ($proposals->jenisProposal ?? '') }}">
                            @error('jenisProposal')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="namaKegiatan">Nama Kegiatan</label>
                            <input type="text" class="form-control @error('namaKegiatan') {{ 'is-invalid' }} @enderror"
                                name="namaKegiatan" placeholder="Masukkan Nama Kegiatan"
                                value="{{ old('namaKegiatan') ?? ($proposals->namaKegiatan ?? '') }}">
                            @error('namaKegiatan')
                                <span class="text-danger"> Nama kegiatan tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nomorRAPB">Nomor RAPB</label>
                            <input type="text" class="form-control @error('nomorRAPB') {{ 'is-invalid' }} @enderror"
                                name="nomorRAPB" placeholder="Masukkan Nomor RAPB"
                                value="{{ old('nomorRAPB') ?? ($proposals->nomorRAPB ?? '') }}">
                            @error('nomorRAPB')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="namaPJ">Nama Penanggung Jawab</label>
                            <input type="text" class="form-control @error('namaPJ') {{ 'is-invalid' }} @enderror"
                                name="namaPJ" placeholder="Masukkan Nama Penanggung Jawab"
                                value="{{ old('namaPJ') ?? ($proposals->namaPJ ?? '') }}">
                            @error('namaPJ')
                                <span class="text-danger"> Nama Penanggung Jawab tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="no_hp">Nomor HP</label>
                            <input type="number" class="form-control @error('no_hp') {{ 'is-invalid' }} @enderror"
                                name="no_hp" placeholder="Masukkan Nomor HP"
                                value="{{ old('no_hp') ?? ($proposals->no_hp ?? '') }}">
                            @error('no_hp')
                                <span class="text-danger"> Nomor HP tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sasaranStrategis">Sasaran Strategis</label>
                            <input type="text"
                                class="form-control @error('sasaranStrategis') {{ 'is-invalid' }} @enderror"
                                name="sasaranStrategis" placeholder="Masukkan Sasaran Strategis"
                                value="{{ old('sasaranStrategis') ?? ($proposals->sasaranStrategis ?? '') }}">
                            @error('sasaranStrategis')
                                <span class="text-danger"> Sasaran Strategis tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <label for="anggaran">Anggaran</label>
                                </div>
                                @if (isset($proposals))
                                    <anggaran-proposal-component :sumber-dana="{{ $sum ?? json_encode(old('sum') ?? []) }}"
                                        :list-of-anggaran="{{ json_encode($anggarans) ?? json_encode(old('anggarans') ?? []) }}"
                                        :jenis-data="{{ json_encode('edit') }}"></anggaran-proposal-component>
                                @else
                                    <anggaran-proposal-component :sumber-dana="{{ $sum ?? json_encode(old('sum') ?? []) }}"
                                        :list-of-anggaran="{{ json_encode($anggarans) ?? json_encode(old('anggarans') ?? []) }}"
                                        :jenis-data="{{ json_encode('tambah') }}"></anggaran-proposal-component>
                                @endif
                            </div>
                            @error('columns')
                                <span class="text-danger"> Anggaran tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="totalBiaya">Total Biaya</label>
                            <input type="text"
                                class="total-biaya form-control @error('totalBiaya') {{ 'is-invalid' }} @enderror"
                                name="totalBiaya" id="totalBiaya"
                                value="{{ old('totalBiaya') ?? ($proposals->totalBiaya ?? '') }}"
                                onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                            @error('totalBiaya')
                                <span class="text-danger"> Total Biaya tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <label for="indikator">Indikator Target</label>
                                </div>
                                {{-- <indikator-proposal-component
                                    :list-of-indikator="{{ json_encode($indikators) ?? json_encode(old('indikators') ?? []) }}">
                                </indikator-proposal-component> --}}

                                @if (isset($proposals))
                                    <indikator-proposal-component
                                        :list-of-indikator="{{ json_encode($indikators) ?? json_encode(old('indikators') ?? []) }}"
                                        :jenis-data="{{ json_encode('edit') }}"></indikator-proposal-component>
                                @else
                                    <indikator-proposal-component
                                        :list-of-indikator="{{ json_encode($indikators) ?? json_encode(old('indikators') ?? []) }}"
                                        :jenis-data="{{ json_encode('tambah') }}"></indikator-proposal-component>
                                @endif
                            </div>
                            @error('columns')
                                <span class="text-danger"> Indikator Target tidak boleh kosong </span>
                            @enderror
                        </div>

                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <label for="rincian">Rencana Pelaksanaan</label>
                                </div>
                                {{-- <rincian-proposal-component
                                    :list-of-rincian="{{ json_encode($rincians) ?? json_encode(old('rincians') ?? []) }}">
                                </rincian-proposal-component> --}}

                                @if (isset($proposals))
                                    <rincian-proposal-component
                                        :list-of-rincian="{{ json_encode($rincians) ?? json_encode(old('rincians') ?? []) }}"
                                        :jenis-data="{{ json_encode('edit') }}"></rincian-proposal-component>
                                @else
                                    <rincian-proposal-component
                                        :list-of-rincian="{{ json_encode($rincians) ?? json_encode(old('rincians') ?? []) }}"
                                        :jenis-data="{{ json_encode('tambah') }}"></rincian-proposal-component>
                                @endif

                            </div>
                            @error('columns')
                                <span class="text-danger"> Rencana Pelaksanaan tidak boleh kosong </span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="romoApprover">Disetujui oleh Romo</label>
                            <select name="romoApprover" class="form-control" id="romoApprover">
                                <option value="">Pilih Romo</option>
                                <option value="Rm. Gregorius Kriswanta, Pr.">Rm. Gregorius Kriswanta, Pr.</option>
                                <option value="Rm. Andreas Krishna Gunawan, Pr.">Rm. Andreas Krishna Gunawan, Pr.</option>
                                <option value="Rm. Robertus Yuni Tri Wibowo, Pr">Rm. Robertus Yuni Tri Wibowo, Pr</option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kabidApprover">Disetujui oleh Kepala Bidang</label>
                            <input type="text"
                                class="form-control @error('kabidApprover') {{ 'is-invalid' }} @enderror"
                                name="kabidApprover" placeholder="Masukkan Nama Kabid"
                                value="{{ old('kabidApprover') ?? ($proposals->kabidApprover ?? '') }}">
                            @error('kabidApprover')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="approvedbyRomo" class="form-check-input" value="Disetujui"
                                    id="approvedbyRomo" @if ((old('approvedbyRomo') ?? ($proposals->approvedbyRomo ?? '')) == 'Disetujui') checked @endif>
                                <label for="approvedbyRomo" class="form-check-label">Disetujui Romo</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="approvedbyKabid" class="form-check-input" value="Disetujui"
                                    id="approvedbyKabid" @if ((old('approvedbyKabid') ?? ($proposals->approvedbyKabid ?? '')) == 'Disetujui') checked @endif>
                                <label for="approvedbyKabid" class="form-check-label">Disetujui Kabid</label>
                            </div>
                            @error('status')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>

                        {{-- <div class="form-group">
                            <label for="tanggalUpdate">Tanggal</label>
                            <input type="date" class="form-control @error('tanggalUpdate') {{ 'is-invalid' }} @enderror"
                                name="tanggalUpdate"
                                value="{{ old('tanggalUpdate') ?? $proposals->tanggalUpdate ?? '' }}">
                            @error('tanggalUpdate')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div> --}}

                        <div class="mb-2">
                            <div class="form-group mb-0">
                                <label for="status">Status</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" name="status" class="form-check-input" value="Disetujui"
                                    id="Disetujui" @if ((old('status') ?? ($proposals->status ?? '')) == 'Disetujui') checked @endif>
                                <label for="Disetujui" class="form-check-label">Disetujui</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input type="radio" name="status" class="form-check-input" value="Belum Disetujui"
                                    id="Belum Disetujui" @if ((old('status') ?? ($proposals->status ?? '')) == 'Belum Disetujui') checked @endif>
                                <label for="Belum Disetujui" class="form-check-label">Belum Disetujui</label>
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
    @if (isset($proposals))
        <div class="modal fade" id="deleteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Hapus</h5>
                    </div>

                    <div class="modal-body">
                        <p>Anda yakin ingin menghapus data ini?</p>
                    </div>

                    <div class="modal-footer">
                        <form action="{{ route('dashboard.proposal.delete', $proposals->proposalID) }}" method="post">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>
                        </form>
                        <button data-dismiss="modal" class="btn btn-secondary btn-sm"><i class="fas fa-sign-out"></i>
                            Keluar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- SCRIPT --}}
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="simple.money.format.js"></script>
    <script src="style.js"></script>

    <script>
        // SCRIPT SET TIPMPEL ON BIDANG
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

        // SCRIPT FORMAT NUMBER (DIGIT)
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

        function numbersonly(ini, e) {
            if (e.keyCode >= 49) {
                if (e.keyCode <= 57) {
                    a = ini.value.toString().replace(".", "");
                    b = a.replace(/[^\d]/g, "");
                    b = (b == "0") ? String.fromCharCode(e.keyCode) : b + String.fromCharCode(e.keyCode);
                    ini.value = tandaPemisahTitik(b);
                    return false;
                } else if (e.keyCode <= 105) {
                    if (e.keyCode >= 96) {
                        //e.keycode = e.keycode - 47;
                        a = ini.value.toString().replace(".", "");
                        b = a.replace(/[^\d]/g, "");
                        b = (b == "0") ? String.fromCharCode(e.keyCode - 48) : b + String.fromCharCode(e.keyCode - 48);
                        ini.value = tandaPemisahTitik(b);
                        //alert(e.keycode);
                        return false;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else if (e.keyCode == 48) {
                a = ini.value.replace(".", "") + String.fromCharCode(e.keyCode);
                b = a.replace(/[^\d]/g, "");
                if (parseFloat(b) != 0) {
                    ini.value = tandaPemisahTitik(b);
                    return false;
                } else {
                    return false;
                }
            } else if (e.keyCode == 95) {
                a = ini.value.replace(".", "") + String.fromCharCode(e.keyCode - 48);
                b = a.replace(/[^\d]/g, "");
                if (parseFloat(b) != 0) {
                    ini.value = tandaPemisahTitik(b);
                    return false;
                } else {
                    return false;
                }
            } else if (e.keyCode == 8 || e.keycode == 46) {
                a = ini.value.replace(".", "");
                b = a.replace(/[^\d]/g, "");
                b = b.substr(0, b.length - 1);
                if (tandaPemisahTitik(b) != "") {
                    ini.value = tandaPemisahTitik(b);
                } else {
                    ini.value = "";
                }

                return false;
            } else if (e.keyCode == 9) {
                return true;
            } else if (e.keyCode == 17) {
                return true;
            } else {
                //alert (e.keyCode);
                return false;
            }

        }
    </script>
@endsection
