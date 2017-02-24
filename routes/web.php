<?php

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


Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::resource('golongan','golonganController');

Route::resource('jabatan','jabatanController');

Route::resource('kategori_lembur','kategori_lemburController');

Route::resource('pegawai','pegawaiController');

Route::resource('tunjangan','tunjanganController');

Route::resource('lembur_pegawai','lembur_pegawaiController');

Route::resource('tunjangan_pegawai','tunjangan_pegawaiController');

Route::resource('penggajian','penggajianController');

Route::get('query', 'CariController@search');

