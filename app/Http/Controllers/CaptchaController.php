<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    /**
     * Menghasilkan gambar CAPTCHA dinamis anti-bot.
     */
    public function generate()
    {
        // Karakter acak yang mudah dibaca manusia (menghindari 0, O, 1, I, l agar tidak ambigu)
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';   
        $code = '';
        $charLength = strlen($characters);
        for ($i = 0; $i < 5; $i++) {
            $code .= $characters[random_int(0, $charLength - 1)];
        }

        // Simpan kode captcha ke session
        session(['captcha_code' => $code]);

        // Fallback jika ekstensi PHP GD tidak tersedia di hosting
        if (!extension_loaded('gd') || !function_exists('imagecreatetruecolor')) {
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="160" height="46" viewBox="0 0 160 46">';
            $svg .= '<rect width="100%" height="100%" fill="#f8f9fa" stroke="#dee2e6" stroke-width="1"/>';
            $svg .= '<line x1="10" y1="10" x2="150" y2="35" stroke="#cbd5e1" stroke-width="2"/>';
            $svg .= '<line x1="15" y1="38" x2="145" y2="12" stroke="#94a3b8" stroke-width="1"/>';
            $svg .= '<line x1="25" y1="20" x2="135" y2="28" stroke="#cbd5e1" stroke-width="1"/>';
            $svg .= '<text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle" font-family="monospace, Arial" font-size="22" font-weight="bold" fill="#2563eb" letter-spacing="7">' . htmlspecialchars($code) . '</text>';
            $svg .= '</svg>';

            return response($svg)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }

        // Dimensi gambar CAPTCHA
        $width = 160;
        $height = 46;
        $image = imagecreatetruecolor($width, $height);

        // Background warna abu-abu terang (#f8f9fa)
        $bg = imagecolorallocate($image, 248, 249, 250);
        imagefill($image, 0, 0, $bg);

        // Tambahkan garis acak (noise lines) untuk mengelabui bot / OCR
        for ($i = 0; $i < 5; $i++) {
            $lineColor = imagecolorallocate(
                $image,
                random_int(170, 210),
                random_int(170, 210),
                random_int(190, 230)
            );
            imagesetthickness($image, random_int(1, 2));
            imageline(
                $image,
                random_int(0, $width),
                random_int(0, $height),
                random_int(0, $width),
                random_int(0, $height),
                $lineColor
            );
        }

        // Tambahkan bintik-bintik acak (noise dots)
        for ($i = 0; $i < 100; $i++) {
            $dotColor = imagecolorallocate(
                $image,
                random_int(140, 200),
                random_int(140, 200),
                random_int(150, 220)
            );
            imagesetpixel($image, random_int(0, $width), random_int(0, $height), $dotColor);
        }

        // Cari font TTF yang valid dan bisa dibaca (> 1KB)
        $fontPaths = [
            public_path('fonts/arial.ttf'),
            base_path('public/fonts/arial.ttf'),
            'C:\\Windows\\Fonts\\arialbd.ttf',
            'C:\\Windows\\Fonts\\arial.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
            '/usr/share/fonts/TTF/DejaVuSans.ttf',
        ];

        $font = null;
        foreach ($fontPaths as $path) {
            if (file_exists($path) && is_readable($path) && @filesize($path) > 1024) {
                $font = $path;
                break;
            }
        }

        // Warna teks variatif (biru / abu tua agar kontras dan terbaca jelas)
        $textColors = [
            imagecolorallocate($image, 46, 89, 217),   // Biru SB Admin (#2e59d9)
            imagecolorallocate($image, 78, 115, 223),  // Biru terang (#4e73df)
            imagecolorallocate($image, 30, 41, 59),    // Slate 800
            imagecolorallocate($image, 37, 99, 235),   // Blue 600
            imagecolorallocate($image, 51, 65, 85),    // Slate 700
        ];

        $ttfRendered = false;
        if ($font && function_exists('imagettftext')) {
            try {
                $startX = 18;
                $spacing = 27;
                for ($i = 0; $i < 5; $i++) {
                    $angle = random_int(-15, 15);
                    $y = random_int(32, 35);
                    $color = $textColors[array_rand($textColors)];
                    @imagettftext($image, 20, $angle, $startX + ($i * $spacing), $y, $color, $font, $code[$i]);
                }
                $ttfRendered = true;
            } catch (\Throwable $e) {
                $ttfRendered = false;
            }
        }

        if (!$ttfRendered) {
            // Fallback font bawaan GD jika font TTF tidak ada atau gagal dibaca (100% aman di hosting manapun)
            $startX = 20;
            $spacing = 25;
            for ($i = 0; $i < 5; $i++) {
                $color = $textColors[array_rand($textColors)];
                $y = random_int(12, 16);
                imagestring($image, 5, $startX + ($i * $spacing), $y, $code[$i], $color);
            }
        }

        // Garis tepi (border) tipis di sekitar gambar
        $borderColor = imagecolorallocate($image, 222, 226, 230);
        imagerectangle($image, 0, 0, $width - 1, $height - 1, $borderColor);

        // Output gambar sebagai PNG
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return response($imageData)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
