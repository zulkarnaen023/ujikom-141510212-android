<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    protected $table = 'penggajians';
    protected $fillable = ['tunjangan_pegawai_id', 'jumlah_jam_lembur', 'jumlah_uang_lembur', 'gaji_pokok', 'total_gaji', 'tanggal_pengambilan', 'status_pengambilan', 'petugas_penerima'];
    protected $visible = ['tunjangan_pegawai_id', 'jumlah_jam_lembur', 'jumlah_uang_lembur', 'gaji_pokok', 'total_gaji', 'tanggal_pengambilan', 'status_pengambilan', 'petugas_penerima'];
    public $timestamps = true;

    public function Tunjangan_pegawai(){
    	return $this->belongsTo('App\Tunjangan_pegawai', 'tunjangan_pegawai_id');
    }
}
