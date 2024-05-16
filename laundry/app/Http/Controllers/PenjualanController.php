<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Http\Requests\StorePenjualanRequest;
use App\Http\Requests\UpdatePenjualanRequest;

// untuk validator
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Auth; //untuk mendapatkan auth

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        // getViewBarang()
        $barang = Penjualan::getBarang();
        $id_pelanggan = Auth::id(); //dapatkan id pelanggan dari sesi user
        return view('penjualan.view',
                [
                    'barang' => $barang,
                    'jml' => Penjualan::getJumlahKg($id_pelanggan),
                    'jml_invoice' => Penjualan::getJmlInvoice($id_pelanggan),
                ]
        );
    }

    // dapatkan data barang berdasarkan id barang
    public function getDataBarang($id){
        $barang = Penjualan::getBarangId($id);
        if($barang)
        {
            return response()->json([
                'status'=>200,
                'barang'=> $barang,
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Tidak ada data ditemukan.'
            ]);
        }
    }

    // dapatkan data barang berdasarkan id barang
    public function getDataBarangAll(){
        $barang = Penjualan::getBarang();
        if($barang)
        {
            return response()->json([
                'status'=>200,
                'barang'=> $barang,
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Tidak ada data ditemukan.'
            ]);
        }
    }

    // dapatkan jumlah barang untuk keranjang
    public function getJumlahKg(){
        $id_pelanggan = Auth::id();
        $jml_kg = Penjualan::getJumlahKg($id_pelanggan);
        if($jml_barang)
        {
            return response()->json([
                'status'=>200,
                'jumlah'=> $jml_kg,
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Tidak ada data ditemukan.'
            ]);
        }
    }

    // dapatkan jumlah barang untuk keranjang
    public function getInvoice(){
        $id_pelanggan = Auth::id();
        $jml_kg = Penjualan::getJmlInvoice($id_pelanggan);
        if($jml_kg)
        {
            return response()->json([
                'status'=>200,
                'jmlinvoice'=> $jml_kg,
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Tidak ada data ditemukan.'
            ]);
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
     * @param  \App\Http\Requests\StorePenjualanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePenjualanRequest $request)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru disimpan ke db
        $validator = Validator::make(
            $request->all(),
            [
                'jumlah' => 'required',
            ]
        );
        
        if($validator->fails()){
            // gagal
            return response()->json(
                [
                    'status' => 400,
                    'errors' => $validator->messages(),
                ]
            );
        }else{
            // berhasil

            // cek apakah tipenya input atau update
            // input => tipeproses isinya adalah tambah
            // update => tipeproses isinya adalah ubah
            
            if($request->input('tipeproses')=='tambah'){

                $id_pelanggan = Auth::id();
                $jml_kg = $request->input('jumlah');
                $id_barang = $request->input('idbaranghidden');

                $brg = Penjualan::getBarangId($id_barang);
                foreach($brg as $b):
                    $harga_barang = $b->harga;
                endforeach;

                $total_harga = $harga_barang*$jml_kg;
                Penjualan::inputPenjualan($id_pelanggan,$total_harga,$id_barang,$jml_kg,$harga_barang,$total_harga);

                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'Sukses Input Data',
                    ]
                );
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function show(Penjualan $penjualan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function edit(Penjualan $penjualan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePenjualanRequest  $request
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePenjualanRequest $request, Penjualan $penjualan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penjualan $penjualan)
    {
        //
    }

    // view keranjang
    public function keranjang(){
        $id_pelanggan = Auth::id();
        $keranjang = Penjualan::viewKeranjang($id_pelanggan);
        return view('penjualan/viewkeranjang',
                [
                    'keranjang' => $keranjang
                ]
        );
    }

    // view status
    public function viewstatus(){
        $id_pelanggan = Auth::id();
        // dapatkan id ke berapa dari status pemesanan
        $id_status_pemesanan = Penjualan::getIdStatus($id_pelanggan);
        $status_pemesanan = Penjualan::getStatusAll($id_pelanggan);
        return view('penjualan.viewstatus',
                [
                    'status_pemesanan' => $status_pemesanan,
                    'id_status_pemesanan'=> $id_status_pemesanan
                ]
        );
    } 

    // view keranjang
    public function keranjangjson(){
        $id_pelanggan = Auth::id();
        $keranjang = Penjualan::viewKeranjang($id_pelanggan);
        if($keranjang)
        {
            return response()->json([
                'status'=>200,
                'keranjang'=> $keranjang,
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Tidak ada data ditemukan.'
            ]);
        }
    }

    // view keranjang
    public function checkout(){
        $id_pelanggan = Auth::id();
        Penjualan::checkout($id_pelanggan); //proses cekout
        $barang = Penjualan::getBarang();

        return redirect('penjualan/status');
    }

    // invoice
    public function invoice(){
        $id_pelanggan = Auth::id();
        $invoice = Penjualan::getListInvoice($id_pelanggan);
        if($invoice)
        {
            return response()->json([
                'status'=>200,
                'invoice'=> $invoice,
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'Tidak ada data ditemukan.'
            ]);
        }
    }

    // delete penjualan detail
    public function destroypenjualandetail($id_penjualan_detail){
        // kembalikan stok ke semula
        //Penjualan::kembalikanstok($id_penjualan_detail);

        //hapus dari database
        Penjualan::hapuspenjualandetail($id_penjualan_detail);

        $id_pelanggan = Auth::id();
        $keranjang = Penjualan::viewKeranjang($id_pelanggan);

        return view('penjualan/viewkeranjang',
            [
                'keranjang' => $keranjang,
                'status_hapus' => 'Sukses Hapus'
            ]
        );
    }
}
