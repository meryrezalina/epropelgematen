<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\Proposal;
use App\Models\Timpel;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    use RegistersUsers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $users, Request $request)
    {
        $active = 'Users';
        $q = $request->input('q');

        $users = $users->when($q, function ($query) use ($q) {
            return $query->where('name', 'like', '%' . $q . '%')
                ->orWhere('email', 'like', '%' . $q . '%');
        })->paginate(10);

        $request = $request->all();
        $data_fk = Proposal::with('bidang', 'timpel');

        //return $users;

        return view('dashboard/users/list', compact('data_fk'),
            ['users' => $users,
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
        $active = 'Users';
        $bid = Bidang::all();
        $tim = Timpel::all();
        return view('dashboard/users/form', compact('bid', 'tim'), [
            'button' => 'Create',
            'url' => 'dashboard.users.store',
            'active' => $active,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $users)
    {
        Log::info('store');
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:100',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            Log::info('validation faield');
            return redirect()
                ->route('dashboard.users.create')
                ->withErrors($validator)
                ->withInput();
        } else {

            // $users->name                = $request->input('name');
            // $users->email               = $request->input('email');
            // $users->bidangID            = $request->input('bidangID');
            // $users->timpelID            = $request->input('timpelID');
            // $users->password            = $request->input('password');
            // $users->konfirmasi          = $request->input('konfirmasi');
            // $users->save();
            Log::info('create');
            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'bidangID' => $request->input('bidangID'),
                'timpelID' => $request->input('timpelID'),
                'role' => $request->input('role'),
            ], );
            return redirect()
                ->route('dashboard.users')
                ->with('message', __('message.create', ['propel' => $request->input('propel')]));

        }
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
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $users)
    {
        User::where('id', $users)->delete();
        return "berhasil hapus";

        return redirect()
            ->route('dashboard.users');
    }
}
