<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Statistics
        $totalProduk = Produk::count();
        $totalKategori = Kategori::count();
        $totalStok = Produk::sum('stok') ?? 0;
        $totalNilaiInventaris = Produk::selectRaw('SUM(harga * stok) as total')->value('total') ?? 0;

        // Query produk
        $produkQuery = Produk::with('kategori');
        if ($search) {
            $produkQuery->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('kode_produk', 'like', "%{$search}%")
                  ->orWhereHas('kategori', function($k) use ($search) {
                      $k->where('nama_kategori', 'like', "%{$search}%");
                  });
            });
        }
        $produks = $produkQuery->latest()->get();

        // Query kategori dengan jumlah produk (withCount untuk produks_count)
        $kategoriQuery = Kategori::withCount('produks');
        if ($search) {
            $kategoriQuery->where('nama_kategori', 'like', "%{$search}%")
                          ->orWhere('deskripsi', 'like', "%{$search}%");
        }
        $kategori = $kategoriQuery->latest()->get();

        return view('dashboard.index', [
            'title' => 'Dashboard Utama',
            'totalProduk' => $totalProduk,
            'totalKategori' => $totalKategori,
            'totalStok' => $totalStok,
            'totalNilaiInventaris' => $totalNilaiInventaris,
            'produks' => $produks,
            'kategori' => $kategori,
            'search' => $search
        ]);
    }

    public function peta()
    {
        $lokasi = [
            // ================= KABUPATEN KETAPANG =================
            [
                'kode'       => 'SKJ',
                'nama'       => 'SUNGAI KINJIL',
                'lat'        => -1.8590,
                'lng'        => 109.9855,
                'ikon'       => 'tint',
                'warna'      => '#27ae60',
                'status'     => 'Kabupaten Ketapang',
                'badge'      => 'success',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - KETAPANG',
            ],
            [
                'kode'       => 'PGII',
                'nama'       => 'PEMATANG GADUNG II',
                'lat'        => -1.9334,
                'lng'        => 110.1205,
                'ikon'       => 'tint',
                'warna'      => '#27ae60',
                'status'     => 'Kabupaten Ketapang',
                'badge'      => 'success',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - KETAPANG',
            ],
            [
                'kode'       => 'PGI',
                'nama'       => 'PEMATANG GADUNG I',
                'lat'        => -1.9329,
                'lng'        => 110.0684,
                'ikon'       => 'tint',
                'warna'      => '#27ae60',
                'status'     => 'Kabupaten Ketapang',
                'badge'      => 'success',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - KETAPANG',
            ],

            // ================= KABUPATEN KUBU RAYA =================
            [
                'kode'       => 'KRW',
                'nama'       => 'KERAWANG KOMPLEK',
                'lat'        => -0.8084,
                'lng'        => 109.8317,
                'ikon'       => 'layer-group',
                'warna'      => '#e74c3c',
                'status'     => 'Kabupaten Kubu Raya',
                'badge'      => 'danger',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - KUBU RAYA',
            ],
            [
                'kode'       => 'TB',
                'nama'       => 'TELUK BAYUR',
                'lat'        => -0.3642,
                'lng'        => 109.5683,
                'ikon'       => 'layer-group',
                'warna'      => '#e74c3c',
                'status'     => 'Kabupaten Kubu Raya',
                'badge'      => 'danger',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - KUBU RAYA',
            ],
            [
                'kode'       => 'KK',
                'nama'       => 'KUBU KOMPLEK',
                'lat'        => -0.4943,
                'lng'        => 109.3928,
                'ikon'       => 'layer-group',
                'warna'      => '#e74c3c',
                'status'     => 'Kabupaten Kubu Raya',
                'badge'      => 'danger',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - KUBU RAYA',
            ],

            // ================= KABUPATEN SAMBAS =================
            [
                'kode'       => 'PMK',
                'nama'       => 'DIR PEMANGKAT',
                'lat'        => 1.1504,
                'lng'        => 108.9912,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
            [
                'kode'       => 'PPK',
                'nama'       => 'DIR PIMPINAN KOMPLEK',
                'lat'        => 1.5656,
                'lng'        => 109.2195,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
            [
                'kode'       => 'SBU',
                'nama'       => 'DIR SARANG BURUNG',
                'lat'        => 1.4782,
                'lng'        => 109.0761,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
            [
                'kode'       => 'SBK',
                'nama'       => 'DIR SEBANGKAU',
                'lat'        => 1.4375,
                'lng'        => 109.0809,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
            [
                'kode'       => 'SBW',
                'nama'       => 'DIR SEBAWI',
                'lat'        => 1.2859,
                'lng'        => 109.2077,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
            [
                'kode'       => 'SBB',
                'nama'       => 'DIR SEBUBUS',
                'lat'        => 1.7680,
                'lng'        => 109.3607,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
            [
                'kode'       => 'SLK',
                'nama'       => 'DIR SELAKAU KOMPLEK',
                'lat'        => 1.0752,
                'lng'        => 109.0419,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
            [
                'kode'       => 'SML',
                'nama'       => 'DIR SEMELAGI KOMPLEK',
                'lat'        => 1.0254,
                'lng'        => 109.0194,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
        ];
        $geojsonFiles   = [];
        $fileList       = [];
        $defaultFiles   = ['baku', 'fungsional', 'potensial', 'saluran', 'bangunan']; // prefix file sistem
        $availableTypes = [
            'baku'       => false,
            'fungsional' => false,
            'potensial'  => false,
            'saluran'    => false,
            'bangunan'   => false,
            'custom'     => false,
        ];

        $path = public_path('geojson');
        if (file_exists($path)) {
            $files = File::files($path);
            foreach ($files as $file) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['json', 'geojson'])) {
                    $geojsonFiles[] = $file->getFilename();

                    $nameLower = strtolower($file->getFilenameWithoutExtension());
                    $isDefault = false;
                    foreach ($defaultFiles as $prefix) {
                        if (str_starts_with($nameLower, $prefix)) {
                            $isDefault = true;
                            $availableTypes[$prefix] = true;
                            break;
                        }
                    }
                    if (!$isDefault) {
                        $availableTypes['custom'] = true;
                    }

                    $bytes = $file->getSize();
                    if ($bytes >= 1048576)      $sizeStr = number_format($bytes / 1048576, 2) . ' MB';
                    elseif ($bytes >= 1024)     $sizeStr = number_format($bytes / 1024, 2) . ' KB';
                    else                        $sizeStr = $bytes . ' B';

                    $fileList[] = [
                        'name'       => $file->getFilename(),
                        'size'       => $sizeStr,
                        'date'       => date('d/m/Y H:i', $file->getMTime()),
                        'is_default' => $isDefault,
                    ];
                }
            }
        }

        $hasFiles = count($geojsonFiles) > 0;

        return view('dashboard.peta', compact('lokasi', 'geojsonFiles', 'fileList', 'hasFiles', 'availableTypes'));
    }

    public function uploadGeojson(Request $request)
    {
        // 1. Validasi: Pastikan ada file yang diunggah
        $request->validate([
            'file_geojson'   => 'required|array|min:1',
            'file_geojson.*' => 'required|file|max:102400|extensions:json,geojson',
        ], [
            'file_geojson.required'     => 'Silakan pilih minimal satu file GeoJSON/JSON terlebih dahulu.',
            'file_geojson.*.file'       => 'File yang diunggah tidak valid.',
            'file_geojson.*.extensions' => 'Format file harus .json atau .geojson!',
            'file_geojson.*.max'        => 'Ukuran file tidak boleh lebih dari 100MB.',
        ]);

        // 2. Pastikan folder public/geojson ada
        $destinationPath = public_path('geojson');
        if (!file_exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // 3. Proses setiap file yang diupload
        $namaFileList = [];
        $failedFiles  = [];

        foreach ($request->file('file_geojson') as $file) {
            $namaFile = $file->getClientOriginalName();
            $ext = strtolower($file->getClientOriginalExtension());

            // Validasi format ekstensi: harus json atau geojson
            if (!in_array($ext, ['json', 'geojson'])) {
                $failedFiles[] = $namaFile;
                continue;
            }

            // 4. Simpan file ke folder public/geojson
            $file->move($destinationPath, $namaFile);
            $namaFileList[] = $namaFile;
        }

        if (count($namaFileList) === 0 && count($failedFiles) > 0) {
            $errMessage = 'Format file harus .json atau .geojson! File "' . implode(', ', $failedFiles) . '" tidak valid.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errMessage,
                    'failed'  => $failedFiles,
                ], 422);
            }
            return redirect()->back()->with('error', $errMessage);
        }

        $jumlah = count($namaFileList);
        $pesanFile = implode(', ', $namaFileList);

        // Jika request berupa AJAX / fetch batch upload, kembalikan respons JSON yang dinanti klien
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => $jumlah . ' file GIS berhasil diunggah.',
                'uploaded' => $namaFileList,
                'failed'   => $failedFiles,
            ]);
        }

        // 5. Kembalikan ke halaman peta dengan notifikasi (key 'success' sesuai blade)
        return redirect()->back()->with('success', $jumlah . ' file GIS berhasil diunggah dan otomatis aktif di peta! (' . $pesanFile . ')');
    }

    public function deleteGeojson(Request $request)
    {
        $request->validate([
            'file_name' => 'required|string',
        ]);

        $fileName = basename($request->input('file_name')); // basename() mencegah path traversal
        $filePath = public_path('geojson/' . $fileName);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File "' . $fileName . '" tidak ditemukan di server.');
        }

        File::delete($filePath);

        return redirect()->back()->with('success', 'File "' . $fileName . '" berhasil dihapus dari server.');
    }

    /**
     * Menghapus banyak file GeoJSON sekaligus dari folder public/geojson
     */
    public function deleteGeojsonBulk(Request $request)
    {
        $request->validate([
            'file_names'   => 'required|array|min:1',
            'file_names.*' => 'required|string',
        ], [
            'file_names.required' => 'Pilih minimal satu file untuk dihapus.',
            'file_names.min'      => 'Pilih minimal satu file untuk dihapus.',
        ]);

        $deleted  = [];
        $notFound = [];

        foreach ($request->input('file_names') as $rawName) {
            $fileName = basename($rawName); // Cegah path traversal
            $filePath = public_path('geojson/' . $fileName);

            if (File::exists($filePath)) {
                File::delete($filePath);
                $deleted[] = $fileName;
            } else {
                $notFound[] = $fileName;
            }
        }

        if (count($deleted) > 0) {
            $msg = count($deleted) . ' file GIS berhasil dihapus (' . implode(', ', $deleted) . ').';
            if (count($notFound) > 0) {
                $msg .= ' ' . count($notFound) . ' file tidak ditemukan: ' . implode(', ', $notFound);
            }
            return redirect()->route('peta')->with('success', $msg);
        }

        return redirect()->route('peta')->with('error', 'Tidak ada file yang berhasil dihapus. Pastikan file masih tersedia di server.');
    }
}
