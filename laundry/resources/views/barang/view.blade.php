@extends('layout3')

@section('konten')

<h1>Data Barang</h1>

<u1>
    @foreach($barang as $p)
        <li>{{ "Kode Barang: " . $p->kode_barang . ' | Nama Barang: ' . $p->nama_layanan . ' | Harga Barang: ' . $p->harga_barang . ' | Stok Barang: ' . $p->stock_barang }}</li>
    @endforeach
</ul>

@endsection