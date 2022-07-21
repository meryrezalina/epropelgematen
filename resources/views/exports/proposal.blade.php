<table>
    <thead>
        <tr>
            <th>ID Proposal</th>
            <th>Bidang</th>
            <th>Timpel</th>
            <th>Jenis Proposal</th>
            <th>Nama Kegiatan</th>
            <th>NO RAPB</th>
            <th>NAMA PJ</th>
            <th>Nomor HP</th>
            <th>Sasaran Strategis</th>
            <th>Total Biaya</th>
            <th>Disetujui oleh Romo</th>
            <th>Disetujui oleh Kepala Bidang</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>

            <th>ID Anggaran Propel</th>
            <th>Anggaran Deskripsi</th>
            <th>Harga Satuan</th>
            <th>Kuantitas</th>
            <th>Satuan</th>
            <th>Sumber</th>

            <th>ID Indikator</th>
            <th>Indikator Deskripsi</th>
            <th>Target</th>
            <th>Pencapaian LPJ</th>

            <th>ID Rincian</th>
            <th>Tempat</th>
            <th>Waktu Mulai</th>
            <th>Waktu Selesai</th>

        </tr>
    </thead>
    <tbody>
        @foreach($results as $res)
            <tr>
                <td>{{$res["proposalID"]}}</td>
                <td>{{$res["namaBidang"]}}</td>
                <td>{{$res["namaTimpel"]}}</td> 
                <td>{{$res["jenisProposal"]}}</td> 
                <td>{{$res["namaKegiatan"]}}</td> 
                <td>{{$res["nomorRAPB"]}}</td>
                <td>{{$res["namaPJ"]}}</td> 
                <td>{{$res["no_hp"]}}</td> 
                <td>{{$res["sasaranStrategis"]}}</td>
                <td>{{$res["totalBiaya"]}}</td>
                <td>{{$res["romoApprover"]}}</td> 
                <td>{{$res["kabidApprover"]}}</td> 
                <td>{{$res["status"]}}</td>
                <td>{{$res["created_at"]}}</td> 
                <td>{{$res["updated_at"]}}</td>

                <td>{{$res["anggaranID"]}}</td>
                <td>{{$res["anggaranDeskripsi"]}}</td> 
                <td>{{$res["hargaSatuan"]}}</td>
                <td>{{$res["kuantitas"]}}</td>
                <td>{{$res["satuan"]}}</td>
                <td>{{$res["sumberID"]}}</td> 

                <td>{{$res["indikatorID"]}}</td>
                <td>{{$res["indikatorDeskripsi"]}}</td>
                <td>{{$res["target"]}}</td>
                <td>{{$res["pencapaianLPJ"]}}</td>

                <td>{{$res["rincianID"]}}</td>
                <td>{{$res["rincianDeskripsi"]}}</td>
                <td>{{$res["tempat"] }}</td>
                <td>{{$res["waktuMulai"]}}</td>
                <td>{{$res["waktuSelesai"] }}</td>
            </tr>
        @endforeach
            
    </tbody>
</table>