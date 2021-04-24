<?php

namespace App\Http\Controllers\import;

use App\imports\PenelitianImports;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use App\Pegawai;
use App\Dosen;
use App\Penelitian;
use App\Penulis;
use App\DetailPenelitian;

class ImportPenelitianController extends Controller
{
    //
    public function show(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $datapenelitian = NULL;
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $data = Dosen::get();
            return view('admin.penelitian.penelitian-import', compact('datapenelitian','data','profiledata'));
        }
    }

    public function view(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $file = $request->file('inputfile');
        
            $rows = Excel::ToCollection(new PenelitianImports, $file);
            $datapenelitian = $rows['0'];
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $data = Dosen::get();
            
            return view('admin.penelitian.penelitian-import', compact('datapenelitian','data','profiledata'),['success'=>'Berhasil Meload Data Excel']);
        }
    }

    public function save(Request $request){
        $counts = count($request->row_nama);
        for ($count = 0; $count < $counts; $count++) {
            $checkPenelitian = Penelitian::where('judul', '=', $request->row_judul[$count])->first();
            if($checkPenelitian == null){
                $storePenelitian = new Penelitian;
                $storePenelitian->judul = $request->row_judul[$count];
                $storePenelitian->tahun_publikasi = $request->row_tahunpub[$count];
                $storePenelitian->kategori = $request->row_kategori[$count];
                $storePenelitian->tahun_ajaran = $request->row_tahunajar[$count];
                $storePenelitian->unit = $request->row_unit[$count];
                $storePenelitian->sunit = $request->row_sunit[$count];
                $storePenelitian->file_1 = $request->row_file1[$count];
                $storePenelitian->file_2 = $request->row_file2[$count];
                $storePenelitian->save();
                
                $dosen= Dosen::where('nip', '=' , $request->row_nip[$count], 'OR', 'nama', '=', $request->row_nama[$count])->first();
                $checkPenulis = Penulis::where('id_dosen', '=', $request->row_nip[$count], 'OR', 'nama_penulis', '=', $request->row_nama[$count] )->first();
                    if($checkPenulis ==  null){
                        if($dosen != null){
                    
                            $penelitian = Penelitian::where('judul', '=', $request->row_judul[$count])->first();
                            $storePenulis = new Penulis;
                            $storePenulis->role = 'Dosen';
                            $storePenulis->id_dosen = $dosen->nip;
                            $storePenulis->nama_penulis = $dosen->nama;
                            $storePenulis->save();
                            
                            $penulis = Penulis::where('id_dosen', $dosen->nip)->first();
                            $detailPenelitian = new DetailPenelitian;
                            $detailPenelitian->id_penelitian = $penelitian->id_penelitian;
                            $detailPenelitian->id_penulis = $penulis->id_penulis;
                            $detailPenelitian->save();
                        }else{
                            $penelitian = Penelitian::where('judul', '=', $request->row_judul[$count])->first();
                            $storePenulis = new Penulis;
                            $storePenulis->id_dosen = $request->row_nip[$count];
                            $storePenulis->nama_penulis = $request->row_nama[$count];
                            $storePenulis->save();
            
                            $penulis = Penulis::where('id_dosen', $request->row_nip[$count])->first();
                            $detailPenelitian = new DetailPenelitian;
                            $detailPenelitian->id_penelitian = $penelitian->id_penelitian;
                            $detailPenelitian->id_penulis = $penulis->id_penulis;
                            $detailPenelitian->save();
                        }
                    }else{
                        $penelitian = Penelitian::where('judul', '=', $request->row_judul[$count])->first();
                        $penulis = Penulis::where('id_dosen', $request->row_nip[$count])->first();
                        $detailPenelitian = new DetailPenelitian;
                        $detailPenelitian->id_penelitian = $penelitian->id_penelitian;
                        $detailPenelitian->id_penulis = $penulis->id_penulis;
                        $detailPenelitian->save();
                    }
            }else{
                $checkPenulis = Penulis::where('id_dosen', '=', $request->row_nip[$count], 'OR', 'nama_penulis', '=', $request->row_nama[$count] )->first();
                    if($checkPenulis ==  null){
                        if($dosen != null){
                    
                            $penelitian = Penelitian::where('judul', '=', $request->row_judul[$count])->first();
                            $storePenulis = new Penulis;
                            $storePenulis->role = 'Dosen';
                            $storePenulis->id_dosen = $dosen->nip;
                            $storePenulis->nama_penulis = $dosen->nama;
                            $storePenulis->save();
                            
                            $penulis = Penulis::where('id_dosen', $dosen->nip)->first();
                            $detailPenelitian = new DetailPenelitian;
                            $detailPenelitian->id_penelitian = $penelitian->id_penelitian;
                            $detailPenelitian->id_penulis = $penulis->id_penulis;
                            $detailPenelitian->save();
                        }else{
                            $penelitian = Penelitian::where('judul', '=', $request->row_judul[$count])->first();
                            $storePenulis = new Penulis;
                            $storePenulis->id_dosen = $request->row_nip[$count];
                            $storePenulis->nama_penulis = $request->row_nama[$count];
                            $storePenulis->save();
            
                            $penulis = Penulis::where('id_dosen', $request->row_nip[$count])->first();
                            $detailPenelitian = new DetailPenelitian;
                            $detailPenelitian->id_penelitian = $penelitian->id_penelitian;
                            $detailPenelitian->id_penulis = $penulis->id_penulis;
                            $detailPenelitian->save();
                        }
                    }else{
                        $penelitian = Penelitian::where('judul', '=', $request->row_judul[$count])->first();
                        $penulis = Penulis::where('id_dosen', $request->row_nip[$count])->first();
                        $checkDetail = DetailPenelitian::where('id_penelitian', '=', $penelitian->id_penelitian, 'AND' , 'id_penulis', '=', $penulis->id_penulis)->first();
                        if($checkDetail == null){
                            $detailPenelitian = new DetailPenelitian;
                            $detailPenelitian->id_penelitian = $penelitian->id_penelitian;
                            $detailPenelitian->id_penulis = $penulis->id_penulis;
                            $detailPenelitian->save();
                        }else{
                            return redirect()->route('show-import-penelitian')->with('success','Berhasil Mengupload Data');
                        }
                        
                    }
            }
            
        }
        return redirect()->route('show-import-penelitian')->with('success','Berhasil Mengupload Data');
    }

    public function delete(Request $request, $id){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
        $data = $request;
        $datapenelitian = $data['nama'] != $id;
        dd($datapenelitian);
        return view('admin.penelitian.penelitian-import', compact('datapenelitian','data','profiledata'),['success'=>'Berhasil Meload Data Excel']);
        }
        
        
        // $datapenelitian = $this->globalpenelitian;
        // foreach($datapenelitian as $d){
        //     $i = 1;
        //     if ($i != $id){
        //         continue;
        //     }elseif{
        //         $d = next($d);
        //     }
        // }
    }
}
