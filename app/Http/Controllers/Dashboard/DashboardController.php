<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Dashboard;
use App\Models\BidangChart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\BidangTimpelDrilldown;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Dashboard  $dashboard
     *
     */
    public function index(Request $request, Dashboard $dashboard)
    {
        //untuk  tampilan utama
        $active = 'Dashboard';
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        // $start_date = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-d-m');
        // $end_date = Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-d-m');
        
        // $proposal = DB::table('proposals')->select('tanggalUpdate')->get();
        // $lpj = DB::table('lpj')->select('tanggalUpdate')->get();

        $active = 'Dashboard';
        // $query = DB::table('proposals')->select()
        //             ->where('tanggalUpdate', '>=', $start_date)
        //             ->where('tanggalUpdate', '<=', $end_date)
        //             ->get();
        // dd($query);
        $count_proposal = DB::table('proposals')->count();
        $count_lpj = DB::table('lpj')->count();
        $count_timpel = DB::table('timpel')->count();
        $count_propel = DB::table('propel')->count();
        
        // SELECT proposals.totalBiaya, lpj.saldo from lpj INNER JOIN proposals ON lpj.proposalID = proposals.proposalID  WHERE proposals.totalBiaya >= lpj.saldo;

        $kpi1 = DB::table('lpj')
                ->join('proposals', 'proposals.proposalID', '=', 'lpj.proposalID')
                ->select('proposals.tanggalUpdate', 'lpj.tanggalUpdate')
                ->whereRaw('lpj.tanggalUpdate <= DATE_ADD(proposals.tanggalUpdate, INTERVAL 30 DAY)')
                ->count();

        //surplus
        $kpi2 = DB::table('lpj')
                ->join('proposals', 'proposals.proposalID' ,'=', 'lpj.proposalID')
                ->select('proposals.totalBiaya', 'lpj.saldo')
                ->whereRaw('proposals.totalBiaya >= lpj.saldo')
                ->count();

        //defisit
        $kpi3 = DB::table('lpj')
                ->join('proposals', 'proposals.proposalID' ,'=', 'lpj.proposalID')
                ->select('proposals.totalBiaya', 'lpj.saldo')
                ->whereRaw('proposals.totalBiaya < lpj.saldo')
                ->count();

        $kpi4 = DB::table('lpj')
                ->join('rinciankegiatanlpj', 'rinciankegiatanlpj.lpjID' ,'=', 'lpj.lpjID')
                ->join('rinciankegiatanproposal', 'rinciankegiatanproposal.proposalID' ,'=', 'lpj.proposalID')
                ->select('rinciankegiatanlpj.waktuMulaiLPJ', 'rinciankegiatanproposal.waktuMulai')
                ->get();

        $totalKPI4 = 0;
            foreach ($kpi4 as $data) {
                $dWaktuMulaiLPJ = date_format(date_create($data->waktuMulaiLPJ),"Y-m");
                $dwaktuMulai = date_format(date_create($data->waktuMulai),"Y-m");
        
                if($dwaktuMulai == $dWaktuMulaiLPJ){
                    $totalKPI4 += 1;
                }
             }
        // SELECT proposals.tanggalUpdate, lpj.tanggalUpdate from lpj INNER JOIN proposals ON lpj.proposalID = proposals.proposalID  WHERE EXTRACT(MONTH FROM lpj.tanggalUpdate) = EXTRACT(MONTH FROM proposals.tanggalUpdate) AND EXTRACT(YEAR FROM lpj.tanggalUpdate) = EXTRACT(YEAR FROM proposals.tanggalUpdate)
        
        // $kpi4 = DB::table('')

        //Data proposal dan lpj perbidang
        $data_proposal_bidang_db = DB::table('proposals')
            ->join('bidang', 'bidang.bidangID', '=', 'proposals.bidangID')
            ->select('bidang.namaBidang', 'proposals.status', DB::raw('count(proposals.namaKegiatan) as jumlah'))
            ->groupBy('bidang.namaBidang', 'proposals.status', 'proposals.bidangID')
            ->orderBy('bidang.namaBidang')
            ->get();
        $data_lpj_bidang_db = DB::table('proposals')
            ->join('lpj', 'lpj.proposalID', '=', 'proposals.proposalID')
            ->join('bidang', 'bidang.bidangID', '=', 'proposals.bidangID')
            ->select('bidang.namaBidang', 'lpj.approvedbyRomo', DB::raw('count(lpj.approvedbyRomo) as jumlah'))
            ->groupBy('bidang.namaBidang', 'lpj.approvedByRomo')
            ->get();
        
        //pie chart status
        // $data_status = DB::table('propel')
        //                 ->select('propel.status', DB::raw('count(propel.status) as y'))
        //                 ->where('propel.status', '=', 'review ulang')
        //                 ->groupBy('propel.status')
        //                 ->get();

        $status_review_ulang = DB::table('propel')
                        ->select('propel.status')
                        ->where('propel.status', '=', 'review ulang')
                        ->count();
        
        $status_ditunda = DB::table('propel')
                        ->select('propel.status')
                        ->where('propel.status', '=', 'ditunda')
                        ->count();
        
        $status_disetujui = DB::table('propel')
                        ->select('propel.status')
                        ->where('propel.status', '=', 'disetujui')
                        ->count();
        
        $status_proposal = DB::table('propel')
                        ->select('propel.status')
                        ->where('propel.status', '=', 'proposal')
                        ->count();

        $status_lpj = DB::table('propel')
                        ->select('propel.status')
                        ->where('propel.status', '=', 'lpj')
                        ->count();
         

        $drilldown_ditunda_db = DB::table('propel')
                            ->join('bidang', 'bidang.bidangID', '=', 'propel.bidangID')
                            ->select('bidang.namaBidang', DB::raw('count(*) as jumlah'))
                            ->where('propel.status', '=', 'ditunda')
                            ->groupby('bidang.namaBidang')
                            ->get();

         $drilldown_review_db = DB::table('propel')
                            ->join('bidang', 'bidang.bidangID', '=', 'propel.bidangID')
                            ->select('bidang.namaBidang', DB::raw('count(*) as jumlah'))
                            ->where('propel.status', '=', 'review ulang')
                            ->groupby('bidang.namaBidang')
                            ->get();

        $drilldown_disetujui_db = DB::table('propel')
                            ->join('bidang', 'bidang.bidangID', '=', 'propel.bidangID')
                            ->select('bidang.namaBidang', DB::raw('count(*) as jumlah'))
                            ->where('propel.status', '=', 'disetujui')
                            ->groupby('bidang.namaBidang')
                            ->get();

        $drilldown_proposal_db = DB::table('propel')
                            ->join('bidang', 'bidang.bidangID', '=', 'propel.bidangID')
                            ->select('bidang.namaBidang', DB::raw('count(*) as jumlah'))
                            ->where('propel.status', '=', 'proposal')
                            ->groupby('bidang.namaBidang')
                            ->get();

        $drilldown_lpj_db = DB::table('propel')
                            ->join('bidang', 'bidang.bidangID', '=', 'propel.bidangID')
                            ->select('bidang.namaBidang', DB::raw('count(*) as jumlah'))
                            ->where('propel.status', '=', 'lpj')
                            ->groupby('bidang.namaBidang')
                            ->get();

        $drilldown_ditunda = array();
        foreach ($drilldown_ditunda_db as $data) {
            array_push($drilldown_ditunda, array($data->namaBidang, $data->jumlah));
        }

        $drilldown_review = array();
        foreach ($drilldown_review_db as $data){
            array_push($drilldown_review, array($data->namaBidang, $data->jumlah));
        }

        $drilldown_disetujui = array();
        foreach($drilldown_disetujui_db as $data){
            array_push($drilldown_disetujui, array($data->namaBidang, $data->jumlah));
        }

        $drilldown_proposal = array();
        foreach($drilldown_proposal_db as $data){
            array_push($drilldown_proposal, array($data->namaBidang, $data->jumlah));
        }

        $drilldown_lpj = array();
        foreach($drilldown_lpj_db as $data){
            array_push($drilldown_lpj, array($data->namaBidang, $data->jumlah));
        }

        
        //tampilan luar drilldown pada data proposal dan lpj per tp
        $data_proposal_per_bidang_db = DB::table('proposals') //--> ambil data proposal perbidang dulu
            ->join('bidang', 'bidang.bidangID', '=', 'proposals.bidangID')
            ->select('bidang.namaBidang', DB::raw('count(bidang.namaBidang) as jumlah'))
            ->groupBy('bidang.namaBidang')
            ->orderBy('bidang.namaBidang')
            ->get();
        $data_lpj_timpel_db = DB::table('proposals') //--> ambil data lpj perbidang dulu
            ->join('bidang', 'bidang.bidangID', '=', 'proposals.bidangID')
            ->join('lpj', 'lpj.proposalID', '=', 'proposals.proposalID')
            ->select('bidang.namaBidang', DB::raw('count(bidang.namaBidang) as jumlah'))
            ->groupby('bidang.namaBidang')
            ->get();
        // snake case

        //didalam drilldown
        $data_timpel_per_bidang_db = DB::table('proposals')
            ->join('timpel', 'timpel.timpelID', '=', 'proposals.timpelID')
            ->join('bidang', 'bidang.bidangID', '=', 'proposals.bidangID')
            ->select('bidang.namaBidang', 'timpel.namaTimpel', DB::raw('count(*) as jumlah'))
            ->groupby('bidang.namaBidang', 'timpel.namaTimpel')
            ->get();
        $data_timpel_lpj_per_bidang_db = DB::table('proposals')
            ->join('lpj', 'lpj.proposalID', '=', 'proposals.proposalID')
            ->join('timpel', 'timpel.timpelID', '=', 'proposals.timpelID')
            ->join('bidang', 'bidang.bidangID', '=', 'proposals.bidangID')
            ->select('bidang.namaBidang', 'timpel.namaTimpel', DB::raw('count(*) as jumlah'))
            ->groupby('bidang.namaBidang', 'timpel.namaTimpel')
            ->get();

        //LOGIC
        //3 Array ditampung
        $data_proposal_bidang = array("bidang" => array(), "disetujui" => array(), "tidakDisetujui" => array()); // mapping dulu
        $data_lpj_bidang = array("bidang" => array(), "disetujui" => array(), "tidakDisetujui" => array());

        
        //logic untuk data proposal per bidang disetujui vs tidak disetujui
        foreach ($data_proposal_bidang_db as $data) {
            // Cek apakah namaBidang ada di array bidang
            // camel case
            if (in_array($data->namaBidang, $data_proposal_bidang['bidang'])) {
                //jika ada
                $idxOfBidang = array_search($data->namaBidang, $data_proposal_bidang['bidang']);
                if ($data->status == 'Disetujui') {
                    $data_proposal_bidang['disetujui'][$idxOfBidang] = $data->jumlah;
                } else {
                    $data_proposal_bidang['tidakDisetujui'][$idxOfBidang] = $data->jumlah;
                }
            } else {
                // namaBidang tidak ada di array bidang
                array_push($data_proposal_bidang['bidang'], $data->namaBidang);
                if ($data->status == 'Disetujui') {
                    array_push($data_proposal_bidang['disetujui'], $data->jumlah);
                    array_push($data_proposal_bidang['tidakDisetujui'], 0);
                } else {
                    array_push($data_proposal_bidang['disetujui'], 0);
                    array_push($data_proposal_bidang['tidakDisetujui'], $data->jumlah);
                }
            }

        }

        //logic untuk data lpj per bidang disetujui vs tidak disetujui
        foreach ($data_lpj_bidang_db as $data) {
            if (in_array($data->namaBidang, $data_lpj_bidang['bidang'])) {
                $idxOfBidang = array_search($data->namaBidang, $data_lpj_bidang['bidang']); //ngambil posisi ke berapa arraynya
                if ($data->approvedbyRomo == 'Disetujui') {
                    $data_lpj_bidang['disetujui'][$idxOfBidang] = $data->jumlah;
                } else {
                    $data_lpj_bidang['tidakDisetujui'][$idxOfBidang] = $data->jumlah;
                }
            } else {
                array_push($data_lpj_bidang['bidang'], $data->namaBidang);
                if ($data->approvedbyRomo == 'Disetujui') {
                    array_push($data_lpj_bidang['disetujui'], $data->jumlah);
                    array_push($data_lpj_bidang['tidakDisetujui'], 0);
                } else {
                    array_push($data_lpj_bidang['disetujui'], 0);
                    array_push($data_lpj_bidang['tidakDisetujui'], $data->jumlah);
                }
            }
        }

         //data proposal timpel per bidang di luar drilldown
        //logic untuk data proposal per bidang
        $data_proposal_per_bidang = array();
        foreach ($data_proposal_per_bidang_db as $data) {
            $bidang_chart = new BidangChart();
            $bidang_chart->name = $data->namaBidang;
            $bidang_chart->y = $data->jumlah;
            $bidang_chart->drilldown = $data->namaBidang;
            array_push($data_proposal_per_bidang, $bidang_chart);
        }
        
        //logic untuk data lpj per timpel
        $data_lpj_timpel = array();
        foreach($data_lpj_timpel_db as $data){
            $bidang_chart = new BidangChart();
            $bidang_chart->name = $data->namaBidang;
            $bidang_chart->y = $data->jumlah;
            $bidang_chart->drilldown = $data->namaBidang;
            array_push($data_lpj_timpel, $bidang_chart);
        }

        //data proposal timpel per bidang dalam drilldown
        $data_timpel_per_bidang = array();
        $bidang = array();
        foreach ($data_timpel_per_bidang_db as $data) {
            ////kalau ada nama bidang di sumbu x
            if (in_array($data->namaBidang, $bidang)) {
                $idx_of_bidang = array_search($data->namaBidang, $bidang);
                array_push($data_timpel_per_bidang[$idx_of_bidang]->data, array($data->namaTimpel, $data->jumlah));
            } else { //kalau gk ada nama bidangnya di sumbu x
                array_push($bidang, $data->namaBidang);
                $bidang_timpel_chart = new BidangTimpelDrilldown(); //grouping timpel berdasarkan bidang
                $bidang_timpel_chart->name = $data->namaBidang;
                $bidang_timpel_chart->id = $data->namaBidang;
                $bidang_timpel_chart->data = array(array($data->namaTimpel, $data->jumlah));
                array_push($data_timpel_per_bidang, $bidang_timpel_chart);
            }
        }
        //data lpj timpel per bidang dalam drilldown
        $data_timpel_lpj_per_bidang = array();
        $bidang = array();
        foreach ($data_timpel_lpj_per_bidang_db as $data){
            if(in_array($data->namaBidang, $bidang)){
                $idx_of_bidang = array_search($data->namaBidang, $bidang);
                array_push($data_timpel_lpj_per_bidang[$idx_of_bidang]->data, array($data->namaTimpel, $data->jumlah));
            }else{
                array_push($bidang, $data->namaBidang);
                $bidang_timpel_chart = new BidangTimpelDrilldown();
                $bidang_timpel_chart->name = $data->namaBidang;
                $bidang_timpel_chart->id   = $data->namaBidang;
                $bidang_timpel_chart->data = array(array($data->namaTimpel, $data->jumlah));
                array_push($data_timpel_lpj_per_bidang, $bidang_timpel_chart);
            }
        }
        
        Log::info($data_lpj_bidang);
        Log::info($data_proposal_bidang);
        Log::info($data_lpj_timpel);
        Log::info(json_encode($data_timpel_per_bidang));
        Log::info(json_encode($data_timpel_lpj_per_bidang));
        Log::info($data_timpel_lpj_per_bidang_db);
        Log::info($drilldown_ditunda);
        
        // Log::info($data_status);
        // Log::info(gettype($data_proposal_bidang_db));

       
        // dd(explode("-","2022-07-01")[0]);
        
        return view('home', compact('kpi1', 'kpi2', 'kpi3', 'totalKPI4', 'count_proposal', 'count_lpj', 'count_timpel', 'count_propel', 'data_proposal_bidang', 'data_lpj_bidang', 'data_proposal_per_bidang', 'data_lpj_timpel', 'data_timpel_per_bidang', 'data_timpel_lpj_per_bidang', 'status_review_ulang', 'status_ditunda', 'status_disetujui', 'status_proposal', 'status_lpj', 'drilldown_ditunda', 'drilldown_review', 'drilldown_disetujui', 'drilldown_proposal', 'drilldown_lpj'), 
                                    ['active' => $active, 'start_date' => $start_date, 'end_date' => $end_date]);

    }

    /**'
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //tampilan form input data
    }
    
    //SEARCH DATE
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            $start_date = Carbon::createFromFormat('d/M/Y', $request->start_date)->format('Y-m-d H:i:s'),
            $end_date = Carbon::createFromFormat('d/M/Y', $request->end_date)->format('Y-m-d H:i:s'),
        ]);
        $start_date = Carbon::createFromFormat('d/M/Y', $request->start_date)->format('Y-m-d H:i:s');
        $end_date = Carbon::createFromFormat('d/M/Y', $request->end_date)->format('Y-m-d H:i:s');
        $active = 'Dashboard';
        if ($start_date > $end_date || $start_date == null || $end_date == null) {
            return redirect()->back()->with('message', 'Tanggal mulai tidak boleh lebih dari tanggal akhir');
        } else {
            
        
        $count_proposal = DB::table('proposals')->whereBetween('tanggalUpdate', [$start_date." 'Y-m-d H:i:s'",$end_date."'Y-m-d H:i:s'"])->count();
        $count_lpj = DB::table('lpj')->whereBetween('tanggalUpdate', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])->count();
        $count_propel = DB::table('propel')->whereBetween('created_at', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])->count();
        $count_timpel = DB::table('timpel')->count();

        //COLUMN CHART PROPOSAL
        $data_proposal_bidang_db = DB::table('proposals')
            ->join('bidang', 'bidang.bidangID', '=', 'proposals.bidangID')
            ->select('bidang.namaBidang', 'proposals.status', DB::raw('count(proposals.namaKegiatan) as jumlah'))
            ->groupBy('bidang.namaBidang', 'proposals.status', 'proposals.bidangID')
            ->orderBy('bidang.namaBidang')
            ->whereBetween('proposals.tanggalUpdate', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
            ->get();
            
        $data_lpj_bidang_db = DB::table('proposals')
            ->join('lpj', 'lpj.proposalID', '=', 'proposals.proposalID')
            ->join('bidang', 'bidang.bidangID', '=', 'proposals.bidangID')
            ->select('bidang.namaBidang', 'lpj.approvedbyRomo', DB::raw('count(lpj.approvedbyRomo) as jumlah'))
            ->groupBy('bidang.namaBidang', 'lpj.approvedByRomo')
           ->whereBetween('lpj.tanggalUpdate', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
           ->get();
            
            
        
            //PIE CHART
        $status_review_ulang = DB::table('propel')
                        ->select('propel.status')
                        ->where('propel.status', '=', 'Review Ulang')
                        ->whereBetween('propel.created_at', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
                        ->count();
        
        $status_ditunda = DB::table('propel')
                        ->select('propel.status')
                        ->where('propel.status', '=', 'Ditunda')
                        ->whereBetween('propel.created_at', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
                        ->count();
        
        $status_disetujui = DB::table('propel')
                        ->select('propel.status')
                        ->where('propel.status', '=', 'Disetujui')
                        ->whereBetween('propel.created_at', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
                        ->count();

        $status_proposal = DB::table('propel')
                        ->select('propel.status')
                        ->where('propel.status', '=', 'Proposal')
                        ->whereBetween('propel.created_at', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
                        ->count();

        $status_lpj = DB::table('propel')
                        ->select('propel.status')
                        ->where('propel.status', '=', 'Lpj')
                        ->whereBetween('propel.created_at', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
                        ->count();
        Log::info($status_review_ulang);
        
         

        $drilldown_ditunda_db = DB::table('propel')
                            ->join('bidang', 'bidang.bidangID', '=', 'propel.bidangID')
                            ->select('bidang.namaBidang', DB::raw('count(*) as jumlah'))
                            ->where('propel.status', '=', 'Ditunda')
                            ->groupby('bidang.namaBidang')
                            ->whereBetween('propel.created_at', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
                            ->get();
         $drilldown_review_db = DB::table('propel')
                            ->join('bidang', 'bidang.bidangID', '=', 'propel.bidangID')
                            ->select('bidang.namaBidang', DB::raw('count(*) as jumlah'))
                            ->where('propel.status', '=', 'Review Ulang')
                            ->groupby('bidang.namaBidang')
                            ->whereBetween('propel.created_at', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
                            ->get();
        $drilldown_disetujui_db = DB::table('propel')
                            ->join('bidang', 'bidang.bidangID', '=', 'propel.bidangID')
                            ->select('bidang.namaBidang', DB::raw('count(*) as jumlah'))
                            ->where('propel.status', '=', 'Disetujui')
                            ->groupby('bidang.namaBidang')
                            ->whereBetween('propel.created_at', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
                            ->get();
        $drilldown_proposal_db = DB::table('propel')
                            ->join('bidang', 'bidang.bidangID', '=', 'propel.bidangID')
                            ->select('bidang.namaBidang', DB::raw('count(*) as jumlah'))
                            ->where('propel.status', '=', 'Proposal')
                            ->groupby('bidang.namaBidang')
                            ->whereBetween('propel.created_at', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
                            ->get();
        $drilldown_lpj_db = DB::table('propel')
                            ->join('bidang', 'bidang.bidangID', '=', 'propel.bidangID')
                            ->select('bidang.namaBidang', DB::raw('count(*) as jumlah'))
                            ->where('propel.status', '=', 'Lpj')
                            ->groupby('bidang.namaBidang')
                            ->whereBetween('propel.created_at', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
                            ->get();
        //KPI
        $kpi1 = DB::table('lpj')
                ->join('proposals', 'proposals.proposalID', '=', 'lpj.proposalID')
                ->select('proposals.tanggalUpdate', 'lpj.tanggalUpdate')
                ->whereRaw('lpj.tanggalUpdate <= DATE_ADD(proposals.tanggalUpdate, INTERVAL 30 DAY)')
                ->whereBetween('lpj.tanggalUpdate', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
                ->count();
        //surplus
        $kpi2 = DB::table('lpj')
                ->join('proposals', 'proposals.proposalID' ,'=', 'lpj.proposalID')
                ->select('proposals.totalBiaya', 'lpj.saldo')
                ->whereRaw('proposals.totalBiaya >= lpj.saldo')
                ->whereBetween('lpj.tanggalUpdate', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
                ->count();


        //defisit
        $kpi3 = DB::table('lpj')
                ->join('proposals', 'proposals.proposalID' ,'=', 'lpj.proposalID')
                ->select('proposals.totalBiaya', 'lpj.saldo')
                ->whereRaw('proposals.totalBiaya <= lpj.saldo')
                ->whereBetween('lpj.tanggalUpdate', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
                ->count();

        $kpi4 = DB::table('lpj')
                ->join('rinciankegiatanlpj', 'rinciankegiatanlpj.lpjID' ,'=', 'lpj.lpjID')
                ->join('rinciankegiatanproposal', 'rinciankegiatanproposal.proposalID' ,'=', 'lpj.proposalID')
                ->select('rinciankegiatanlpj.waktuMulaiLPJ', 'rinciankegiatanproposal.waktuMulai')
                ->whereBetween('lpj.tanggalUpdate', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])
                ->get();
        
                // return array($kpi2,$kpi3);

        $drilldown_ditunda = array();
        foreach ($drilldown_ditunda_db as $data) {
            array_push($drilldown_ditunda, array($data->namaBidang, $data->jumlah));
        }

        $drilldown_review = array();
        foreach ($drilldown_review_db as $data){
            array_push($drilldown_review, array($data->namaBidang, $data->jumlah));
        }

        $drilldown_disetujui = array();
        foreach($drilldown_disetujui_db as $data){
            array_push($drilldown_disetujui, array($data->namaBidang, $data->jumlah));
        }

        $drilldown_proposal = array();
        foreach($drilldown_proposal_db as $data){
            array_push($drilldown_proposal, array($data->namaBidang, $data->jumlah));
        }

        $drilldown_lpj = array();
        foreach($drilldown_lpj_db as $data){
            array_push($drilldown_lpj, array($data->namaBidang, $data->jumlah));
        }

        
        //tampilan luar drilldown pada data bidang
        $data_proposal_per_bidang_db = DB::table('proposals')
            ->join('bidang', 'bidang.bidangID', '=', 'proposals.bidangID')
            ->select('bidang.namaBidang', DB::raw('count(bidang.namaBidang) as jumlah'))
            ->groupBy('bidang.namaBidang')
            ->orderBy('bidang.namaBidang')
            ->whereBetween('proposals.tanggalUpdate', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])->get();

        $data_lpj_timpel_db = DB::table('proposals')
            ->join('bidang', 'bidang.bidangID', '=', 'proposals.bidangID')
            ->join('lpj', 'lpj.proposalID', '=', 'proposals.proposalID')
            ->select('bidang.namaBidang', DB::raw('count(bidang.namaBidang) as jumlah'))
            ->groupby('bidang.namaBidang')
            ->whereBetween('lpj.tanggalUpdate', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])->get();

        // snake case
        //didalam drilldown
        $data_timpel_per_bidang_db = DB::table('proposals')
            ->join('timpel', 'timpel.timpelID', '=', 'proposals.timpelID')
            ->join('bidang', 'bidang.bidangID', '=', 'proposals.bidangID')
            ->select('bidang.namaBidang', 'timpel.namaTimpel', DB::raw('count(*) as jumlah'))
            ->groupby('bidang.namaBidang', 'timpel.namaTimpel')
            ->whereBetween('proposals.tanggalUpdate', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])->get();

        $data_timpel_lpj_per_bidang_db = DB::table('proposals')
            ->join('lpj', 'lpj.proposalID', '=', 'proposals.proposalID')
            ->join('timpel', 'timpel.timpelID', '=', 'proposals.timpelID')
            ->join('bidang', 'bidang.bidangID', '=', 'proposals.bidangID')
            ->select('bidang.namaBidang', 'timpel.namaTimpel', DB::raw('count(*) as jumlah'))
            ->groupby('bidang.namaBidang', 'timpel.namaTimpel')
            ->whereBetween('lpj.tanggalUpdate', [$start_date." Y-m-d H:i:s",$end_date."Y-m-d H:i:s"])->get();


        //3 Array ditampung
        $data_proposal_bidang = array("bidang" => array(), "disetujui" => array(), "tidakDisetujui" => array());
        $data_lpj_bidang = array("bidang" => array(), "disetujui" => array(), "tidakDisetujui" => array());

        //logic untuk data proposal per bidang
        foreach ($data_proposal_bidang_db as $data) {
            // Cek apakah namaBidang ada di array bidang
            // camel case
            if (in_array($data->namaBidang, $data_proposal_bidang['bidang'])) {
                //jika ada
                $idxOfBidang = array_search($data->namaBidang, $data_proposal_bidang['bidang']);
                if ($data->status == 'Disetujui') {
                    $data_proposal_bidang['disetujui'][$idxOfBidang] = $data->jumlah;
                } else {
                    $data_proposal_bidang['tidakDisetujui'][$idxOfBidang] = $data->jumlah;
                }
            } else {
                // namaBidang tidak ada di array bidang
                array_push($data_proposal_bidang['bidang'], $data->namaBidang);
                if ($data->status == 'Disetujui') {
                    array_push($data_proposal_bidang['disetujui'], $data->jumlah);
                    array_push($data_proposal_bidang['tidakDisetujui'], 0);
                } else {
                    array_push($data_proposal_bidang['disetujui'], 0);
                    array_push($data_proposal_bidang['tidakDisetujui'], $data->jumlah);
                }
            }

        }

        //logic untuk data proposal per timpel
        foreach ($data_lpj_bidang_db as $data) {
            if (in_array($data->namaBidang, $data_lpj_bidang['bidang'])) {
                $idxOfBidang = array_search($data->namaBidang, $data_lpj_bidang['bidang']); //ngambil posisi ke berapa arraynya
                if ($data->approvedbyRomo == 'Disetujui') {
                    $data_lpj_bidang['disetujui'][$idxOfBidang] = $data->jumlah;
                } else {
                    $data_lpj_bidang['tidakDisetujui'][$idxOfBidang] = $data->jumlah;
                }
            } else {
                array_push($data_lpj_bidang['bidang'], $data->namaBidang);
                if ($data->approvedbyRomo == 'Disetujui') {
                    array_push($data_lpj_bidang['disetujui'], $data->jumlah);
                    array_push($data_lpj_bidang['tidakDisetujui'], 0);
                } else {
                    array_push($data_lpj_bidang['disetujui'], 0);
                    array_push($data_lpj_bidang['tidakDisetujui'], $data->jumlah);
                }
            }
        }

         //data proposal timpel per bidang di luar drilldown
        //logic untuk data proposal per bidang
        $data_proposal_per_bidang = array();
        foreach ($data_proposal_per_bidang_db as $data) {
            $bidang_chart = new BidangChart();
            $bidang_chart->name = $data->namaBidang;
            $bidang_chart->y = $data->jumlah;
            $bidang_chart->drilldown = $data->namaBidang;
            array_push($data_proposal_per_bidang, $bidang_chart);
        }
        //logic untuk data lpj per timpel
        $data_lpj_timpel = array();
        foreach($data_lpj_timpel_db as $data){
            $bidang_chart = new BidangChart();
            $bidang_chart->name = $data->namaBidang;
            $bidang_chart->y = $data->jumlah;
            $bidang_chart->drilldown = $data->namaBidang;
            array_push($data_lpj_timpel, $bidang_chart);
        }

        //data proposal timpel per bidang dalam drilldown
        $data_timpel_per_bidang = array();
        $bidang = array();
        foreach ($data_timpel_per_bidang_db as $data) {
            if (in_array($data->namaBidang, $bidang)) {
                $idx_of_bidang = array_search($data->namaBidang, $bidang);
                array_push($data_timpel_per_bidang[$idx_of_bidang]->data, array($data->namaTimpel, $data->jumlah));
            } else {
                array_push($bidang, $data->namaBidang);
                $bidang_timpel_chart = new BidangTimpelDrilldown();
                $bidang_timpel_chart->name = $data->namaBidang;
                $bidang_timpel_chart->id = $data->namaBidang;
                $bidang_timpel_chart->data = array(array($data->namaTimpel, $data->jumlah));
                array_push($data_timpel_per_bidang, $bidang_timpel_chart);
            }
        }
        //data lpj timpel per bidang dalam drilldown
        $data_timpel_lpj_per_bidang = array();
        $bidang = array();
        foreach ($data_timpel_lpj_per_bidang_db as $data){
            if(in_array($data->namaBidang, $bidang)){
                $idx_of_bidang = array_search($data->namaBidang, $bidang);
                array_push($data_timpel_lpj_per_bidang[$idx_of_bidang]->data, array($data->namaTimpel, $data->jumlah));
            }else{
                array_push($bidang, $data->namaBidang);
                $bidang_timpel_chart = new BidangTimpelDrilldown();
                $bidang_timpel_chart->name = $data->namaBidang;
                $bidang_timpel_chart->id   = $data->namaBidang;
                $bidang_timpel_chart->data = array(array($data->namaTimpel, $data->jumlah));
                array_push($data_timpel_lpj_per_bidang, $bidang_timpel_chart);
            }
        }
        
        $totalKPI4 = 0;
        foreach ($kpi4 as $data) {
            if(strtotime($data->waktuMulaiLPJ) == strtotime($data->waktuMulai)) $totalKPI4++;
        }
        
        return view('home', compact('kpi1', 'kpi2', 'kpi3', 'totalKPI4', 'count_proposal', 'count_lpj', 'count_timpel', 'count_propel', 'data_proposal_bidang', 'data_lpj_bidang', 'data_proposal_per_bidang', 'data_lpj_timpel', 'data_timpel_per_bidang', 'data_timpel_lpj_per_bidang', 'status_review_ulang', 'status_ditunda', 'status_disetujui', 'status_proposal', 'status_lpj', 'drilldown_ditunda', 'drilldown_review', 'drilldown_disetujui', 'drilldown_proposal', 'drilldown_lpj'), 
                                    ['active' => $active, 'start_date' => $start_date, 'end_date' => $end_date]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //function input data
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //menampilkan data spesific
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //tampilan edit data/spesific data
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //function update data
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
