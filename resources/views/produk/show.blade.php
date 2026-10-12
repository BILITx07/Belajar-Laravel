@extends('layout.master')

@section('title', $title)

@section('konten')

<!-- Header Halaman -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
        <i class="fas fa-boxes text-primary mr-2"></i>Daftar Produk Kami
    </h1>
    <a href="{{ route('produk.create') }}" class="btn btn-primary btn-sm shadow-sm px-3 py-2 font-weight-bold">
        <i class="fas fa-plus-circle mr-1"></i> Tambah Data
    </a>
</div>

{{-- Pesan Notifikasi Sukses --}}
@if(session('message'))
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('message') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- Info Banner Toko -->
<div class="card shadow-sm border-left-primary mb-4">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-4 mb-2 mb-md-0 d-flex align-items-center">
                <div class="rounded-circle bg-primary text-white p-2 mr-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="fas fa-store"></i>
                </div>
                <div>
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-0">Nama Toko</div>
                    <div class="font-weight-bold text-gray-800">Gemilang Abadi</div>
                </div>
            </div>
            <div class="col-md-5 mb-2 mb-md-0 d-flex align-items-center">
                <div class="rounded-circle bg-info text-white p-2 mr-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div>
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-0">Alamat</div>
                    <div class="font-weight-bold text-gray-800">Pontianak, Kalimantan Barat</div>
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <div class="rounded-circle bg-success text-white p-2 mr-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-0">Tipe Toko</div>
                    <div class="font-weight-bold text-gray-800 text-capitalize">Ruko</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Card Tabel Produk -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between bg-white">
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table mr-1"></i> Data Produk
            </h6>
            <span class="badge badge-primary ml-2 px-2 py-1 font-weight-normal">{{ $data->count() }} Total</span>
        </div>
        <form action="/produk" method="GET" class="form-inline">
            <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control bg-light border-1 small" placeholder="Cari nama, kode, kategori..." value="{{ request('search') }}" style="min-width: 220px;">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm"></i> Cari
                    </button>
                    @if(request('search'))
                        <a href="/produk" class="btn btn-secondary" title="Reset Pencarian">
                            <i class="fas fa-times fa-sm"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0 text-gray-900" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center align-middle" style="width: 50px;">No</th>
                        <th class="text-center align-middle" style="width: 90px;">Gambar</th>
                        <th class="text-center align-middle" style="width: 130px;">Kode Produk</th>
                        <th class="align-middle" style="min-width: 150px;">Nama Produk</th>
                        <th class="text-right align-middle" style="width: 120px;">Harga</th>
                        <th class="text-center align-middle" style="width: 80px;">Stok</th>
                        <th class="align-middle" style="min-width: 160px;">Deskripsi</th>
                        <th class="text-center align-middle" style="width: 120px;">Kategori</th>
                        <th class="text-center align-middle" style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <!-- Kolom No -->
                        <td class="text-center align-middle font-weight-bold text-muted">
                            {{ $loop->iteration }}
                        </td>

                        <!-- Kolom Gambar -->
                        <td class="text-center align-middle">
                            @if(!empty($item->gambar_url))
                                <div class="d-inline-block position-relative">
                                    <a href="javascript:void(0)" 
                                       onclick="previewImage('{{ $item->gambar_url }}', '{{ addslashes($item->nama_produk) }}')" 
                                       title="Klik untuk memperbesar gambar">
                                        <img src="{{ $item->gambar_url }}" 
                                             alt="{{ $item->nama_produk }}" 
                                             class="rounded border shadow-sm" 
                                             style="width: 58px; height: 58px; object-fit: cover; transition: transform 0.2s;"
                                             onmouseover="this.style.transform='scale(1.08)'"
                                             onmouseout="this.style.transform='scale(1)'"
                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'rounded bg-light border d-inline-flex flex-column align-items-center justify-content-center text-muted\' style=\'width: 58px; height: 58px;\'><i class=\'fas fa-image text-gray-400\'></i><small style=\'font-size: 8px; margin-top: 2px;\'>No Foto</small></div>';">
                                    </a>
                                </div>
                            @else
                                <div class="rounded bg-light border d-inline-flex flex-column align-items-center justify-content-center text-muted shadow-sm" style="width: 58px; height: 58px;" title="Tidak ada foto">
                                    <i class="fas fa-image fa-lg text-gray-400 mb-1"></i>
                                    <span style="font-size: 8px; line-height: 1;">No Foto</span>
                                </div>
                            @endif
                        </td>

                        <!-- Kolom Kode Produk & QR Code -->
                        <td class="text-center align-middle">
                            @if(!empty($item->kode_produk))
                                <div class="d-inline-flex flex-column align-items-center">
                                    <div class="p-1 bg-white border rounded shadow-sm">
                                        {!! DNS2D::getBarcodeHTML($item->kode_produk, 'QRCODE', 2.3, 2.3) !!}
                                    </div>
                                    <span class="badge badge-light border text-monospace font-weight-bold text-gray-700 mt-1" style="font-size: 10px;">
                                        {{ $item->kode_produk }}
                                    </span>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>  

                        <!-- Kolom Nama Produk -->
                        <td class="align-middle">
                            <span class="font-weight-bold text-gray-900">{{ $item->nama_produk }}</span>
                        </td>

                        <!-- Kolom Harga -->
                        <td class="text-right align-middle">
                            <span class="font-weight-bold text-primary">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </span>
                        </td>

                        <!-- Kolom Stok -->
                        <td class="text-center align-middle">
                            @if($item->stok > 10)
                                <span class="badge badge-success px-2 py-1 font-weight-bold" title="Stok Aman">
                                    {{ $item->stok }}
                                </span>
                            @elseif($item->stok > 0)
                                <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" title="Stok Menipis">
                                    {{ $item->stok }}
                                </span>
                            @else
                                <span class="badge badge-danger px-2 py-1 font-weight-bold" title="Stok Habis">
                                    0
                                </span>
                            @endif
                        </td>

                        <!-- Kolom Deskripsi -->
                        <td class="align-middle text-muted small" style="max-width: 220px;">
                            <span title="{{ $item->deskripsi }}">
                                {{ Str::limit($item->deskripsi, 50, '...') ?: '-' }}
                            </span>
                        </td>

                        <!-- Kolom Kategori -->
                        <td class="text-center align-middle">
                            <span class="badge badge-pill badge-primary px-3 py-1 font-weight-normal" style="font-size: 11px;">
                                {{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                            </span>
                        </td>

                        <!-- Kolom Aksi -->
                        <td class="text-center align-middle text-nowrap">
                            <div class="btn-group btn-group-sm shadow-sm" role="group">
                                <a href="{{ route('produk.show', $item->id) }}" class="btn btn-info" title="Lihat Detail">
                                    <i class="fas fa-eye fa-sm"></i> Detail
                                </a>
                                <a href="{{ route('produk.edit', $item->id) }}" class="btn btn-warning text-white" title="Edit Produk">
                                    <i class="fas fa-edit fa-sm"></i> Edit
                                </a>
                                <button type="button" 
                                        class="btn btn-danger" 
                                        data-toggle="modal" 
                                        data-target="#hapus{{ $item->id }}" 
                                        title="Hapus Produk">
                                    <i class="fas fa-trash fa-sm"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-3x mb-3 d-block text-gray-400"></i>
                            <span class="font-weight-bold">Tidak ada data produk ditemukan</span>
                            @if(request('search'))
                                <div class="mt-2">
                                    <a href="/produk" class="btn btn-outline-primary btn-sm">Reset Pencarian</a>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Preview Foto Gambar -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title font-weight-bold" id="previewModalTitle">
                    <i class="fas fa-image mr-1"></i> Preview Gambar Produk
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center p-3 bg-light">
                <img id="previewModalImage" src="" alt="Preview" class="img-fluid rounded shadow-sm border" style="max-height: 480px; object-fit: contain;">
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Data -->
@foreach($data as $item)
<div class="modal fade" id="hapus{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalHapusLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="/produk/{{ $item->id }}" method="POST" class="modal-content border-0 shadow-lg">
            @csrf
            @method('DELETE')
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title font-weight-bold" id="modalHapusLabel{{ $item->id }}">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Konfirmasi Hapus Produk
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-4 text-center">
                @if(!empty($item->gambar_url))
                    <img src="{{ $item->gambar_url }}" alt="{{ $item->nama_produk }}" class="rounded shadow-sm mb-3 border" style="width: 80px; height: 80px; object-fit: cover;">
                @endif
                <p class="mb-1 text-gray-800">Apakah Anda yakin ingin menghapus produk ini?</p>
                <h5 class="font-weight-bold text-danger">{{ $item->nama_produk }}</h5>
                <small class="text-muted">Kode: {{ $item->kode_produk ?? '-' }} | Data yang dihapus tidak dapat dikembalikan.</small>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger btn-sm font-weight-bold">
                    <i class="fas fa-trash mr-1"></i> Ya, Hapus Data
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
    function previewImage(src, title) {
        document.getElementById('previewModalImage').src = src;
        document.getElementById('previewModalTitle').innerHTML = '<i class="fas fa-image mr-1"></i> ' + title;
        $('#imagePreviewModal').modal('show');
    }
</script>
@endpush
