<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Propel;
use App\Models\Anggaran;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Anggaran $anggaranpropel)
    {
        $q = $request->input('q');

        $active = 'Anggaran Propel';

        $anggaranpropel = $anggaranpropel->when($q, function ($query) use ($q) {
            return $query->where('namaKegiatan', 'like', '%' . $q . '%');
        })
            ->paginate(20);

        $data_fk = Anggaran::with('propel', 'sumberdana');

        $request = $request->all();

        return view('dashboard/anggaran/list', compact('data_fk'), ['anggaranpropel' => $anggaranpropel,
            'request' => $request,
            'active' => $active,
            'data_fk' => $data_fk]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active = 'Anggaran Propel';
        $pro = Propel::all();
        $sum = SumberDana::all();

        return view('dashboard/anggaran/form', compact('pro', 'sum'), [
            'button' => 'Create',
            'url' => 'dashboard.anggaran.store',
            'active' => $active,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Anggaran  $anggaran
     * @return \Illuminate\Http\Response
     */
    public function show(Anggaran $anggaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Anggaran  $anggaran
     * @return \Illuminate\Http\Response
     */
    public function edit(Anggaran $anggaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Anggaran  $anggaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Anggaran $anggaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Anggaran  $anggaran
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anggaran $anggaran)
    {
        //
    }
}
