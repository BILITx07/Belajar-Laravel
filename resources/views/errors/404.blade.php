@extends('layout.master')

@section('title', '404 - Halaman Tidak Ditemukan | SIPDIRA SPASIAL')
@section('hide_footer', true)

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 120px); background: #f8fafc; padding: 40px 20px;">
    <div class="text-center" style="max-width: 580px; width: 100%;">
        <!-- Badge Status -->
        <div class="d-inline-flex align-items-center mb-3 px-3 py-1.5 rounded-pill shadow-xs" style="background: #ffffff; border: 1.5px solid #e2e8f0; gap: 8px;">
            <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #f59e0b;"></span>
            <span style="font-size: 11px; font-weight: 700; color: #10143a; letter-spacing: 0.5px;">KEMENTERIAN PEKERJAAN UMUM</span>
        </div>

        <!-- 404 Hero Number -->
        <div style="font-size: clamp(72px, 12vw, 110px); font-weight: 900; line-height: 1; color: #10143a; letter-spacing: -2px; margin-bottom: 12px; font-family: 'Plus Jakarta Sans', sans-serif;">
            4<span style="color: #f59e0b;">0</span>4
        </div>

        <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 10px;">
            Halaman Tidak Ditemukan
        </h1>

        <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin-bottom: 28px;">
            Tautan yang Anda tuju mungkin telah dipindahkan, dihapus, atau alamat URL yang dimasukkan kurang tepat. Silakan kembali ke peta interaktif SIPDIRA SPASIAL.
        </p>

        <!-- Action Buttons -->
        <div class="d-flex flex-wrap justify-content-center" style="gap: 12px;">
            <a href="{{ route('peta') }}" class="btn d-inline-flex align-items-center shadow-sm" style="background: #10143a; color: #ffffff; border: 1.5px solid #fad605; border-radius: 9px; padding: 10px 22px; font-weight: 700; font-size: 13.5px; gap: 8px; text-decoration: none;">
                <i class="fas fa-map-marked-alt text-warning"></i>
                <span>Kembali ke Peta Spasial</span>
            </a>
            <a href="{{ url('/') }}" class="btn d-inline-flex align-items-center" style="background: #ffffff; color: #1e293b; border: 1.5px solid #cbd5e1; border-radius: 9px; padding: 10px 20px; font-weight: 600; font-size: 13.5px; gap: 8px; text-decoration: none;">
                <i class="fas fa-home text-muted"></i>
                <span>Beranda Utama</span>
            </a>
        </div>

        <!-- Instansi Footer -->
        <div class="mt-5 pt-3 border-top" style="border-color: #e2e8f0 !important;">
            <small style="color: #94a3b8; font-size: 11px;">
                SIPDIRA SPASIAL &bull; Balai Wilayah Sungai Kalimantan I Pontianak
            </small>
        </div>
    </div>
</div>
@endsection
