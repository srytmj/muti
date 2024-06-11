@extends('layout3')

@section('konten')

<h1>Data Layanan</h1>

<u1>
    @foreach($layanan as $p)
        <li>{{ "Kode Layanan: " . $p->kode_layanan . ' | Nama Layanan: ' . $p->nama_layanan . ' | Harga Layanan: ' . $p->harga_layanan . ' | Stok Layanan: ' . $p->stock_layanan }}</li>
    @endforeach
</ul>

@endsection