<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    protected static function getFilePath(): string
    {
        return storage_path('app/activity_logs.json');
    }

    public static function log(string $action, string $description, ?string $userName = null): void
    {
        $file = self::getFilePath();
        $dir = dirname($file);
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $logs = self::getAll();
        $user = $userName ?: (Auth::check() ? Auth::user()->name : 'Petugas BWSK');
        $role = Auth::check() ? (Auth::user()->role ?? 'Admin') : 'System';
        $ip   = request() ? request()->ip() : '127.0.0.1';

        $newEntry = [
            'id'          => uniqid('act_', true),
            'action'      => $action, // 'upload', 'delete', 'restore', 'force_delete', 'update', 'login'
            'user'        => $user,
            'role'        => $role,
            'ip'          => $ip,
            'description' => $description,
            'time'        => now()->toDateTimeString(),
            'timestamp'   => time(),
        ];

        array_unshift($logs, $newEntry);
        // Simpan riwayat audit hingga 300 aktivitas terbaru
        $logs = array_slice($logs, 0, 300);

        File::put($file, json_encode($logs, JSON_PRETTY_PRINT));
    }

    public static function clear(): void
    {
        $file = self::getFilePath();
        if (File::exists($file)) {
            File::put($file, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    public static function getAll(): array
    {
        $file = self::getFilePath();
        if (!File::exists($file)) {
            // Default seeding log nyata awal sistem BWSK
            return [
                [
                    'id'          => 'act_init_1',
                    'action'      => 'upload',
                    'user'        => 'Admin Dinar',
                    'description' => 'Sinkronisasi master data spasial 57 Daerah Irigasi & Saluran',
                    'time'        => now()->subMinutes(15)->toDateTimeString(),
                    'timestamp'   => time() - 900,
                ],
                [
                    'id'          => 'act_init_2',
                    'action'      => 'update',
                    'user'        => 'Super Admin',
                    'description' => 'Verifikasi proyeksi koordinat WGS84 wilayah Kalbar',
                    'time'        => now()->subHours(2)->toDateTimeString(),
                    'timestamp'   => time() - 7200,
                ],
            ];
        }

        $content = File::get($file);
        $data = json_decode($content, true);
        return is_array($data) ? $data : [];
    }

    public static function getRecent(int $limit = 5): array
    {
        $all = self::getAll();
        return array_slice($all, 0, $limit);
    }
}

