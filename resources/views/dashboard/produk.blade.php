<!-- Component Tabel Produk Dashboard (Read Only / Non-CRUD) -->
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary text-white d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-boxes mr-2"></i>Tabel Data Produk
            <span class="badge badge-light text-primary ml-2 px-2 py-1">{{ count($produks) }} data</span>
        </h6>
        <a href="{{ route('produk.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold shadow-sm">
            <i class="fas fa-external-link-alt mr-1"></i> Kelola Produk
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0 text-gray-900" width="100%" cellspacing="0">
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($produks as $item)
                    <tr>
                        <!-- No -->
                        <td class="text-center align-middle font-weight-bold text-muted">
                            {{ $loop->iteration }}
                        </td>

                        <!-- Gambar -->
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

                        <!-- Kode Produk & QR Code -->
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

                        <!-- Nama Produk -->
                        <td class="align-middle">
                            <span class="font-weight-bold text-gray-900">{{ $item->nama_produk }}</span>
                        </td>

                        <!-- Harga -->
                        <td class="text-right align-middle">
                            <span class="font-weight-bold text-primary">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </span>
                        </td>

                        <!-- Stok -->
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

                        <!-- Deskripsi -->
                        <td class="align-middle text-muted small" style="max-width: 200px;">
                            <span title="{{ $item->deskripsi }}">
                                {{ Str::limit($item->deskripsi, 50, '...') ?: '-' }}
                            </span>
                        </td>

                        <!-- Kategori -->
                        <td class="text-center align-middle">
                            @if(!empty($item->kategori->nama_kategori))
                                <span class="badge badge-pill badge-primary px-3 py-1 font-weight-normal" style="font-size: 11px;">
                                    {{ $item->kategori->nama_kategori }}
                                </span>
                            @else
                                <span class="badge badge-light text-muted border">Tanpa Kategori</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-3x mb-3 text-gray-300 d-block"></i>
                            <span class="font-weight-bold">Belum ada data produk{{ !empty($search) ? ' yang sesuai pencarian' : '' }}</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
