<?php

namespace App\Exports;

use App\Models\Proposal;
use App\Models\Anggaran;
use App\Exports\ProposalList;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProposalList implements FromView
{
    protected $proposals;

    public function __construct($proposalID)
    {
        $this->proposal = Proposal::find($proposalID);
        // $this->anggaranproposalID = $anggaranproposalID;
        
    }

    public function view(): View{
        // dd($this->proposal->anggaranProposal[0]->satuan);
        $results = [];
        foreach($this->proposal->anggaranProposal as $anggaran){
            $temp = [];
            $temp["proposalID"] = $this->proposal->proposalID;
            $temp["namaBidang"] = $this->proposal->bidang->namaBidang;
            $temp["namaTimpel"] = $this->proposal->timpel->namaTimpel;
            $temp["jenisProposal"] = $this->proposal->jenisProposal;
            $temp["namaKegiatan"] = $this->proposal->namaKegiatan;
            $temp["nomorRAPB"] = $this->proposal->nomorRAPB;
            $temp["namaPJ"] = $this->proposal->namaPJ;
            $temp["no_hp"] = $this->proposal->no_hp;
            $temp["sasaranStrategis"] = $this->proposal->sasaranStrategis;
            $temp["totalBiaya"] = $this->proposal->totalBiaya;
            $temp["romoApprover"] = $this->proposal->romoApprover;
            $temp["kabidApprover"] = $this->proposal->kabidApprover;
            $temp["status"] = $this->proposal->status;
            $temp["created_at"] = $this->proposal->created_at;
            $temp["updated_at"] = $this->proposal->updated_at;

            $temp["anggaranID"] = $anggaran->anggaranID;
            $temp["anggaranDeskripsi"] = $anggaran->anggaranDeskripsi;
            $temp["hargaSatuan"] = $anggaran->hargaSatuan;
            $temp["kuantitas"] = $anggaran->kuantitas;
            $temp["satuan"] = $anggaran->satuan;
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

        foreach($this->proposal->indikatorTargetProposal as $indikator){
            $temp = [];
            $temp["proposalID"] = $this->proposal->proposalID;
            $temp["namaBidang"] = $this->proposal->bidang->namaBidang;
            $temp["namaTimpel"] = $this->proposal->timpel->namaTimpel;
            $temp["jenisProposal"] = $this->proposal->jenisProposal;
            $temp["namaKegiatan"] = $this->proposal->namaKegiatan;
            $temp["nomorRAPB"] = $this->proposal->nomorRAPB;
            $temp["namaPJ"] = $this->proposal->namaPJ;
            $temp["no_hp"] = $this->proposal->no_hp;
            $temp["sasaranStrategis"] = $this->proposal->sasaranStrategis;
            $temp["totalBiaya"] = $this->proposal->totalBiaya;
            $temp["romoApprover"] = $this->proposal->romoApprover;
            $temp["kabidApprover"] = $this->proposal->kabidApprover;
            $temp["status"] = $this->proposal->status;
            $temp["created_at"] = $this->proposal->created_at;
            $temp["updated_at"] = $this->proposal->updated_at;

            $temp["anggaranID"] = "";
            $temp["anggaranDeskripsi"] = "";
            $temp["hargaSatuan"] = "";
            $temp["kuantitas"] = "";
            $temp["satuan"] = $anggaran->satuan;
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

        foreach($this->proposal->rincianKegiatanProposal as $rincian){
            $temp = [];
            $temp["proposalID"] = $this->proposal->proposalID;
            $temp["namaBidang"] = $this->proposal->bidang->namaBidang;
            $temp["namaTimpel"] = $this->proposal->timpel->namaTimpel;
            $temp["jenisProposal"] = $this->proposal->jenisProposal;
            $temp["namaKegiatan"] = $this->proposal->namaKegiatan;
            $temp["nomorRAPB"] = $this->proposal->nomorRAPB;
            $temp["namaPJ"] = $this->proposal->namaPJ;
            $temp["no_hp"] = $this->proposal->no_hp;
            $temp["sasaranStrategis"] = $this->proposal->sasaranStrategis;
            $temp["totalBiaya"] = $this->proposal->totalBiaya;
            $temp["romoApprover"] = $this->proposal->romoApprover;
            $temp["kabidApprover"] = $this->proposal->kabidApprover;
            $temp["status"] = $this->proposal->status;
            $temp["created_at"] = $this->proposal->created_at;
            $temp["updated_at"] = $this->proposal->updated_at;

            $temp["anggaranID"] = "";
            $temp["anggaranDeskripsi"] = "";
            $temp["hargaSatuan"] = "";
            $temp["kuantitas"] = "";
            $temp["satuan"] = $anggaran->satuan;
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

        

        return view('exports.proposal',[
            'results'=> $results
        ]);
    }
}
