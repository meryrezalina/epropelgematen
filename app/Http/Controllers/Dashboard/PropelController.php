<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\DataExport2;
use App\Exports\PropelExport;
use App\Http\Controllers\Controller;
use App\Models\Anggaran;
use App\Models\Bidang;
use App\Models\IndikatorTarget;
use App\Models\Propel;
use App\Models\RincianKegiatan;
use App\Models\SumberDana;
use App\Models\Timpel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PropelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Propel $propel, Anggaran $anggarans, Bidang $bidang)
    {
        $id = $anggarans['id'];

        $active = 'E-Propel';
        $data_fk = Propel::with('bidang', 'timpel');

        // SEARCH
        $q = $request->input('q');
        $propel = $propel
            ->join('bidang', 'bidang.bidangID', '=', 'propel.bidangID')
            ->join('timpel', 'timpel.timpelID', '=', 'propel.timpelID')
            ->select('propel.propelID', 'bidang.namaBidang', 'timpel.namaTimpel', 'propel.namaKegiatan', 'propel.nomorRAPB', 'propel.namaPJ', 'propel.status', 'propel.updated_at')
            ->where('propel.namaKegiatan', 'like', '%' . $q . '%')
            ->orWhere('propel.namaPJ', 'like', '%' . $q . '%')
            ->orWhere('propel.nomorRAPB', 'like', '%' . $q . '%')
            ->orWhere('bidang.namaBidang', 'like', '%' . $q . '%')
            ->orWhere('timpel.namaTimpel', 'like', '%' . $q . '%')
            ->orWhere('propel.status', 'like', '%' . $q . '%')
            ->orWhere('propel.created_at', 'like', '%' . $q . '%')
            ->sortable(['created_at' => 'DESC'])
            ->paginate(20);

        $request = $request->all();

        return view('dashboard/propel/list', compact('data_fk'), [
            'propel' => $propel,
            'request' => $request,
            'bidang' => $bidang,
            'active' => $active,
            'data_fk' => $data_fk, 'anggarans' => $anggarans
        ]);
    }

    public function propelexport()
    {
        $time = Carbon::now();
        return Excel::download(new PropelExport, 'propel ' . $time . '.xlsx');
    }

    public function dataexport2(Propel $propel, Anggaran $propelID)
    {
        $time = Carbon::now();
        return Excel::download(new DataExport2($propel->propelID), 'datapropel ' . $time . '.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active = 'E-Propel';
        $bid = Bidang::all();
        $tim = Timpel::all();
        $pro = Propel::all();
        $sum = SumberDana::all();
        $anggaranpropel = Anggaran::paginate(10);
        $timByBidang = $tim;
        $anggarans = [];
        $indikators = [];
        $rincians = [];

        //return $anggaranpropel;
        return view('dashboard/propel/form', compact('bid', 'tim', 'pro', 'sum', 'anggaranpropel', 'timByBidang', 'anggarans', 'indikators', 'rincians'), [
            'button' => 'Create',
            'url' => 'dashboard.propel.store',
            'active' => $active,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Propel $propel)
    {
        $validator = Validator::make($request->all(), [
            'bidangID' => 'required',
            'namaKegiatan' => 'required',
            'namaPJ' => 'required',
            'sasaranStrategis' => 'required',
            'status' => 'required',
            'anggarans' => 'required',
            'indikators' => 'required',
            'rincians' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->route('dashboard.propel.create')
                ->withErrors($validator)
                ->withInput();
        } else {
            $idPropel = Propel::max('propelID');
            Log::info(json_encode($request->input('anggarans')));

            $propel->bidangID = $request->input('bidangID');
            $propel->timpelID = $request->input('timpelID');
            $propel->namaKegiatan = $request->input('namaKegiatan');
            $propel->nomorRAPB = $request->input('nomorRAPB');
            $propel->namaPJ = $request->input('namaPJ');
            $propel->sasaranStrategis = $request->input('sasaranStrategis');
            $propel->status = $request->input('status');
            $propel->save();

            //ANGGARAN INSERT
            $currentPropelID = $propel->propelID;
            $anggarans = array();
            foreach ($request->input('anggarans') as $anggaran) {
                array_push(
                    $anggarans,
                    array(
                        'propelID' => $currentPropelID,
                        'anggaranDeskripsi' => $anggaran['anggaranDeskripsi'],
                        'hargaSatuan' => $anggaran['hargaSatuan'],
                        'kuantitas' => $anggaran['kuantitas'],
                        'sumberID' => $anggaran['sumberID']
                    )
                );
            }
            Anggaran::insert($anggarans);

            //INDIKATOR TARGET INSERT
            $indikators = array();
            foreach ($request->input('indikators') as $indikator) {
                array_push(
                    $indikators,
                    array(
                        'propelID' => $currentPropelID,
                        'indikatorDeskripsi' => $indikator['indikatorDeskripsi'],
                        'target' => $indikator['target']
                    )
                    // 'pencapaianLPJ'         => $indikator['pencapaianLPJ'])
                );
            }
            IndikatorTarget::insert($indikators);

            // RINCIAN KEGIATAN INSERT
            $rincians = array();
            foreach ($request->input('rincians') as $rincian) {
                $parseWaktuMulai = $rincian['waktuMulai'];
                $parseWaktuSelesai = $rincian['waktuSelesai'];
                $stringWaktuMulai = strstr($parseWaktuMulai, "(", true);
                $stringWaktuSelesai = strstr($parseWaktuSelesai, "(", true);
                array_push(
                    $rincians,
                    array(
                        'propelID' => $currentPropelID,
                        'rincianDeskripsi' => $rincian['rincianDeskripsi'],
                        'tempat' => $rincian['tempat'],
                        'waktuMulai' => Carbon::parse($stringWaktuMulai)->format('Y-m-d'),
                        'waktuSelesai' => Carbon::parse($stringWaktuSelesai)->format('Y-m-d')
                    )
                );
            }
            RincianKegiatan::insert($rincians);

            return redirect()
                ->route('dashboard.propel')
                ->with('message', __('message.create', ['propel' => $request->input('propel')]));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Propel  $propel
     * @return \Illuminate\Http\Response
     */
    public function show(Propel $propel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Propel  $propel
     * @return \Illuminate\Http\Response
     */
    public function edit(Propel $propel)
    {
        $active = 'Propel';
        $bid = Bidang::all();
        $tim = Timpel::all();
        $timByBidang = Timpel::where('bidangID', '=', $propel->bidangID)->get();
        $propels = Propel::get();
        $anggaranpropel = '';
        $anggarans = $propel->anggaran()->get();
        $sum = SumberDana::all();
        $indikators = $propel->indikatorTarget()->get();
        $rincians = $propel->rincianKegiatan()->get();
        return view(
            'dashboard/propel/form',
            compact('bid', 'tim', 'anggaranpropel', 'timByBidang', 'sum', 'anggarans', 'indikators', 'rincians'),
            [
                'active' => $active,
                'propel' => $propel,
                'propels' => $propels,
                'button' => 'Update',
                'url' => 'dashboard.propel.update',
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Propel  $propel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Propel $propel)
    {
        $validator = Validator::make($request->all(), [
            'bidangID' => 'required',
            'namaKegiatan' => 'required',
            'namaPJ' => 'required',
            'sasaranStrategis' => 'required',
            'status' => 'required',
            'anggarans' => 'required',
            'indikators' => 'required',
            'rincians' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->route('dashboard.propel.create')
                ->withErrors($validator)
                ->withInput();
        } else {
            Log::info("start update");
            Log::info(json_encode($request->input('anggarans')));

            $propel->bidangID = $request->input('bidangID');
            $propel->timpelID = $request->input('timpelID');
            $propel->namaKegiatan = $request->input('namaKegiatan');
            $propel->nomorRAPB = $request->input('nomorRAPB');
            $propel->namaPJ = $request->input('namaPJ');
            $propel->sasaranStrategis = $request->input('sasaranStrategis');
            $propel->status = $request->input('status');
            $propel->save();

            //ANGGARAN
            $currentPropelID = $propel->propelID;
            Log::info($currentPropelID);
            $anggarans = array();
            foreach ($request->input('anggarans') as $anggaran) {
                if ($anggaran['id'] != null) {
                    if ($anggaran['isDeleted'] == 'true') {
                        Anggaran::where('anggaranPropelID', '=', $anggaran['id'])->delete();
                    } else {
                        Anggaran::where('anggaranPropelID', '=', $anggaran['id'])->update(
                            array(
                                'propelID' => $currentPropelID,
                                'anggaranDeskripsi' => $anggaran['anggaranDeskripsi'],
                                'hargaSatuan' => $anggaran['hargaSatuan'],
                                'kuantitas' => $anggaran['kuantitas'],
                                'sumberID' => $anggaran['sumberID']
                            )
                        );
                    }
                } else {
                    array_push(
                        $anggarans,
                        array(
                            'propelID' => $currentPropelID,
                            'anggaranDeskripsi' => $anggaran['anggaranDeskripsi'],
                            'hargaSatuan' => $anggaran['hargaSatuan'],
                            'kuantitas' => $anggaran['kuantitas'],
                            'sumberID' => $anggaran['sumberID']
                        )
                    );
                }
            }
            if (!empty($anggarans)) {
                Anggaran::insert($anggarans);
            }

            Log::info("indikators");
            Log::info(json_encode($request->input('indikators')));

            //INDIKATOR TARGET
            $indikators = array();
            foreach ($request->input('indikators') as $indikator) {
                if ($indikator['id'] != null) {
                    Log::info('id not null');
                    if ($indikator['isDeleted'] == 'true') {
                        IndikatorTarget::where('indikatorPropelID', '=', $indikator['id'])->delete();
                    } else {
                        IndikatorTarget::where('indikatorPropelID', '=', $indikator['id'])->update(array(
                            'propelID' => $currentPropelID,
                            'indikatorDeskripsi' => $indikator['indikatorDeskripsi'],
                            'target' => $indikator['target'],
                            // 'pencapaianLPJ' => $indikator['pencapaianLPJ'],
                        ));
                    }
                } else {
                    Log::info('id null');
                    array_push(
                        $indikators,
                        array(
                            'propelID' => $currentPropelID,
                            'indikatorDeskripsi' => $indikator['indikatorDeskripsi'],
                            'target' => $indikator['target'],
                            'pencapaianLPJ' => $indikator['pencapaianLPJ']
                        )
                    );
                }
            }
            Log::info($indikators);
            if (!empty($indikators)) {
                Log::info('indikators empty');
                IndikatorTarget::insert($indikators);
            }

            //RINCIAN KEGIATAN
            $rincians = array();
            foreach ($request->input('rincians') as $rincian) {
                if ($rincian['id'] != null) {
                    if ($rincian['isDeleted'] == 'true') {
                        RincianKegiatan::where('rincianPropelID', '=', $rincian['id'])->delete();
                    } else {
                        RincianKegiatan::where('rincianPropelID', '=', $rincian['id'])->update(array(
                            'propelID' => $currentPropelID,
                            'rincianDeskripsi' => $rincian['rincianDeskripsi'],
                            'tempat' => $rincian['tempat'],
                            'waktuMulai' => $rincian['waktuMulai'],
                            'waktuSelesai' => $rincian['waktuSelesai'],
                        ));
                    }
                } else {
                    array_push(
                        $rincians,
                        array(
                            'propelID' => $currentPropelID,
                            'rincianDeskripsi' => $rincian['rincianDeskripsi'],
                            'tempat' => $rincian['tempat'],
                            'waktuMulai' => $rincian['waktuMulai'],
                            'waktuSelesai' => $rincian['waktuSelesai']
                        )
                    );
                }
            }
            if (!empty($rincians)) {
                RincianKegiatan::insert($rincians);
            }

            return redirect()
                ->route('dashboard.propel')
                ->with('message', __('message.update', ['propel' => $request->input('propel')]));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Propel  $propel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Propel $propel, Anggaran $anggarans, IndikatorTarget $indikators, RincianKegiatan $rincians)
    {
        foreach ($anggarans as $anggaran) {
            $id = $anggaran['id'];
            if ($id != null) {
                Anggaran::where('anggaranPropelID', '=', $id)->delete();
            }
        }
        foreach ($indikators as $indikator) {
            $id = $indikator['id'];
            if ($id != null) {
                IndikatorTarget::where('indikatorPropelID', '=', $id)->delete();
            }
        }
        foreach ($rincians as $rincian) {
            $id = $rincian['id'];
            if ($id != null) {
                RincianKegiatan::where('rincianPropelID', '=', $id)->delete();
            }
        }
        // TODO: tambah yang lain, ikutin anggaran. Kuncinya di id
        $propel->delete();

        return redirect()
            ->route('dashboard.propel', $request->input('propelID'))
            ->with('message', __('message.delete', ['propel' => $request->input('propel')]));
    }
}
