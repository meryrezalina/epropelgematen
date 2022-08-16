<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ProposalExport;
use App\Exports\ProposalList;
use App\Http\Controllers\Controller;
use App\Models\AnggaranProposal;
use App\Models\Bidang;
use App\Models\IndikatorTargetProposal;
use App\Models\Proposal;
use App\Models\RincianKegiatanProposal;
use App\Models\SumberDana;
use App\Models\Timpel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Proposal $proposals)
    {

        $q = $request->input('q');

        $active = 'E-Proposal';

        $data_fk = Proposal::with('bidang', 'timpel');

        $proposals = $proposals
            ->join('bidang', 'bidang.bidangID', '=', 'proposals.bidangID')
            ->join('timpel', 'timpel.timpelID', '=', 'proposals.timpelID')
            ->select('proposals.proposalID', 'bidang.namaBidang', 'timpel.namaTimpel', 'proposals.namaKegiatan', 'proposals.nomorRAPB', 'proposals.totalBiaya', 'proposals.status', 'proposals.updated_at')
            ->where('proposals.namaKegiatan', 'like', '%' . $q . '%')
            ->orWhere('proposals.namaPJ', 'like', '%' . $q . '%')
            ->orWhere('proposals.nomorRAPB', 'like', '%' . $q . '%')
            ->orWhere('bidang.namaBidang', 'like', '%' . $q . '%')
            ->orWhere('timpel.namaTimpel', 'like', '%' . $q . '%')
            ->orWhere('proposals.updated_at', 'like', '%' . $q . '%')
            ->sortable(['created_at' => 'desc'])
            ->paginate(20);

        $request = $request->all();

        return view('dashboard/proposal/list', compact('data_fk'), ['proposals' => $proposals,
            'request' => $request,
            'active' => $active,
            'data_fk' => $data_fk]);
    }

    //EXPORT PROPOSAL
    public function proposalexport()
    {
        $time = Carbon::now();
        return Excel::download(new ProposalExport, 'proposal ' . $time . '.xlsx');
    }

    public function proposallist(Proposal $proposals, AnggaranProposal $proposalID)
    {
        // dd($proposals);
        $time = Carbon::now();
        return Excel::download(new ProposalList($proposals->proposalID), 'dataproposal ' . $time . '.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active = 'E-Proposal';
        $bid = Bidang::all();
        $tim = Timpel::all();
        $proposal = Proposal::all();
        $anggaranproposal = AnggaranProposal::paginate(10);
        $sum = SumberDana::all();
        $timByBidang = $tim;
        $anggarans = [];
        $indikators = [];
        $rincians = [];

        return view('dashboard/proposal/form', compact('bid', 'tim', 'anggarans', 'sum', 'timByBidang', 'anggaranproposal', 'proposal', 'indikators', 'rincians'),
            [
                'button' => 'Create',
                'url' => 'dashboard.proposal.store',
                'active' => $active,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Proposal $proposals)
    {
        $validator = Validator::make($request->all(), [
            'bidangID' => 'required',
            'namaKegiatan' => 'required',
            'namaPJ' => 'required',
            'no_hp' => 'required',
            'sasaranStrategis' => 'required',
            'totalBiaya' => 'required',
            'status' => 'required',
            'anggarans' => 'required',
            'indikators' => 'required',
            'rincians' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('dashboard.proposal.create')
                ->withErrors($validator)
                ->withInput();
        } else {

            $proposals->bidangID = $request->input('bidangID');
            $proposals->timpelID = $request->input('timpelID');
            $proposals->jenisProposal = $request->input('jenisProposal');
            $proposals->namaKegiatan = $request->input('namaKegiatan');
            $proposals->nomorRAPB = $request->input('nomorRAPB');
            $proposals->namaPJ = $request->input('namaPJ');
            $proposals->no_hp = $request->input('no_hp');
            $proposals->totalBiaya = $request->input('totalBiaya');
            $proposals->sasaranStrategis = $request->input('sasaranStrategis');
            $proposals->romoApprover = $request->input('romoApprover');
            $proposals->kabidApprover = $request->input('kabidApprover');
            $proposals->approvedbyRomo = $request->input('approvedbyRomo');
            $proposals->approvedbyKabid = $request->input('approvedbyKabid');
            $proposals->tanggalUpdate = Carbon::now()->toDateString();
            $proposals->status = $request->input('status');
            $proposals->is_active = 0;

            $proposals->save();

            //ANGGARAN INSERT
            $currentProposalID = $proposals->proposalID;
            $anggarans = array();
            foreach ($request->input('anggarans') as $anggaran) {
                array_push($anggarans, array(
                    'proposalID' => $currentProposalID,
                    'anggaranDeskripsi' => $anggaran['anggaranDeskripsi'],
                    'hargaSatuan' => $anggaran['hargaSatuan'],
                    'kuantitas' => $anggaran['kuantitas'],
                    'satuan' => $anggaran['satuan'],
                    'sumberID' => $anggaran['sumberID'])
                );
            }
            AnggaranProposal::insert($anggarans);

            //INDIKATOR TARGET INSERT
            $indikators = array();
            foreach ($request->input('indikators') as $indikator) {
                array_push($indikators, array(
                    'proposalID' => $currentProposalID,
                    'indikatorDeskripsi' => $indikator['indikatorDeskripsi'],
                    'target' => $indikator['target'])
                );
            }
            IndikatorTargetProposal::insert($indikators);

            // RINCIAN KEGIATAN INSERT
            $rincians = array();
            foreach ($request->input('rincians') as $rincian) {
                $parseWaktuMulai = $rincian['waktuMulai'];
                $parseWaktuSelesai = $rincian['waktuSelesai'];
                $stringWaktuMulai = strstr($parseWaktuMulai, "(", true);
                $stringWaktuSelesai = strstr($parseWaktuSelesai, "(", true);
                array_push($rincians, array(
                    'proposalID' => $currentProposalID,
                    'rincianDeskripsi' => $rincian['rincianDeskripsi'],
                    'tempat' => $rincian['tempat'],
                    'waktuMulai' => Carbon::parse($stringWaktuMulai)->format('Y-m-d'),
                    'waktuSelesai' => Carbon::parse($stringWaktuSelesai)->format('Y-m-d'))
                );
            }
            RincianKegiatanProposal::insert($rincians);
            return redirect()
                ->route('dashboard.proposals')
                ->with('message', __('message.create', ['propel' => $request->input('propel')]));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function show(Proposal $proposal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function edit(Proposal $proposals)
    {
        $active = 'E-Proposal';
        $bid = Bidang::all();
        $tim = Timpel::all();
        $timByBidang = Timpel::where('bidangID', '=', $proposals->bidangID)->get();
        $proposal = Proposal::get();
        $anggaranproposal = '';
        $anggarans = $proposals->anggaranProposal()->get();
        $sum = SumberDana::all();
        $indikators = $proposals->indikatorTargetProposal()->get();
        $rincians = $proposals->rincianKegiatanProposal()->get();

        $proposals->totalBiaya = number_format($proposals->totalBiaya, 0, "", ".");

        return view('dashboard/proposal/form', compact('bid', 'tim', 'anggarans', 'timByBidang', 'anggaranproposal', 'sum', 'indikators', 'rincians'),
            [
                'active' => $active,
                'proposals' => $proposals,
                'proposal' => $proposal,
                'button' => 'Update',
                'url' => 'dashboard.proposal.update',
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proposal $proposals)
    {
        $validator = Validator::make($request->all(), [
            'bidangID' => 'required',
            'namaKegiatan' => 'required',
            'namaPJ' => 'required',
            'no_hp' => 'required',
            'sasaranStrategis' => 'required',
            'totalBiaya' => 'required',
            'status' => 'required',
            'anggarans' => 'required',
            'indikators' => 'required',
            'rincians' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('dashboard.proposal.create')
                ->withErrors($validator)
                ->withInput();
        } else {

            $proposals->bidangID = $request->input('bidangID');
            $proposals->timpelID = $request->input('timpelID');
            $proposals->jenisProposal = $request->input('jenisProposal');
            $proposals->namaKegiatan = $request->input('namaKegiatan');
            $proposals->nomorRAPB = $request->input('nomorRAPB');
            $proposals->namaPJ = $request->input('namaPJ');
            $proposals->no_hp = $request->input('no_hp');
            $proposals->totalBiaya = preg_replace("/[.,]/", "", $request->totalBiaya);
            $proposals->sasaranStrategis = $request->input('sasaranStrategis');
            $proposals->romoApprover = $request->input('romoApprover');
            $proposals->kabidApprover = $request->input('kabidApprover');
            $proposals->approvedbyRomo = $request->input('approvedbyRomo');
            $proposals->approvedbyKabid = $request->input('approvedbyKabid');
            $proposals->tanggalUpdate = $request->input('tanggalUpdate');
            $proposals->status = $request->input('status');
            $proposals->save();

            //ANGGARAN
            $currentProposalID = $proposals->proposalID;
            $anggarans = array();
            foreach ($request->input('anggarans') as $anggaran) {
                if ($anggaran['id'] != null) {
                    if ($anggaran['isDeleted'] == 'true') {
                        AnggaranProposal::where('anggaranID', '=', $anggaran['id'])->delete();
                    } else {
                        AnggaranProposal::where('anggaranID', '=', $anggaran['id'])->update(array(
                            'proposalID' => $currentProposalID,
                            'anggaranDeskripsi' => $anggaran['anggaranDeskripsi'],
                            'hargaSatuan' => $anggaran['hargaSatuan'],
                            'kuantitas' => $anggaran['kuantitas'],
                            'satuan' => $anggaran['satuan'],
                            'sumberID' => $anggaran['sumberID'])
                        );
                    }
                } else {
                    array_push($anggarans, array(
                        'proposalID' => $currentProposalID,
                        'anggaranDeskripsi' => $anggaran['anggaranDeskripsi'],
                        'hargaSatuan' => $anggaran['hargaSatuan'],
                        'kuantitas' => $anggaran['kuantitas'],
                        'satuan' => $anggaran['satuan'],
                        'sumberID' => $anggaran['sumberID'])
                    );
                }
            }
            if (!empty($anggarans)) {
                AnggaranProposal::insert($anggarans);
            }

            //INDIKATOR TARGET
            $indikators = array();
            foreach ($request->input('indikators') as $indikator) {
                if ($indikator['id'] != null) {
                    if ($indikator['isDeleted'] == 'true') {
                        IndikatorTargetProposal::where('indikatorID', '=', $indikator['id'])->delete();
                    } else {
                        IndikatorTargetProposal::where('indikatorID', '=', $indikator['id'])->update(array(
                            'proposalID' => $currentProposalID,
                            'indikatorDeskripsi' => $indikator['indikatorDeskripsi'],
                            'target' => $indikator['target'],
                        ));
                    }
                } else {
                    array_push($indikators, array(
                        'proposalID' => $currentProposalID,
                        'indikatorDeskripsi' => $indikator['indikatorDeskripsi'],
                        'target' => $indikator['target'],
                        'pencapaianLPJ' => $indikator['pencapaianLPJ'])
                    );
                }
            }
            if (!empty($indikators)) {
                IndikatorTargetProposal::insert($indikators);
            }

            //RINCIAN KEGIATAN
            $rincians = array();
            foreach ($request->input('rincians') as $rincian) {
                if ($rincian['id'] != null) {
                    if ($rincian['isDeleted'] == 'true') {
                        RincianKegiatanProposal::where('rincianID', '=', $rincian['id'])->delete();
                    } else {
                        RincianKegiatanProposal::where('rincianID', '=', $rincian['id'])->update(array(
                            'proposalID' => $currentProposalID,
                            'rincianDeskripsi' => $rincian['rincianDeskripsi'],
                            'tempat' => $rincian['tempat'],
                            'waktuMulai' => $rincian['waktuMulai'],
                            'waktuSelesai' => $rincian['waktuSelesai'],
                        ));
                    }
                } else {
                    array_push($rincians, array(
                        'proposalID' => $currentProposalID,
                        'rincianDeskripsi' => $rincian['rincianDeskripsi'],
                        'tempat' => $rincian['tempat'],
                        'waktuMulai' => $rincian['waktuMulai'],
                        'waktuSelesai' => $rincian['waktuSelesai'])
                    );
                }
            }
            if (!empty($rincians)) {
                Log::info('adds');
                RincianKegiatanProposal::insert($rincians);
            }

            return redirect()
                ->route('dashboard.proposals')
                ->with('message', __('message.update', ['propel' => $request->input('propel')]));

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Proposal $proposals, AnggaranProposal $anggarans, IndikatorTargetProposal $indikators, RincianKegiatanProposal $rincians)
    {
        foreach ($anggarans as $anggaran) {
            $id = $anggaran['id'];
            if ($id != null) {
                AnggaranProposal::where('anggaranID', '=', $id)->delete();
            }
        }
        foreach ($indikators as $indikator) {
            $id = $indikator['id'];
            if ($id != null) {
                IndikatorTargetProposal::where('indikatorID', '=', $id)->delete();
            }
        }
        foreach ($rincians as $rincian) {
            $id = $rincian['id'];
            if ($id != null) {
                RincianKegiatanProposal::where('rincianID', '=', $id)->delete();
            }
        }

        $proposals->delete();

        return redirect()
            ->route('dashboard.proposals')
            ->with('message', __('message.delete', ['propel' => $request->input('propel')]));
    }
}
