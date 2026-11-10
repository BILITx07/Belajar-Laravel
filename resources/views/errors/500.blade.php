@extends('layout.master')

@section('title', '500 - Kendala Sistem | SIPDIRA SPASIAL')
@section('hide_footer', true)

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 120px); background: #f8fafc; padding: 40px 20px;">
    <div class="text-center" style="max-width: 580px; width: 100%;">
        <!-- Badge Status -->
        <div class="d-inline-flex align-items-center mb-3 px-3 py-1.5 rounded-pill shadow-xs" style="background: #ffffff; border: 1.5px solid #e2e8f0; gap: 8px;">
            <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #ef4444;"></span>
            <span style="font-size: 11px; font-weight: 700; color: #10143a; letter-spacing: 0.5px;">KEMENTERIAN PEKERJAAN UMUM</span>
        </div>

        <!-- 500 Hero Number -->
        <div style="font-size: clamp(72px, 12vw, 110px); font-weight: 900; line-height: 1; color: #10143a; letter-spacing: -2px; margin-bottom: 12px; font-family: 'Plus Jakarta Sans', sans-serif;">
            5<span style="color: #ef4444;">0</span>0
        </div>

        <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 10px;">
            Terjadi Kendala Sistem
        </h1>

        <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin-bottom: 28px;">
            Server sedang memproses permintaan atau mengalami kendala sementara. Tim teknis Balai Wilayah Sungai Kalimantan I sedang memantau ketersediaan layanan.
        </p>

        <!-- Action Buttons -->
        <div class="d-flex flex-wrap justify-content-center" style="gap: 12px;">
            <a href="{{ route('peta') }}" class="btn d-inline-flex align-items-center shadow-sm" style="background: #10143a; color: #ffffff; border: 1.5px solid #fad605; border-radius: 9px; padding: 10px 22px; font-weight: 700; font-size: 13.5px; gap: 8px; text-decoration: none;">
                <i class="fas fa-redo-alt text-warning"></i>
                <span>Muat Ulang Peta</span>
            </a>
            <a href="https://wa.me/6285389999100" target="_blank" rel="noopener noreferrer" class="btn d-inline-flex align-items-center" style="background: #ffffff; color: #15803d; border: 1.5px solid #cbd5e1; border-radius: 9px; padding: 10px 20px; font-weight: 600; font-size: 13.5px; gap: 8px; text-decoration: none;">
                <i class="fab fa-whatsapp"></i>
                <span>Laporkan Kendala</span>
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
