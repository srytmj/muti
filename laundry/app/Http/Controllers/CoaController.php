<?php

namespace App\Http\Controllers;

use App\Models\coa;
use Illuminate\Http\Request;
use App\Http\Requests\StorecoaRequest;
use App\Http\Requests\UpdatecoaRequest;

class CoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('coa/view', [
            'coa' => Coa::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('coa/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pengecekan = $request->validate([
            'kode_akun' => 'required',
            'nama_akun' => 'required',
            'header_akun' => 'required'
        ]);

        coa::create($pengecekan);

        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(coa $coa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(coa $coa)
    {
        return view('coa/edit',[
            'coa' => $coa
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, coa $coa)
    {
        $pengecekan = $request->validate([
            'kode_akun' => 'required',
            'nama_akun' => 'required',
            'header_akun' => 'required'
        ]);

        coa::where('id', $coa->id)
        ->update($pengecekan);
        return redirect('/coa');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(coa $coa)
    {
        coa::destroy('id', $coa->id);
        return redirect('/');
    }
}