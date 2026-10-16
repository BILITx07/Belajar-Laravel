<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class WebGisController extends Controller
{
    /**
     * Halaman Publik WebGIS Interaktif (Tanpa Login)
     * Layout dengan Sidebar Kiri Fungsional (Filter Kabupaten, Layer Control, Search, Statistik, Legenda)
     */
    public function index()
    {
        // 1. Data Daerah Irigasi (DIR) Kewenangan BWS Kalimantan I
        $lokasi = [
            // Kabupaten Ketapang
            [
                'kode'       => 'SKJ',
                'nama'       => 'SUNGAI KINJIL',
                'kabupaten'  => 'Ketapang',
                'lat'        => -1.8590,
                'lng'        => 109.9855,
                'luas_baku'  => 1250,
                'luas_fung'  => 980,
                'ikon'       => 'tint',
                'warna'      => '#27ae60',
                'status'     => 'Kabupaten Ketapang',
                'badge'      => 'success',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - KETAPANG',
            ],
            [
                'kode'       => 'PGII',
                'nama'       => 'PEMATANG GADUNG II',
                'kabupaten'  => 'Ketapang',
                'lat'        => -1.9334,
                'lng'        => 110.1205,
                'luas_baku'  => 1420,
                'luas_fung'  => 1150,
                'ikon'       => 'tint',
                'warna'      => '#27ae60',
                'status'     => 'Kabupaten Ketapang',
                'badge'      => 'success',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - KETAPANG',
            ],
            [
                'kode'       => 'PGI',
                'nama'       => 'PEMATANG GADUNG I',
                'kabupaten'  => 'Ketapang',
                'lat'        => -1.9329,
                'lng'        => 110.0684,
                'luas_baku'  => 1600,
                'luas_fung'  => 1200,
                'ikon'       => 'tint',
                'warna'      => '#27ae60',
                'status'     => 'Kabupaten Ketapang',
                'badge'      => 'success',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - KETAPANG',
            ],

            // Kabupaten Kubu Raya
            [
                'kode'       => 'KRW',
                'nama'       => 'KERAWANG KOMPLEK',
                'kabupaten'  => 'Kubu Raya',
                'lat'        => -0.8084,
                'lng'        => 109.8317,
                'luas_baku'  => 2850,
                'luas_fung'  => 2100,
                'ikon'       => 'layer-group',
                'warna'      => '#e74c3c',
                'status'     => 'Kabupaten Kubu Raya',
                'badge'      => 'danger',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - KUBU RAYA',
            ],
            [
                'kode'       => 'TB',
                'nama'       => 'TELUK BAYUR',
                'kabupaten'  => 'Kubu Raya',
                'lat'        => -0.3642,
                'lng'        => 109.5683,
                'luas_baku'  => 1950,
                'luas_fung'  => 1450,
                'ikon'       => 'layer-group',
                'warna'      => '#e74c3c',
                'status'     => 'Kabupaten Kubu Raya',
                'badge'      => 'danger',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - KUBU RAYA',
            ],
            [
                'kode'       => 'KK',
                'nama'       => 'KUBU KOMPLEK',
                'kabupaten'  => 'Kubu Raya',
                'lat'        => -0.4943,
                'lng'        => 109.3928,
                'luas_baku'  => 3100,
                'luas_fung'  => 2400,
                'ikon'       => 'layer-group',
                'warna'      => '#e74c3c',
                'status'     => 'Kabupaten Kubu Raya',
                'badge'      => 'danger',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - KUBU RAYA',
            ],

            // Kabupaten Sambas
            [
                'kode'       => 'PMK',
                'nama'       => 'DIR PEMANGKAT',
                'kabupaten'  => 'Sambas',
                'lat'        => 1.1504,
                'lng'        => 108.9912,
                'luas_baku'  => 2200,
                'luas_fung'  => 1850,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
            [
                'kode'       => 'PPK',
                'nama'       => 'DIR PIMPINAN KOMPLEK',
                'kabupaten'  => 'Sambas',
                'lat'        => 1.5656,
                'lng'        => 109.2195,
                'luas_baku'  => 3400,
                'luas_fung'  => 2800,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
            [
                'kode'       => 'SBU',
                'nama'       => 'DIR SARANG BURUNG',
                'kabupaten'  => 'Sambas',
                'lat'        => 1.4782,
                'lng'        => 109.0761,
                'luas_baku'  => 1780,
                'luas_fung'  => 1350,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
            [
                'kode'       => 'SBK',
                'nama'       => 'DIR SEBANGKAU',
                'kabupaten'  => 'Sambas',
                'lat'        => 1.4375,
                'lng'        => 109.0809,
                'luas_baku'  => 1920,
                'luas_fung'  => 1510,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
            [
                'kode'       => 'SBW',
                'nama'       => 'DIR SEBAWI',
                'kabupaten'  => 'Sambas',
                'lat'        => 1.2859,
                'lng'        => 109.2077,
                'luas_baku'  => 2150,
                'luas_fung'  => 1700,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
            [
                'kode'       => 'SBB',
                'nama'       => 'DIR SEBUBUS',
                'kabupaten'  => 'Sambas',
                'lat'        => 1.7680,
                'lng'        => 109.3607,
                'luas_baku'  => 1850,
                'luas_fung'  => 1400,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
            [
                'kode'       => 'SLK',
                'nama'       => 'DIR SELAKAU KOMPLEK',
                'kabupaten'  => 'Sambas',
                'lat'        => 1.0752,
                'lng'        => 109.0419,
                'luas_baku'  => 2600,
                'luas_fung'  => 2050,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
            [
                'kode'       => 'SML',
                'nama'       => 'DIR SEMELAGI KOMPLEK',
                'kabupaten'  => 'Sambas',
                'lat'        => 1.0254,
                'lng'        => 109.0194,
                'luas_baku'  => 2300,
                'luas_fung'  => 1820,
                'ikon'       => 'map-marker-alt',
                'warna'      => '#2980b9',
                'status'     => 'Kabupaten Sambas',
                'badge'      => 'primary',
                'keterangan' => 'DATA IGT DIR KEWENANGAN BWSK I PTK - SAMBAS',
            ],
        ];

        // 2. Daftar Kabupaten unik
        $kabupatenList = array_values(array_unique(array_map(fn($item) => $item['kabupaten'], $lokasi)));
        sort($kabupatenList);

        // 3. Scan File GeoJSON di public/geojson
        $geojsonFiles   = [];
        $fileList       = [];
        $defaultFiles   = ['baku', 'fungsional', 'potensial', 'saluran', 'bangunan'];
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

        // 4. Hitung Statistik Ringkasan
        $totalLuasBaku = array_sum(array_column($lokasi, 'luas_baku'));
        $totalLuasFung = array_sum(array_column($lokasi, 'luas_fung'));
        $statistik = [
            'total_dir'       => count($lokasi),
            'total_luas_baku' => number_format($totalLuasBaku, 0, ',', '.') . ' Ha',
            'total_luas_fung' => number_format($totalLuasFung, 0, ',', '.') . ' Ha',
            'total_file'      => count($geojsonFiles),
        ];

        return view('peta', compact(
            'lokasi',
            'kabupatenList',
            'geojsonFiles',
            'fileList',
            'hasFiles',
            'availableTypes',
            'statistik'
        ));
    }
}
