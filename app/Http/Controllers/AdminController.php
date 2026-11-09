<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Helpers\ActivityLogger;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Halaman Depan Admin (WebGIS Interactive Dashboard Sesuai Gambar Referensi SIPERISDA & FEWS)
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

        // Data Agregat per Kabupaten untuk Grafik SIPERISDA
        $chartKabupaten = [];
        foreach ($lokasi as $item) {
            $kab = $item['kabupaten'];
            if (!isset($chartKabupaten[$kab])) {
                $chartKabupaten[$kab] = ['baku' => 0, 'fungsional' => 0];
            }
            $chartKabupaten[$kab]['baku'] += (float) ($item['luas_baku'] ?? 0);
            $chartKabupaten[$kab]['fungsional'] += (float) ($item['luas_fung'] ?? 0);
        }

        $allGisFiles = $this->scanGeojsonFiles();
        $recentFiles = array_slice($allGisFiles, 0, 5);
        $totalUsers = User::count();
        $recentActivities = ActivityLogger::getRecent(6);

        return view('admin.dashboard', compact(
            'lokasi',
            'kabupatenList',
            'geojsonFiles',
            'availableTypes',
            'statistik',
            'stats',
            'chartKabupaten',
            'recentFiles',
            'totalUsers',
            'recentActivities'
        ));
    }

    /**
     * Halaman Manajemen Master Data GIS (CRUD File Spasial & Kotak Sampah)
     */
    public function kelolaGis(Request $request)
    {
        $search = $request->input('search');
        $filterType = $request->input('type', 'semua');
        $currentTab = $request->input('tab', 'active'); // 'active' atau 'trash'
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $activeFiles = $this->scanGeojsonFiles();
        $trashFiles  = $this->scanTrashFiles();

        $targetFiles = ($currentTab === 'trash') ? $trashFiles : $activeFiles;

        // Filter pencarian teks
        if ($search) {
            $targetFiles = array_filter($targetFiles, function ($f) use ($search) {
                return stripos($f['name'], $search) !== false;
            });
        }

        // Filter kategori spasial (hanya berlaku di tab file aktif)
        if ($currentTab === 'active' && $filterType !== 'semua' && !empty($filterType)) {
            if ($filterType === 'irigasi') {
                $targetFiles = array_filter($targetFiles, function ($f) {
                    return in_array($f['category'], ['baku', 'fungsional', 'potensial']);
                });
            } elseif ($filterType === 'infrastruktur') {
                $targetFiles = array_filter($targetFiles, function ($f) {
                    return in_array($f['category'], ['saluran', 'bangunan']);
                });
            } elseif ($filterType === 'ws_kapuas' || $filterType === 'skema_sungai') {
                $targetFiles = array_filter($targetFiles, function ($f) {
                    return $f['category'] === 'ws_kapuas' || stripos($f['name'], 'kapuas') !== false || stripos($f['name'], 'ws') !== false;
                });
            } else {
                $targetFiles = array_filter($targetFiles, function ($f) use ($filterType) {
                    return $f['category'] === $filterType || stripos($f['name'], $filterType) !== false;
                });
            }
        }

        $targetFiles = array_values($targetFiles);
        $totalItems = count($targetFiles);

        // Pagination
        $currentPage = max(1, (int) $request->input('page', 1));
        $totalPages = max(1, (int) ceil($totalItems / $perPage));
        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }
        $offset = ($currentPage - 1) * $perPage;
        $fileList = array_slice($targetFiles, $offset, $perPage);

        $stats = $this->getGisStats();

        return view('admin.kelola_gis', [
            'title'        => 'Manajemen Master Data GIS — BWS Kalimantan I',
            'fileList'     => $fileList,
            'stats'        => $stats,
            'search'       => $search,
            'filterType'   => $filterType,
            'currentTab'   => $currentTab,
            'totalActive'  => count($activeFiles),
            'totalTrash'   => count($trashFiles),
            'perPage'      => $perPage,
            'currentPage'  => $currentPage,
            'totalPages'   => $totalPages,
            'totalItems'   => $totalItems,
            'offset'       => $offset,
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
        $kategori = $request->input('kategori', 'auto');

        // Fungsi pembantu untuk membubuhkan prefix kategori jika dipilih pengguna
        $applyKategoriPrefix = function ($fileName, $cat) {
            if (!$cat || $cat === 'auto') {
                return $fileName;
            }
            $prefix = strtolower($cat) . '_';
            $knownPrefixes = ['baku_', 'fungsional_', 'potensial_', 'saluran_', 'bangunan_', 'ws_kapuas_', 'ws_'];
            foreach ($knownPrefixes as $kp) {
                if (str_starts_with(strtolower($fileName), $kp)) {
                    return $fileName; // Sudah memiliki awalan kategori
                }
            }
            return $prefix . $fileName;
        };

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
                                $targetName = $applyKategoriPrefix($entryName, $kategori);
                                file_put_contents($destinationPath . '/' . $targetName, $fileContent);
                                $uploaded[] = $targetName;
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

                $targetName = $applyKategoriPrefix($namaFile, $kategori);
                $file->move($destinationPath, $targetName);
                $uploaded[] = $targetName;
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

        ActivityLogger::log('upload', 'Mengunggah ' . count($uploaded) . ' file spasial: ' . implode(', ', array_slice($uploaded, 0, 3)));

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
        ActivityLogger::log('rename', "Mengubah nama file dari '{$oldName}' menjadi '{$newName}'");

        return redirect()->back()->with('success', 'File berhasil diubah nama menjadi "' . $newName . '".');
    }

    /**
     * Soft Delete: Pindahkan File GeoJSON ke Kotak Sampah
     */
    public function delete(Request $request)
    {
        $request->validate(['file_name' => 'required|string']);

        $fileName = basename($request->input('file_name'));
        $filePath = public_path('geojson/' . $fileName);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File "' . $fileName . '" tidak ditemukan.');
        }

        $trashPath = $this->getTrashPath();
        $targetTrashFile = $trashPath . '/' . $fileName;

        // Pindahkan file ke folder kotak sampah (Soft Delete)
        File::move($filePath, $targetTrashFile);

        // Catat metadata file di kotak sampah
        $meta = $this->getTrashMeta();
        $meta[$fileName] = [
            'original_name' => $fileName,
            'deleted_at'    => now()->format('d-M-Y H:i'),
            'deleted_by'    => auth()->check() ? auth()->user()->name : 'Petugas BWSK',
            'role'          => auth()->check() ? auth()->user()->role : 'Admin',
            'size'          => number_format(filesize($targetTrashFile) / 1024, 1) . ' KB',
        ];
        $this->saveTrashMeta($meta);

        ActivityLogger::log('delete', "Memindahkan file spasial '{$fileName}' ke Kotak Sampah");

        return redirect()->route('admin.kelola-gis', ['tab' => 'trash'])->with('success', 'File "' . $fileName . '" berhasil dipindahkan ke Kotak Sampah.');
    }

    /**
     * Hapus Banyak File Sekaligus ke Kotak Sampah (Bulk Soft Delete - Khusus Super Admin)
     */
    public function deleteBulk(Request $request)
    {
        if (auth()->check() && strtolower(auth()->user()->role ?? '') !== 'superadmin') {
            return redirect()->back()->with('error', 'Akses Ditolak: Fitur penghapusan massal hanya dapat dilakukan oleh Super Admin.');
        }

        $request->validate([
            'file_names'   => 'required|array|min:1',
            'file_names.*' => 'required|string',
        ], [
            'file_names.required' => 'Pilih minimal satu file untuk dihapus.',
        ]);

        $trashPath = $this->getTrashPath();
        $meta = $this->getTrashMeta();
        $deleted = [];

        foreach ($request->input('file_names') as $rawName) {
            $fileName = basename($rawName);
            $filePath = public_path('geojson/' . $fileName);
            if (File::exists($filePath)) {
                $targetTrashFile = $trashPath . '/' . $fileName;
                File::move($filePath, $targetTrashFile);
                $meta[$fileName] = [
                    'original_name' => $fileName,
                    'deleted_at'    => now()->format('d-M-Y H:i'),
                    'deleted_by'    => auth()->check() ? auth()->user()->name : 'Super Admin',
                    'role'          => 'superadmin',
                    'size'          => number_format(filesize($targetTrashFile) / 1024, 1) . ' KB',
                ];
                $deleted[] = $fileName;
            }
        }

        $this->saveTrashMeta($meta);

        if (count($deleted) > 0) {
            ActivityLogger::log('delete', "Memindahkan massal " . count($deleted) . " file ke Kotak Sampah");
            return redirect()->route('admin.kelola-gis', ['tab' => 'trash'])->with('success', count($deleted) . ' file GeoJSON dipindahkan ke Kotak Sampah.');
        }

        return redirect()->back()->with('error', 'Tidak ada file yang berhasil dipindahkan.');
    }

    /**
     * Pulihkan File dari Kotak Sampah (Restore)
     */
    public function restoreGis(Request $request)
    {
        $request->validate(['file_name' => 'required|string']);
        $fileName = basename($request->input('file_name'));
        $trashPath = $this->getTrashPath();
        $trashFile = $trashPath . '/' . $fileName;

        if (!File::exists($trashFile)) {
            return redirect()->back()->with('error', 'File "' . $fileName . '" tidak ditemukan di Kotak Sampah.');
        }

        $destination = public_path('geojson/' . $fileName);
        File::move($trashFile, $destination);

        $meta = $this->getTrashMeta();
        unset($meta[$fileName]);
        $this->saveTrashMeta($meta);

        ActivityLogger::log('restore', "Memulihkan file spasial '{$fileName}' dari Kotak Sampah kembali aktif ke peta");

        return redirect()->route('admin.kelola-gis', ['tab' => 'active'])->with('success', 'File "' . $fileName . '" berhasil dipulihkan dan aktif kembali di peta WebGIS.');
    }

    /**
     * Hapus Permanen File dari Kotak Sampah (Force Delete - Khusus Super Admin)
     */
    public function forceDeleteGis(Request $request)
    {
        if (auth()->check() && strtolower(auth()->user()->role ?? '') !== 'superadmin') {
            return redirect()->back()->with('error', 'Akses Ditolak: Penghapusan permanen hanya diizinkan untuk Super Admin.');
        }

        $request->validate(['file_name' => 'required|string']);
        $fileName = basename($request->input('file_name'));
        $trashPath = $this->getTrashPath();
        $trashFile = $trashPath . '/' . $fileName;

        if (File::exists($trashFile)) {
            File::delete($trashFile);
        }

        $meta = $this->getTrashMeta();
        unset($meta[$fileName]);
        $this->saveTrashMeta($meta);

        ActivityLogger::log('force_delete', "Menghapus permanen file spasial '{$fileName}' dari server");

        return redirect()->route('admin.kelola-gis', ['tab' => 'trash'])->with('success', 'File "' . $fileName . '" telah dihapus permanen selamanya.');
    }

    /**
     * Kosongkan Seluruh Kotak Sampah (Empty Trash - Khusus Super Admin)
     */
    public function emptyTrashGis(Request $request)
    {
        if (auth()->check() && strtolower(auth()->user()->role ?? '') !== 'superadmin') {
            return redirect()->back()->with('error', 'Akses Ditolak: Pengosongan tempat sampah hanya diizinkan untuk Super Admin.');
        }

        $trashPath = $this->getTrashPath();
        $files = File::files($trashPath);
        $count = count($files);

        foreach ($files as $file) {
            File::delete($file->getRealPath());
        }

        $this->saveTrashMeta([]);

        ActivityLogger::log('force_delete', "Mengosongkan kotak sampah ({$count} file dihapus permanen)");

        return redirect()->route('admin.kelola-gis', ['tab' => 'trash'])->with('success', 'Seluruh kotak sampah berhasil dibersihkan (' . $count . ' file terhapus permanen).');
    }

    /**
     * Halaman Log Aktivitas Audit Petugas & Operasional
     */
    public function activityLogs(Request $request)
    {
        $search       = trim((string) $request->input('search', ''));
        $actionFilter = $request->input('action', 'all');
        $allLogs      = ActivityLogger::getAll();

        if ($search !== '') {
            $allLogs = array_filter($allLogs, function($l) use ($search) {
                return stripos($l['description'] ?? '', $search) !== false ||
                       stripos($l['user'] ?? '', $search) !== false ||
                       stripos($l['action'] ?? '', $search) !== false ||
                       stripos($l['role'] ?? '', $search) !== false;
            });
        }

        if ($actionFilter !== 'all' && !empty($actionFilter)) {
            $allLogs = array_filter($allLogs, function($l) use ($actionFilter) {
                return ($l['action'] ?? '') === $actionFilter;
            });
        }

        $allLogs = array_values($allLogs);
        $totalLogs = count($allLogs);

        $perPage = (int) $request->input('per_page', 15);
        if (!in_array($perPage, [10, 15, 25, 50, 100])) {
            $perPage = 15;
        }

        $currentPage = max(1, (int) $request->input('page', 1));
        $totalPages  = max(1, (int) ceil($totalLogs / $perPage));
        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }
        $offset = ($currentPage - 1) * $perPage;
        $logs   = array_slice($allLogs, $offset, $perPage);

        // Rekap hitungan kategori aksi
        $rawLogs = ActivityLogger::getAll();
        $statsAksi = [
            'total'        => count($rawLogs),
            'upload'       => count(array_filter($rawLogs, fn($l) => ($l['action'] ?? '') === 'upload')),
            'delete'       => count(array_filter($rawLogs, fn($l) => ($l['action'] ?? '') === 'delete')),
            'restore'      => count(array_filter($rawLogs, fn($l) => ($l['action'] ?? '') === 'restore')),
            'force_delete' => count(array_filter($rawLogs, fn($l) => ($l['action'] ?? '') === 'force_delete')),
        ];

        return view('admin.activity_logs', compact(
            'logs',
            'search',
            'actionFilter',
            'perPage',
            'currentPage',
            'totalPages',
            'totalLogs',
            'statsAksi',
            'offset'
        ));
    }

    /**
     * Bersihkan Seluruh Riwayat Log Aktivitas (Khusus Super Admin)
     */
    public function clearActivityLogs(Request $request)
    {
        if (!auth()->check() || strtolower(auth()->user()->role ?? '') !== 'superadmin') {
            return redirect()->back()->with('error', 'Akses Ditolak: Hanya Super Admin yang diizinkan mengosongkan log audit.');
        }

        ActivityLogger::clear();
        ActivityLogger::log('clear_logs', 'Mengosongkan riwayat log aktivitas audit');

        return redirect()->back()->with('success', 'Riwayat log aktivitas berhasil dibersihkan.');
    }

    // ─────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────

    private function getTrashPath(): string
    {
        $path = storage_path('app/trash_geojson');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        return $path;
    }

    private function getTrashMeta(): array
    {
        $metaFile = storage_path('app/trash_geojson_meta.json');
        if (!File::exists($metaFile)) {
            return [];
        }
        $data = json_decode(File::get($metaFile), true);
        return is_array($data) ? $data : [];
    }

    private function saveTrashMeta(array $meta): void
    {
        $metaFile = storage_path('app/trash_geojson_meta.json');
        File::put($metaFile, json_encode($meta, JSON_PRETTY_PRINT));
    }

    private function scanTrashFiles(): array
    {
        $trashPath = $this->getTrashPath();
        $meta = $this->getTrashMeta();
        $files = File::files($trashPath);
        $list = [];

        foreach ($files as $file) {
            $name = $file->getFilename();
            $bytes = $file->getSize();
            $sizeStr = $bytes >= 1048576 ? number_format($bytes / 1048576, 2) . ' MB' : number_format($bytes / 1024, 2) . ' KB';
            $fileMeta = $meta[$name] ?? [];

            $list[] = [
                'name'        => $name,
                'size'        => $sizeStr,
                'size_bytes'  => $bytes,
                'deleted_at'  => $fileMeta['deleted_at'] ?? date('d-M-Y H:i', $file->getMTime()),
                'deleted_by'  => $fileMeta['deleted_by'] ?? 'Petugas BWSK',
                'role'        => $fileMeta['role'] ?? 'Admin',
                'extension'   => strtolower($file->getExtension()),
            ];
        }

        // Urutkan waktu terhapus paling baru
        usort($list, function ($a, $b) {
            return strcasecmp($b['deleted_at'], $a['deleted_at']);
        });

        return $list;
    }

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
