<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Penggajian;
use App\Models\Tunjangan_Pegawai;
use App\Models\Tunjangan;
use App\Models\Jabatan;
use App\Models\Golongan;
use App\Models\Kategori_lembur;
use App\Models\Lembur_pegawai;
use Input;
use auth ;
use App\Models\Pegawai;
use Carbon\Carbon;
class Penggajian_Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function __construct()
    {
        $this->middleware('keuangan');
    }
    public function index()
    {
      $pegawai=Pegawai::all();
         $penggajian=Penggajian::orderby('id','desc')->paginate(5);
        
        if(request()->has('nip')){
            $pegawai=Pegawai::where('nip',request('nip'))->paginate(0);
        }
        return view('penggajian.index',compact('penggajian','pegawai')); 
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $tunjangan=Tunjangan_pegawai::paginate(10);
          $lembur_pegawai=Lembur_pegawai::selectRaw("sum(lembur_pegawais.jumlah_jam) as jumlah_jam,lembur_pegawais.kode_lembur_id as kode_lembur_id,lembur_pegawais.pegawai_id as pegawai_id")->GroupBy('kode_lembur_id','pegawai_id')->get();
        return view('penggajian.create',compact('tunjangan','lembur_pegawai'));     }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $penggajian=Input::all();
         //dd($penggajian);
        $where=Tunjangan_Pegawai::where('id',$penggajian['tunjangan_pegawai_id'])->first();
        //dd($where);
        $wherepenggajian=Penggajian::where('tunjangan_pegawai_id',$where->id)->first();
        //dd($wherepenggajian);
        $wheretunjangan=Tunjangan::where('id',$where->kode_tunjangan_id)->first();
        // dd($where);
        $wherepegawai=Pegawai::where('id',$where->pegawai_id)->first();
        //dd($wherepegawai);
        $wherekategorilembur=Kategori_lembur::where('jabatan_id',$wherepegawai->jabatan_id)->where('golongan_id',$wherepegawai->golongan_id)->first();
        //dd($wherekategorilembur);
        $wherelemburpegawai=Lembur_pegawai::where('pegawai_id',$wherepegawai->id)->first();
          
       
        // dd($wherelemburpegawai);
        $wherejabatan=Jabatan::where('id',$wherepegawai->jabatan_id)->first();
        // dd($wherejabatan);
        $wheregolongan=Golongan::where('id',$wherepegawai->golongan_id)->first();
        // dd($wheregolongan);
        $penggajian=new Penggajian ;
        $now=Carbon::now();
       
        if ($wherepenggajian->created_at->addDays(30)==$now->addDays(30)) {
        $tunjangan=Tunjangan_Pegawai::paginate(10);
            $error=true ;
            
            return view('penggajian.create',compact('tunjangan','error','trialExpires'));
        }
        elseif (!isset($wherelemburpegawai)) {
            $nol=0 ;
            $penggajian->jumlah_jam_lembur=$nol;
            $penggajian->jumlah_uang_lembur=$nol ;
             $penggajian->status_pengembalian=$request->get('status_pengembalian') ;
            $penggajian->gaji_pokok=$wherejabatan->besaran_uang+$wheregolongan->besaran_uang;
            $penggajian->total_gaji=($wheretunjangan->besaran_uang)+($wherejabatan->besaran_uang+$wheregolongan->besaran_uang);
        $penggajian->tunjangan_pegawai_id=Input::get('tunjangan_pegawai_id');
        $penggajian->petugas_penerima=auth::user()->name ;
        $penggajian->save();
        }
        elseif (!isset($wherelemburpegawai)||!isset($wherekategorilembur)) {
            $nol=0 ;
            $penggajian->jumlah_jam_lembur=$nol;
            $penggajian->jumlah_uang_lembur=$nol ;
              $penggajian->status_pengembalian=$request->get('status_pengembalian') ;
            $penggajian->gaji_pokok=$wherejabatan->besaran_uang+$wheregolongan->besaran_uang;
            $penggajian->total_gaji=($wheretunjangan->besaran_uang)+($wherejabatan->besaran_uang)+($wheregolongan->besaran_uang);
        $penggajian->tunjangan_pegawai_id=Input::get('tunjangan_pegawai_id');
        $penggajian->petugas_penerima=auth::user()->name ;
        $penggajian->save();
        }
       else{
            $nol=0 ;
            $penggajian->jumlah_jam_lembur=$nol;
            $penggajian->jumlah_uang_lembur=$nol ;
          foreach ($wherepegawai->lembur_pegawai as $data) {
                $penggajian['jumlah_jam_lembur']+=$data->jumlah_jam;
                $penggajian['jumlah_uang_lembur']+=$wherekategorilembur->besaran_uang*$data->jumlah_jam;
              $penggajian->status_pengembalian=$request->get('status_pengembalian') ;
            $penggajian->gaji_pokok=$wherejabatan->besaran_uang+$wheregolongan->besaran_uang;
            $penggajian->total_gaji=( $penggajian['jumlah_jam_lembur']*$wherekategorilembur->besaran_uang)+($wheretunjangan->besaran_uang)+($wherejabatan->besaran_uang)+($wheregolongan->besaran_uang);
             }
            $penggajian->tanggal_pengambilan =Null;
            $penggajian->tunjangan_pegawai_id=Input::get('tunjangan_pegawai_id');
            $penggajian->petugas_penerima=auth::user()->name ;
            $penggajian->save();
            }
            return redirect('penggajian');
                 }
            
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
            $datapenggajian=penggajian::find($id);
        return view('penggajian.read',compact('datapenggajian'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $penggajian=Penggajian::find($id);
         $pegawai=Pegawai::all();
        return view('penggajian.edit',compact('tunjangan','penggajian'));     }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $penggajian = Penggajian::find($id);
            $penggajian->tunjangan_pegawai_id = $request->get('tunjangan_pegawai_id');
            $penggajian->jumlah_jam_lembur = $request->get('jumlah_jam_lembur');
            $penggajian->jumlah_uang_lembur = $request->get('jumlah_uang_lembur');
              $penggajian->gaji_pokok = $request->get('gaji_pokok');
                $penggajian->total_gaji = $request->get('total_gaji');
                  $penggajian->tanggal_pengambilan = $request->get('tanggal_pengambilan');
                    $penggajian->status_pengembalian = $request->get('status_pengembalian');
                           $penggajian->petugas_penerima = $request->get('petugas_penerima');
            $penggajian->save();
             return redirect('penggajian');    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $penggajian=Penggajian::find($id)->delete();
        return redirect('penggajian');
            }
}