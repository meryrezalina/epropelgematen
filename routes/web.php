<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function () {

//Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/dashboard/profils', 'Dashboard\ProfilController@index')->name('dashboard.profils');

//DASHBOARD
    Route::get('/', 'Dashboard\DashboardController@index')->name('dashboard');
    Route::post('/dashboard', 'Dashboard\DashboardController@search')->name('dashboard.search');

//E-PROPOSAL
    Route::get('/dashboard/proposal', 'Dashboard\ProposalController@index')->name('dashboard.proposals');
    Route::get('/dashboard/proposal/create', 'Dashboard\ProposalController@create')->name('dashboard.proposal.create');
    Route::post('/dashboard/proposal', 'Dashboard\ProposalController@store')->name('dashboard.proposal.store');
    Route::get('/dashboard/proposal/{proposals}', 'Dashboard\ProposalController@edit')->name('dashboard.proposal.edit');
    Route::put('/dashboard/proposal/{proposals}', 'Dashboard\ProposalController@update')->name('dashboard.proposal.update');
    Route::delete('/dashboard/proposal/{proposals}', 'Dashboard\ProposalController@destroy')->name('dashboard.proposal.delete');
    Route::get('/dashboard/proposalexport', 'Dashboard\ProposalController@proposalexport')->name('dashboard.proposalexport');
    Route::get('/dashboard/proposallist/{proposals}', 'Dashboard\ProposalController@proposallist')->name('dashboard.proposallist');

//E-LPJ
    Route::get('/dashboard/lpj', 'Dashboard\LpjController@index')->name('dashboard.lpjs');
    Route::get('/dashboard/lpj/create', 'Dashboard\LpjController@create')->name('dashboard.lpj.create');
    Route::post('/dashboard/lpj', 'Dashboard\LpjController@store')->name('dashboard.lpj.store');
    Route::get('/dashboard/lpj/{lpj}', 'Dashboard\LpjController@edit')->name('dashboard.lpj.edit');
    Route::put('/dashboard/lpj/{lpj}', 'Dashboard\LpjController@update')->name('dashboard.lpj.update');
    Route::delete('/dashboard/lpj/{lpj}', 'Dashboard\LpjController@destroy')->name('dashboard.lpj.delete');

//E-PROPEL

    Route::get('/dashboard/propel', 'Dashboard\PropelController@index')->name('dashboard.propel');
    Route::get('/dashboard/propel/create', 'Dashboard\PropelController@create')->name('dashboard.propel.create');
    Route::post('/dashboard/propel', 'Dashboard\PropelController@store')->name('dashboard.propel.store');
    Route::get('/dashboard/propel/{propel}', 'Dashboard\PropelController@edit')->name('dashboard.propel.edit');
    Route::put('/dashboard/propel/{propel}', 'Dashboard\PropelController@update')->name('dashboard.propel.update');
    Route::delete('/dashboard/propel/{propel}', 'Dashboard\PropelController@destroy')->name('dashboard.propel.delete');
    Route::get('/dashboard/propelexport', 'Dashboard\PropelController@propelexport')->name('dashboard.propelexport');
    Route::get('/dashboard/dataexport2/{propel}', 'Dashboard\PropelController@dataexport2')->name('dashboard.dataexport2');
    Route::get('/dashboard/cobaexport', 'Dashboard\PropelController@cobaexport')->name('dashboard.cobaexport');

//PROFIL
    Route::get('/dashboard/profil', 'Dashboard\ProfilController@index')->name('dashboard.profil');
// Route::get('/dashboard/profil', 'Dashboard\ProfilController@update')->name('dashboard.profil.update');
    Route::post('/dashboard/profil/updatepassword', 'Dashboard\ProfilController@update_password')->name('dashboard.profil.update_password');
    Route::post('/dashboard/profil', 'Dashboard\ProfilController@update')->name('dashboard.profil.update');

//USERS
    Route::get('/dashboard/users', 'Dashboard\UsersController@index')->name('dashboard.users');
    Route::get('/dashboard/users/create', 'Dashboard\UsersController@create')->name('dashboard.users.create');
    Route::post('/dashboard/users', 'Dashboard\UsersController@store')->name('dashboard.users.store');
    Route::get('/dashboard/users/delete/{user}', 'Dashboard\UsersController@destroy')->name('dashboard.users.delete');

//PDF
    Route::get('dashboard/download_public', 'Dashboard\PdfController@download_public')->name('dashboard.pdf');
});
