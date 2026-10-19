<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    /**
     * Halaman Depan Admin (WebGIS Interactive Dashboard Sesuai Gambar Referensi)
     */
    public function dashboard(Request $request)
    {
        $lokasi = WebGisController::getLokasiData();
        $kabupatenList = array_values(array_unique(array_map(fn($item) => $item['kabupaten'], $lokasi)));
        sort($kabupatenList);

        $geojsonFiles = [];
        $path = public_path('geojson');
        if (file_exists($path)) {
            $files = File::files($path);
            foreach ($files as $file) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['json', 'geojson'])) {
                    $geojsonFiles[] = $file->getFilename();
                }
            }
        }

        $totalLuasBaku = array_sum(array_column($lokasi, 'luas_baku'));
        $totalLuasFung = array_sum(array_column($lokasi, 'luas_fung'));
        $statistik = [
            'total_dir'       => count($lokasi),
            'total_luas_baku' => number_format($totalLuasBaku, 0, ',', '.') . ' Ha',
            'total_luas_fung' => number_format($totalLuasFung, 0, ',', '.') . ' Ha',
            'total_file'      => count($geojsonFiles),
        ];

        $availableTypes = [
            'ws_kapuas'  => true,
            'baku'       => true,
            'fungsional' => true,
            'potensial'  => true,
            'saluran'    => true,
            'bangunan'   => true,
            'custom'     => false,
        ];

        $stats = $this->getGisStats();

        return view('admin.dashboard', compact(
            'lokasi',
            'kabupatenList',
            'geojsonFiles',
            'availableTypes',
            'statistik',
            'stats'
        ));
    }

    /**
     * Halaman Manajemen Master Data GIS (CRUD File Spasial)
     */
    public function kelolaGis(Request $request)
    {
        $search = $request->input('search');
        $filterType = $request->input('type', 'semua');
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $allFiles = $this->scanGeojsonFiles();

        // Filter pencarian teks
        if ($search) {
            $allFiles = array_filter($allFiles, function ($f) use ($search) {
                return stripos($f['name'], $search) !== false;
            });
        }

        // Filter kategori spasial (Master Data GIS Sesuai Referensi FEWS)
        if ($filterType !== 'semua' && !empty($filterType)) {
            if ($filterType === 'irigasi') {
                $allFiles = array_filter($allFiles, function ($f) {
                    return in_array($f['category'], ['baku', 'fungsional', 'potensial', 'saluran', 'bangunan']);
                });
            } elseif ($filterType === 'ws_kapuas' || $filterType === 'skema_sungai') {
                $allFiles = array_filter($allFiles, function ($f) {
                    return $f['category'] === 'ws_kapuas' || stripos($f['name'], 'kapuas') !== false || stripos($f['name'], 'ws') !== false;
                });
            } else {
                $allFiles = array_filter($allFiles, function ($f) use ($filterType) {
                    return $f['category'] === $filterType || stripos($f['name'], $filterType) !== false;
                });
            }
        }

        $allFiles = array_values($allFiles);
        $totalItems = count($allFiles);

        // Pagination sederhana
        $currentPage = max(1, (int) $request->input('page', 1));
        $totalPages = max(1, (int) ceil($totalItems / $perPage));
        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }
        $offset = ($currentPage - 1) * $perPage;
        $fileList = array_slice($allFiles, $offset, $perPage);

        $stats = $this->getGisStats();

        return view('admin.kelola_gis', [
            'title'       => 'Manajemen Master Data GIS — BWS Kalimantan I',
            'fileList'    => $fileList,
            'stats'       => $stats,
            'search'      => $search,
            'filterType'  => $filterType,
            'perPage'     => $perPage,
            'currentPage' => $currentPage,
            'totalPages'  => $totalPages,
            'totalItems'  => $totalItems,
            'offset'      => $offset,
        ]);
    }

    /**
     * Upload File Spasial Baru (Mendukung .geojson, .json, dan arsip .zip)
     */
    public function upload(Request $request)
    {
        @ini_set('memory_limit', '1024M');
        @set_time_limit(300);

        $rawFiles = $request->file('file_geojson') ?? $request->file('geojson_files');

        if (!$rawFiles || count((array) $rawFiles) === 0) {
            $msg = 'Silakan pilih minimal satu file ZIP atau GeoJSON terlebih dahulu.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        $destinationPath = public_path('geojson');
        if (!file_exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $files = is_array($rawFiles) ? $rawFiles : [$rawFiles];
        $uploaded = [];
        $failed = [];

        foreach ($files as $file) {
            if (!$file->isValid()) {
                $failed[] = $file->getClientOriginalName() . ' (file tidak valid)';
                continue;
            }

            $namaFile = $file->getClientOriginalName();
            $ext = strtolower($file->getClientOriginalExtension());

            if (!in_array($ext, ['json', 'geojson', 'zip'])) {
                $failed[] = $namaFile . ' (format harus .zip, .geojson, atau .json)';
                continue;
            }

            // Jika berupa file ZIP
            if ($ext === 'zip') {
                $zip = new \ZipArchive();
                if ($zip->open($file->getRealPath()) === true) {
                    $extractedInZip = 0;
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $stat = $zip->statIndex($i);
                        $entryName = basename($stat['name']);
                        $entryExt = strtolower(pathinfo($entryName, PATHINFO_EXTENSION));

                        // Lewati direktori, file tersembunyi macOS, atau file sistem
                        if (str_starts_with($entryName, '.') || str_contains($stat['name'], '__MACOSX')) {
                            continue;
                        }

                        if (in_array($entryExt, ['json', 'geojson'])) {
                            $fileContent = $zip->getFromIndex($i);
                            if ($fileContent !== false) {
                                file_put_contents($destinationPath . '/' . $entryName, $fileContent);
                                $uploaded[] = $entryName;
                                $extractedInZip++;
                            }
                        }
                    }
                    $zip->close();

                    if ($extractedInZip === 0) {
                        $failed[] = $namaFile . ' (tidak ditemukan file .geojson/.json di dalam arsip ZIP)';
                    }
                } else {
                    $failed[] = $namaFile . ' (file arsip ZIP tidak dapat dibuka)';
                }
            } else {
                // File .geojson atau .json
                $content = file_get_contents($file->getRealPath());
                if ($file->getSize() <= 2 * 1024 * 1024) {
                    $decoded = json_decode($content, true);
                    if (json_last_error() !== JSON_ERROR_NONE || empty($decoded['type'])) {
                        $failed[] = $namaFile . ' (struktur JSON tidak valid)';
                        continue;
                    }
                }

                $file->move($destinationPath, $namaFile);
                $uploaded[] = $namaFile;
            }
        }

        if (count($uploaded) === 0 && count($failed) > 0) {
            $errMsg = 'Gagal mengunggah file: ' . implode(', ', $failed);
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $errMsg], 422);
            }
            return redirect()->back()->with('error', $errMsg);
        }

        $msg = count($uploaded) . ' file spasial berhasil ditambahkan (' . implode(', ', array_slice($uploaded, 0, 4)) . (count($uploaded) > 4 ? '...' : '') . ').';
        if (count($failed) > 0) {
            $msg .= ' Catatan: ' . implode(', ', $failed);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => $msg, 'uploaded' => $uploaded]);
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Edit / Ubah Nama File GeoJSON
     */
    public function rename(Request $request)
    {
        $request->validate([
            'old_name' => 'required|string',
            'new_name' => 'required|string|regex:/^[a-zA-Z0-9_\-\s\.]+$/',
        ], [
            'new_name.required' => 'Nama file baru wajib diisi.',
            'new_name.regex'    => 'Nama file hanya boleh mengandung huruf, angka, spasi, titik, underscore, dan strip.',
        ]);

        $oldName = basename($request->input('old_name'));
        $newName = basename($request->input('new_name'));

        // Pastikan ekstensi tetap .geojson atau .json
        $ext = pathinfo($newName, PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), ['json', 'geojson'])) {
            $newName .= '.geojson';
        }

        $oldPath = public_path('geojson/' . $oldName);
        $newPath = public_path('geojson/' . $newName);

        if (!file_exists($oldPath)) {
            return redirect()->back()->with('error', 'File "' . $oldName . '" tidak ditemukan.');
        }

        if (file_exists($newPath) && $oldName !== $newName) {
            return redirect()->back()->with('error', 'File dengan nama "' . $newName . '" sudah ada di server.');
        }

        rename($oldPath, $newPath);

        return redirect()->back()->with('success', 'File berhasil diubah nama menjadi "' . $newName . '".');
    }

    /**
     * Hapus Satu File GeoJSON
     */
    public function delete(Request $request)
    {
        $request->validate(['file_name' => 'required|string']);

        $fileName = basename($request->input('file_name'));
        $filePath = public_path('geojson/' . $fileName);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File "' . $fileName . '" tidak ditemukan.');
        }

        File::delete($filePath);

        return redirect()->back()->with('success', 'File "' . $fileName . '" berhasil dihapus dari server.');
    }

    /**
     * Hapus Banyak File Sekaligus (Bulk Delete)
     */
    public function deleteBulk(Request $request)
    {
        $request->validate([
            'file_names'   => 'required|array|min:1',
            'file_names.*' => 'required|string',
        ], [
            'file_names.required' => 'Pilih minimal satu file untuk dihapus.',
        ]);

        $deleted = [];
        foreach ($request->input('file_names') as $rawName) {
            $fileName = basename($rawName);
            $filePath = public_path('geojson/' . $fileName);
            if (File::exists($filePath)) {
                File::delete($filePath);
                $deleted[] = $fileName;
            }
        }

        if (count($deleted) > 0) {
            return redirect()->back()->with('success', count($deleted) . ' file GeoJSON berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Tidak ada file yang berhasil dihapus.');
    }

    // ─────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────

    private function scanGeojsonFiles(): array
    {
        $fileList = [];
        $path = public_path('geojson');
        $defaultPrefixes = ['baku', 'fungsional', 'potensial', 'saluran', 'bangunan'];

        if (!file_exists($path)) {
            return [];
        }

        $files = File::files($path);
        foreach ($files as $file) {
            $ext = strtolower($file->getExtension());
            if (!in_array($ext, ['json', 'geojson'])) {
                continue;
            }

            $nameLower = strtolower($file->getFilenameWithoutExtension());
            $category = 'custom';

            if (str_contains($nameLower, 'kapuas') || str_starts_with($nameLower, 'ws') || str_contains($nameLower, 'das')) {
                $category = 'ws_kapuas';
            } else {
                foreach ($defaultPrefixes as $prefix) {
                    if (str_starts_with($nameLower, $prefix) || str_contains($nameLower, $prefix)) {
                        $category = $prefix;
                        break;
                    }
                }
            }

            $bytes = $file->getSize();
            if ($bytes >= 1048576) {
                $sizeStr = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $sizeStr = number_format($bytes / 1024, 2) . ' KB';
            } else {
                $sizeStr = $bytes . ' B';
            }

            $fileList[] = [
                'name'       => $file->getFilename(),
                'size'       => $sizeStr,
                'size_bytes' => $bytes,
                'date'       => date('d/m/Y H:i', $file->getMTime()),
                'category'   => $category,
                'extension'  => $ext,
            ];
        }

        // Urutkan alfabetis
        usort($fileList, function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });

        return $fileList;
    }

    private function getGisStats(): array
    {
        $files = $this->scanGeojsonFiles();
        $totalSize = array_sum(array_column($files, 'size_bytes'));

        $categories = [
            'baku'       => 0,
            'fungsional' => 0,
            'potensial'  => 0,
            'saluran'    => 0,
            'bangunan'   => 0,
            'ws_kapuas'  => 0,
            'custom'     => 0,
        ];

        foreach ($files as $f) {
            $cat = $f['category'];
            if (!isset($categories[$cat])) {
                $categories[$cat] = 0;
            }
            $categories[$cat]++;
        }

        return [
            'total_files' => count($files),
            'total_size'  => $totalSize >= 1048576
                ? number_format($totalSize / 1048576, 1) . ' MB'
                : number_format($totalSize / 1024, 1) . ' KB',
            'categories'  => $categories,
        ];
    }
}
