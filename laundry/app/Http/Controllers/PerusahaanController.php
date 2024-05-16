<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //query data
        $perusahaan = Perusahaan::all();
        return view('perusahaan.view',
                    [
                        'perusahaan' => $perusahaan
                    ]
                  );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // berikan kode perusahaan secara otomatis
        // 1. query dulu ke db, select max untuk mengetahui posisi terakhir 
        
        return view('perusahaan/create',
        [
            'kode_perusahaan' => Perusahaan::getKodePerusahaan()
        ]
      );
        // return view('perusahaan/view');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru disimpan ke db
        $validated = $request->validate([
            'kode_perusahaan' => 'required',
            'nama_perusahaan' => 'required|unique:perusahaan|min:5|max:255',
            'alamat_perusahaan' => 'required',
        ]);

        // masukkan ke db
        Perusahaan::create($request->all());
        
        return redirect()->route('perusahaan.index')->with('success','Data Berhasil di Input');
    }

    /**
     * Display the specified resource.
     */
    public function show(Perusahaan $perusahaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perusahaan $perusahaan)
    {
        return view('perusahaan.edit', compact('perusahaan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perusahaan $perusahaan)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru diupdate ke db
        $validated = $request->validate([
            'kode_perusahaan' => 'required',
            'nama_perusahaan' => 'required|max:255',
            'alamat_perusahaan' => 'required',
        ]);
    
        $perusahaan->update($validated);
    
        return redirect()->route('perusahaan.index')->with('success','Data Berhasil di Ubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //hapus dari database
        $perusahaan = Perusahaan::findOrFail($id);
        $perusahaan->delete();

        return redirect()->route('perusahaan.index')->with('success','Data Berhasil di Hapus');
    }
}