<?php

namespace App\Http\Controllers\Dashboard;

use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $users, Request $request)
    {
        $active = 'Profil';

        return view('dashboard/profil/form',
        [
            'active' => $active,
            'button' => 'Update',
            'url'    => 'dashboard.profil'
        ]);
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'pass_lama' => 'required|min:6|max:100',
            'pass_baru' => 'required|min:6|max:100',
            'konfirmasi' => 'required|same:pass_baru'
        ]);

        $current_user = auth()->user();
        if(Hash::check($request->pass_lama, $current_user->password)){
            $current_user->update([
                'password'=>bcrypt($request->pass_baru)
            ]);

            return redirect()->back()->with('success', 'Update Password berhasil');

        }else{
            return redirect()->back()->with('error', 'Password Lama tidak cocok');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $users)
    {
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        Auth::user()->name = $request->input('name');
        Auth::user()->email = $request->input('email');
        Auth::user()->save();

        return redirect()->back()->with('success_update', 'Update berhasil');
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
