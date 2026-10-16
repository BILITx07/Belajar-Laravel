<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Services\HidrologiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tipeFilter = $request->input('tipe', 'semua');
        $statusFilter = $request->input('status', 'semua');

        $posList = HidrologiService::getDaftarPos();
        $statistik = HidrologiService::getStatistik();
        $infrastrukturSDA = HidrologiService::getInfrastrukturSDA();

        // Optional filtering for search/type
        if ($search) {
            $posList = array_values(array_filter($posList, function($pos) use ($search) {
                return stripos($pos['nama'], $search) !== false 
                    || stripos($pos['kabupaten'], $search) !== false 
                    || stripos($pos['kode'], $search) !== false;
            }));
        }

        if ($tipeFilter !== 'semua') {
            $posList = array_values(array_filter($posList, function($pos) use ($tipeFilter) {
                return $pos['tipe'] === $tipeFilter;
            }));
        }

        if ($statusFilter === 'online') {
            $posList = array_values(array_filter($posList, fn($pos) => $pos['status_alat'] === 'Online'));
        } elseif ($statusFilter === 'offline') {
            $posList = array_values(array_filter($posList, fn($pos) => $pos['status_alat'] === 'Offline'));
        } elseif ($statusFilter === 'siaga') {
            $posList = array_values(array_filter($posList, fn($pos) => in_array($pos['status_siaga'], ['Waspada', 'Siaga', 'Awas'])));
        }

        // Old inventory stats (if needed)
        $totalProduk = Produk::count();
        $totalKategori = Kategori::count();

        return view('dashboard.index', [
            'title' => 'Dashboard Telemetri & FEWS Terpadu - BWS Kalimantan I',
            'posList' => $posList,
            'statistik' => $statistik,
            'infrastrukturSDA' => $infrastrukturSDA,
            'totalProduk' => $totalProduk,
            'totalKategori' => $totalKategori,
            'search' => $search,
            'tipeFilter' => $tipeFilter,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function dataHidrologi(Request $request)
    {
        $search = $request->input('search');
        $tipeFilter = $request->input('tipe', 'semua');
        $kabupatenFilter = $request->input('kabupaten', 'semua');
        $statusFilter = $request->input('status', 'semua');

        $allPos = HidrologiService::getDaftarPos();
        $statistik = HidrologiService::getStatistik();

        $filteredPos = array_filter($allPos, function($pos) use ($search, $tipeFilter, $kabupatenFilter, $statusFilter) {
            $matchSearch = true;
            if ($search) {
                $matchSearch = stripos($pos['nama'], $search) !== false
                    || stripos($pos['kode'], $search) !== false
                    || stripos($pos['das'], $search) !== false
                    || stripos($pos['kabupaten'], $search) !== false;
            }

            $matchTipe = ($tipeFilter === 'semua' || $pos['tipe'] === $tipeFilter);
            $matchKab = ($kabupatenFilter === 'semua' || $pos['kabupaten'] === $kabupatenFilter);
            $matchStatus = ($statusFilter === 'semua' 
                || ($statusFilter === 'online' && $pos['status_alat'] === 'Online')
                || ($statusFilter === 'offline' && $pos['status_alat'] === 'Offline')
                || ($statusFilter === 'siaga' && in_array($pos['status_siaga'], ['Waspada', 'Siaga', 'Awas']))
            );

            return $matchSearch && $matchTipe && $matchKab && $matchStatus;
        });

        // List distinct kabupaten
        $kabupatenList = array_unique(array_map(fn($p) => $p['kabupaten'], $allPos));
        sort($kabupatenList);

        return view('dashboard.hidrologi_data', [
            'title' => 'Rekapitulasi Data Pos Hidrologi & Log Telemetri',
            'posList' => array_values($filteredPos),
            'statistik' => $statistik,
            'kabupatenList' => $kabupatenList,
            'search' => $search,
            'tipeFilter' => $tipeFilter,
            'kabupatenFilter' => $kabupatenFilter,
            'statusFilter' => $statusFilter,
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
            'ws_kapuas'  => false,
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

                    // Deteksi Wilayah Sungai / DAS / Jaringan Sungai Kapuas
                    if (str_contains($nameLower, 'kapuas') || str_starts_with($nameLower, 'ws') || str_contains($nameLower, 'das')) {
                        $isDefault = true;
                        $availableTypes['ws_kapuas'] = true;
                    } else {
                        foreach ($defaultFiles as $prefix) {
                            if (str_starts_with($nameLower, $prefix)) {
                                $isDefault = true;
                                $availableTypes[$prefix] = true;
                                break;
                            }
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
        $posList = HidrologiService::getDaftarPos();
        $infrastrukturSDA = HidrologiService::getInfrastrukturSDA();

        return view('dashboard.peta', compact('lokasi', 'geojsonFiles', 'fileList', 'hasFiles', 'availableTypes', 'posList', 'infrastrukturSDA'));
    }

    public function uploadGeojson(Request $request)
    {
        @ini_set('memory_limit', '1024M');
        @set_time_limit(300);

        // Tentukan apakah upload sebagai array (form biasa) atau single blob (konversi JS)
        $isSingleBlob = $request->hasFile('file_geojson') && !is_array($request->file('file_geojson'));

        if ($isSingleBlob) {
            // Upload blob tunggal dari konversi JS (misal: Shapefile → GeoJSON, KML → GeoJSON)
            $request->validate([
                'file_geojson' => 'required|file|max:262144',
            ], [
                'file_geojson.required' => 'File GeoJSON hasil konversi tidak ditemukan.',
                'file_geojson.max'      => 'Ukuran file tidak boleh lebih dari 256MB.',
            ]);
        } else {
            // Upload normal (array file .json / .geojson)
            $request->validate([
                'file_geojson'   => 'required|array|min:1',
                'file_geojson.*' => 'required|file|max:262144|extensions:json,geojson',
            ], [
                'file_geojson.required'     => 'Silakan pilih minimal satu file GeoJSON/JSON terlebih dahulu.',
                'file_geojson.*.file'       => 'File yang diunggah tidak valid.',
                'file_geojson.*.extensions' => 'Format file harus .json atau .geojson!',
                'file_geojson.*.max'        => 'Ukuran file tidak boleh lebih dari 256MB.',
            ]);
        }

        // Pastikan folder public/geojson ada
        $destinationPath = public_path('geojson');
        if (!file_exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // Normalisasi: jadikan selalu array agar bisa di-loop
        $files = $isSingleBlob
            ? [$request->file('file_geojson')]
            : (array) $request->file('file_geojson');

        $namaFileList = [];
        $failedFiles  = [];

        foreach ($files as $file) {
            $namaFile = $file->getClientOriginalName();
            $ext      = strtolower($file->getClientOriginalExtension());

            // Validasi ekstensi
            if (!in_array($ext, ['json', 'geojson'])) {
                $failedFiles[] = $namaFile . ' (ekstensi tidak valid: .' . $ext . ')';
                continue;
            }

            // Validasi isi file secara efisien (hindari memory exhaustion dari json_decode pada file spasial besar)
            $filePath = $file->getRealPath();
            $fileSize = $file->getSize();

            if ($fileSize > 2 * 1024 * 1024) { // Untuk file > 2MB, gunakan streaming header check yang cepat dan hemat RAM
                $handle = fopen($filePath, 'r');
                if (!$handle) {
                    $failedFiles[] = $namaFile . ' (tidak dapat membaca file sementara)';
                    continue;
                }
                $headerSample = fread($handle, 4096);
                fclose($handle);

                $trimmed = ltrim($headerSample);
                if (!str_starts_with($trimmed, '{') && !str_starts_with($trimmed, '[')) {
                    $failedFiles[] = $namaFile . ' (bukan format JSON/GeoJSON yang valid)';
                    continue;
                }

                if (!str_contains($headerSample, '"type"') && !str_contains($headerSample, "'type'")) {
                    $failedFiles[] = $namaFile . ' (bukan struktur GeoJSON: atribut "type" tidak ditemukan)';
                    continue;
                }
            } else {
                // Untuk file kecil <= 2MB, aman divalidasi dengan json_decode
                $content = file_get_contents($filePath);
                $decoded = json_decode($content, true);
                if (json_last_error() !== JSON_ERROR_NONE || empty($decoded['type'])) {
                    $failedFiles[] = $namaFile . ' (bukan file GeoJSON yang valid)';
                    continue;
                }
            }

            // Simpan file ke folder public/geojson
            $file->move($destinationPath, $namaFile);
            $namaFileList[] = $namaFile;
        }

        if (count($namaFileList) === 0 && count($failedFiles) > 0) {
            $errMessage = 'Gagal mengunggah file: ' . implode(', ', $failedFiles);
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errMessage,
                    'failed'  => $failedFiles,
                ], 422);
            }
            return redirect()->back()->with('error', $errMessage);
        }

        $jumlah    = count($namaFileList);
        $pesanFile = implode(', ', $namaFileList);

        // Kembalikan JSON untuk AJAX / fetch (termasuk dari konversi JS)
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => $jumlah . ' file GIS berhasil diunggah.',
                'uploaded' => $namaFileList,
                'failed'   => $failedFiles,
            ]);
        }

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
