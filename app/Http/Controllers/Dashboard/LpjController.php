<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AnggaranLpj;
use App\Models\IndikatorTargetLpj;
use App\Models\Lpj;
use App\Models\Proposal;
use App\Models\RincianKegiatanLpj;
use App\Models\SumberDana;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LpjController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Lpj $lpj)
    {
        $q = $request->input('q');

        $active = 'E-LPJ';

        $data_fk = Lpj::with('proposal');

        // $lpj = $lpj->when($q, function ($query) use ($q) {
        //     return $query->where('saldo', 'like', '%' . $q . '%')
        //                  ->orWhere('tanggalUpdate', 'like', '%' . $q . '%');
        // })->sortable(['tanggalUpdate' => 'desc'])
        //     ->paginate(20);

        $lpj = $lpj
            ->join('proposals', 'proposals.proposalID', '=', 'lpj.proposalID')
            ->select('lpj.lpjID', 'proposals.namaKegiatan', 'lpj.approvedbyRomo', 'lpj.approvedbyKabid', 'lpj.saldo', 'lpj.totalPengeluaran', 'lpj.updated_at')
            ->where('proposals.namaKegiatan', 'like', '%' . $q . '%')
            ->orWhere('lpj.approvedbyRomo', 'like', '%' . $q . '%')
            ->orWhere('lpj.approvedbyKabid', 'like', '%' . $q . '%')
            ->orWhere('lpj.saldo', 'like', '%' . $q . '%')
            ->orWhere('lpj.totalPengeluaran', 'like', '%' . $q . '%')
            ->orWhere('lpj.updated_at', 'like', '%' . $q . '%')
            ->sortable(['created_at' => 'desc'])
            ->paginate(20);
        // return $lpj->total();
        $request = $request->all();

        return view('dashboard/lpj/list', [
            'lpj' => $lpj,
            'request' => $request,
            'active' => $active
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active = 'E-LPJ';
        $pro = Proposal::where("is_active", "=", 0)->get();
        $sum = SumberDana::all();
        //$indikators = [];
        $anggarans = [];
        $rincians = [];

        //return $indikators;
        $indikators = DB::table('proposals')
            ->join('indikatortarget', 'proposals.proposalID', '=', 'indikatortarget.proposalID')
            ->select('indikatortarget.indikatorID', 'indikatortarget.proposalID', 'indikatortarget.indikatorDeskripsi', 'indikatortarget.target')
            ->get();

        return view(
            'dashboard/lpj/form',
            compact('pro', 'sum', 'anggarans', 'indikators', 'rincians'),
            [
                'button' => 'Create',
                'url' => 'dashboard.lpj.store',
                'active' => $active,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Lpj $lpj)
    {

        //return $request;
        $validator = Validator::make($request->all(), [
            // 'proposalID' => 'required',
            // 'saldo' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('dashboard.lpj.create')
                ->withErrors($validator)
                ->withInput();
        } else {

            $lpj->proposalID = $request->input('proposalID');
            $lpj->saldo = $request->input('saldo');
            $lpj->approvedbyRomo = $request->input('approvedbyRomo');
            $lpj->approvedbyKabid = $request->input('approvedbyKabid');
            $lpj->totalPengeluaran = $request->input('pengeluaranTotal');
            $lpj->tanggalUpdate = Carbon::now()->toDateString();

            $lpj->save();
            Proposal::where('proposalID', $lpj->proposalID)->update(['is_active' => 1]);

            //ANGGARAN INSERT
            $currentLpjID = $lpj->lpjID;
            $anggarans = array();
            foreach ($request->input('anggarans') as $anggaran) {
                array_push(
                    $anggarans,
                    array(
                        'lpjID' => $currentLpjID,
                        'pengeluaranDeskripsi' => $anggaran['pengeluaranDeskripsi'],
                        'hargaSatuan' => $anggaran['hargaSatuan'],
                        'kuantitas' => $anggaran['kuantitas'],
                        'satuan' => $anggaran['satuan'],
                        'sumberID' => $anggaran['sumberID']
                    )
                );
            }
            AnggaranLpj::insert($anggarans);

            //INDIKATOR TARGET INSERT
            $indikators = array();
            foreach ($request->input('indikators') as $indikator) {
                array_push(
                    $indikators,
                    array(
                        'lpjID' => $currentLpjID,
                        'indikatorDeskripsi' => $indikator['indikatorDeskripsi'],
                        'target' => $indikator['target'],
                        'pencapaianLPJ' => $indikator['pencapaianLPJ']
                    )
                );
            }
            IndikatorTargetLpj::insert($indikators);

            // RINCIAN KEGIATAN INSERT
            $rincians = array();
            foreach ($request->input('rincians') as $rincian) {
                $parseWaktuMulai = $rincian['waktuMulaiLPJ'];
                $parseWaktuSelesai = $rincian['waktuSelesaiLPJ'];
                $stringWaktuMulai = strstr($parseWaktuMulai, "(", true);
                $stringWaktuSelesai = strstr($parseWaktuSelesai, "(", true);
                array_push(
                    $rincians,
                    array(
                        'lpjID' => $currentLpjID,
                        'rincianDeskripsiLPJ' => $rincian['rincianDeskripsiLPJ'],
                        'tempatLPJ' => $rincian['tempatLPJ'],
                        'waktuMulaiLPJ' => Carbon::parse($stringWaktuMulai)->format('Y-m-d'),
                        'waktuSelesaiLPJ' => Carbon::parse($stringWaktuSelesai)->format('Y-m-d')
                    )
                );
            }
            RincianKegiatanLpj::insert($rincians);

            return redirect()
                ->route('dashboard.lpjs')
                ->with('message', __('message.create', ['propel' => $request->input('propel')]));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lpj  $lpj
     * @return \Illuminate\Http\Response
     */
    public function show(Lpj $lpj)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lpj  $lpj
     * @return \Illuminate\Http\Response
     */
    public function edit(Lpj $lpj)
    {
        $active = 'E-LPJ';
        $pro = Proposal::get();
        $anggarans = $lpj->anggaranLpj()->get();
        $sum = SumberDana::all();
        $indikators = $lpj->indikatorTargetLpj()->get();
        $rincians = $lpj->rincianKegiatanLpj()->get();

        //return $indikators;

        return view(
            'dashboard/lpj/form',
            compact('anggarans', 'sum', 'indikators', 'rincians'),
            [
                'active' => $active,
                'pro' => $pro,
                'lpj' => $lpj,
                'button' => 'Update',
                'url' => 'dashboard.lpj.update',
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lpj  $lpj
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lpj $lpj)
    {
        $validator = Validator::make($request->all(), [
            'proposalID' => 'required',
            'saldo' => 'required',
        ]);

        //return $request;

        if ($validator->fails()) {
            return redirect()
                ->route('dashboard.proposal.create')
                ->withErrors($validator)
                ->withInput();
        } else {

            $lpj->proposalID = $request->input('proposalID');
            $lpj->saldo = $request->input('saldo');
            $lpj->approvedbyRomo = $request->input('approvedbyRomo');
            $lpj->approvedbyKabid = $request->input('approvedbyKabid');
            $lpj->tanggalUpdate = date("Y-m-d");
            $lpj->save();

            //ANGGARAN
            $currentLpjID = $lpj->lpjID;
            $anggarans = array();
            foreach ($request->input('anggarans') as $anggaran) {
                if ($anggaran['id'] != null) {
                    if ($anggaran['isDeleted'] == 'true') {
                        AnggaranLpj::where('pengeluaranID', '=', $anggaran['id'])->delete();
                    } else {
                        AnggaranLpj::where('pengeluaranID', '=', $anggaran['id'])->update(
                            array(
                                'lpjID' => $currentLpjID,
                                'pengeluaranDeskripsi' => $anggaran['pengeluaranDeskripsi'],
                                'hargaSatuan' => $anggaran['hargaSatuan'],
                                'kuantitas' => $anggaran['kuantitas'],
                                'satuan' => $anggaran['satuan'],
                                'sumberID' => $anggaran['sumberID']
                            )
                        );
                    }
                } else {
                    array_push(
                        $anggarans,
                        array(
                            'lpjID' => $currentLpjID,
                            'pengeluaranDeskripsi' => $anggaran['pengeluaranDeskripsi'],
                            'hargaSatuan' => $anggaran['hargaSatuan'],
                            'kuantitas' => $anggaran['kuantitas'],
                            'satuan' => $anggaran['satuan'],
                            'sumberID' => $anggaran['sumberID']
                        )
                    );
                }
            }
            if (!empty($anggarans)) {
                AnggaranLpj::insert($anggarans);
            }

            //INDIKATOR TARGET
            $indikators = array();
            foreach ($request->input('indikators') as $indikator) {
                if ($indikator['id'] != null) {
                    if ($indikator['isDeleted'] == 'true') {
                        IndikatorTargetLpj::where('indikatorLpjID', '=', $indikator['id'])->delete();
                    } else {
                        IndikatorTargetLpj::where('indikatorLpjID', '=', $indikator['id'])->update(array(
                            'lpjID' => $currentLpjID,
                            'indikatorDeskripsi' => $indikator['indikatorDeskripsi'],
                            'target' => $indikator['target'],
                            'pencapaianLPJ' => $indikator['pencapaianLPJ'],
                        ));
                    }
                } else {
                    array_push(
                        $indikators,
                        array(
                            'lpjID' => $currentLpjID,
                            'indikatorDeskripsi' => $indikator['indikatorDeskripsi'],
                            'target' => $indikator['target'],
                            'pencapaianLPJ' => $indikator['pencapaianLPJ']
                        )
                    );
                }
            }
            if (!empty($indikators)) {
                IndikatorTargetLpj::insert($indikators);
            }

            //RINCIAN KEGIATAN
            $rincians = array();
            foreach ($request->input('rincians') as $rincian) {
                if ($rincian['id'] != null) {
                    if ($rincian['isDeleted'] == 'true') {
                        RincianKegiatanLpj::where('rincianKeglpjID', '=', $rincian['id'])->delete();
                    } else {
                        RincianKegiatanLpj::where('rincianKeglpjID', '=', $rincian['id'])->update(array(
                            'lpjID' => $currentLpjID,
                            'rincianDeskripsiLPJ' => $rincian['rincianDeskripsiLPJ'],
                            'tempatLPJ' => $rincian['tempatLPJ'],
                            'waktuMulaiLPJ' => $rincian['waktuMulaiLPJ'],
                            'waktuSelesaiLPJ' => $rincian['waktuSelesaiLPJ'],
                        ));
                    }
                } else {
                    array_push(
                        $rincians,
                        array(
                            'lpjID' => $currentLpjID,
                            'rincianDeskripsiLPJ' => $rincian['rincianDeskripsiLPJ'],
                            'tempatLPJ' => $rincian['tempatLPJ'],
                            'waktuMulaiLPJ' => $rincian['waktuMulaiLPJ'],
                            'waktuSelesaiLPJ' => $rincian['waktuSelesaiLPJ']
                        )
                    );
                }
            }
            if (!empty($rincians)) {
                Log::info('add');
                RincianKegiatanLpj::insert($rincians);
            }

            return redirect()
                ->route('dashboard.lpjs')
                ->with('message', __('message.update', ['propel' => $request->input('propel')]));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lpj  $lpj
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lpj $lpj, Request $request, AnggaranLpj $anggarans, IndikatorTargetLpj $indikators, RincianKegiatanLpj $rincians)
    {
        foreach ($anggarans as $anggaran) {
            $id = $anggaran['id'];
            if ($id != null) {
                AnggaranLpj::where('pengeluaranID', '=', $id)->delete();
            }
        }
        foreach ($indikators as $indikator) {
            $id = $indikator['id'];
            if ($id != null) {
                IndikatorTargetLpj::where('indikatorLpjID', '=', $id)->delete();
            }
        }
        foreach ($rincians as $rincian) {
            $id = $rincian['id'];
            if ($id != null) {
                RincianKegiatanLpj::where('rincianKeglpjID', '=', $id)->delete();
            }
        }
        // TODO: tambah yang lain, ikutin anggaran. Kuncinya di id
        $lpj->delete();

        return redirect()
            ->route('dashboard.lpjs', $request->input('lpjID'))
            ->with('message', __('message.delete', ['propel' => $request->input('propel')]));
    }
}
