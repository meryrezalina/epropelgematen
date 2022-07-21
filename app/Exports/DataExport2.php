<?php

namespace App\Exports;

use App\Models\Propel;
use App\Models\Anggaran;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class DataExport2 implements FromView
{
    protected $propel;

    public function __construct($propelID)
    {
        $this->propel = Propel::find($propelID);
        // $this->anggaranPropelID = $anggaranPropelID;
        
    }

    public function view(): View{
        $results = [];
        // dd($this->propel->anggaran);
        foreach($this->propel->anggaran as $anggaran){
            $temp = [];
            $temp["propelID"] = $this->propel->propelID;
            $temp["namaBidang"] = $this->propel->bidang->namaBidang;
            $temp["namaTimpel"] = $this->propel->timpel->namaTimpel;
            $temp["namaKegiatan"] = $this->propel->namaKegiatan;
            $temp["nomorRAPB"] = $this->propel->nomorRAPB;
            $temp["namaPJ"] = $this->propel->namaPJ;
            $temp["sasaranStrategis"] = $this->propel->sasaranStrategis;
            $temp["status"] = $this->propel->status;
            $temp["created_at"] = $this->propel->created_at;
            $temp["updated_at"] = $this->propel->updated_at;

            $temp["anggaranPropelID"] = $anggaran->anggaranPropelID;
            $temp["anggaranDeskripsi"] = $anggaran->anggaranDeskripsi;
            $temp["hargaSatuan"] = $anggaran->hargaSatuan;
            $temp["kuantitas"] = $anggaran->kuantitas;
            $temp["sumberID"] = $anggaran->sumberdana->sumberDeskripsi;

            $temp["indikatorID"] = "";
            $temp["indikatorDeskripsi"] = "";
            $temp["target"] = "";
            $temp["pencapaianLPJ"] = "";

            $temp["rincianID"] = "";
            $temp["rincianDeskripsi"] = "";
            $temp["tempat"] = "";
            $temp["waktuMulai"] = "";
            $temp["waktuSelesai"] = "";
            
            
            $results[] = $temp;
        }

        foreach($this->propel->indikatorTarget as $indikator){
            $temp = [];
            $temp["propelID"] = $this->propel->propelID;
            $temp["namaBidang"] = $this->propel->bidang->namaBidang;
            $temp["namaTimpel"] = $this->propel->timpel->namaTimpel;
            $temp["namaKegiatan"] = $this->propel->namaKegiatan;
            $temp["nomorRAPB"] = $this->propel->nomorRAPB;
            $temp["namaPJ"] = $this->propel->namaPJ;
            $temp["sasaranStrategis"] = $this->propel->sasaranStrategis;
            $temp["status"] = $this->propel->status;
            $temp["created_at"] = $this->propel->created_at;
            $temp["updated_at"] = $this->propel->updated_at;

            $temp["anggaranPropelID"] = "";
            $temp["anggaranDeskripsi"] = "";
            $temp["hargaSatuan"] = "";
            $temp["kuantitas"] = "";
            $temp["sumberID"] = "";

            $temp["indikatorID"] = $indikator->indikatorID;
            $temp["indikatorDeskripsi"] = $indikator->indikatorDeskripsi;
            $temp["target"] = $indikator->target;
            $temp["pencapaianLPJ"] = $indikator->pencapaianLPJ;

            $temp["rincianID"] = "";
            $temp["rincianDeskripsi"] = "";
            $temp["tempat"] = "";
            $temp["waktuMulai"] = "";
            $temp["waktuSelesai"] = "";
            $results[] = $temp;
        }

        foreach($this->propel->rincianKegiatan as $rincian){
            $temp = [];
            $temp["propelID"] = $this->propel->propelID;
            $temp["namaBidang"] = $this->propel->bidang->namaBidang;
            $temp["namaTimpel"] = $this->propel->timpel->namaTimpel;
            $temp["namaKegiatan"] = $this->propel->namaKegiatan;
            $temp["nomorRAPB"] = $this->propel->nomorRAPB;
            $temp["namaPJ"] = $this->propel->namaPJ;
            $temp["sasaranStrategis"] = $this->propel->sasaranStrategis;
            $temp["status"] = $this->propel->status;
            $temp["created_at"] = $this->propel->created_at;
            $temp["updated_at"] = $this->propel->updated_at;

            $temp["anggaranPropelID"] = "";
            $temp["anggaranDeskripsi"] = "";
            $temp["hargaSatuan"] = "";
            $temp["kuantitas"] = "";
            $temp["sumberID"] = "";

            $temp["indikatorID"] = "";
            $temp["indikatorDeskripsi"] = "";
            $temp["target"] = "";
            $temp["pencapaianLPJ"] = "";

            $temp["rincianID"] = $rincian->rincianId;
            $temp["rincianDeskripsi"] = $rincian->rincianDeskripsi;
            $temp["tempat"] = $rincian->tempat;
            $temp["waktuMulai"] = $rincian->waktuMulai;
            $temp["waktuSelesai"] = $rincian->waktuSelesai;
            $results[] = $temp;
        }

        return view('exports.excel',[
            'results'=> $results
        ]);
    }
}
