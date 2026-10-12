<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProdukTest extends TestCase
{
    use RefreshDatabase;

    public function test_produk_page_renders_cleanly_for_authenticated_user(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@gmail.com',
            'role' => 'admin',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => 'Pakaian',
            'deskripsi' => 'Kategori Pakaian',
        ]);

        Produk::create([
            'kode_produk' => 'PRD-1001',
            'nama_produk' => 'Kemeja Polos',
            'harga' => 100000,
            'stok' => 10,
            'kategori_id' => $kategori->id,
            'deskripsi' => 'Kemeja bahan katun premium',
            'gambar' => 'produk/test.jpg',
        ]);

        $response = $this->actingAs($user)->get('/produk');

        $response->assertStatus(200);
        $response->assertSee('Daftar Produk Kami');
        $response->assertSee('Data Produk');
        $response->assertSee('Kemeja Polos');
        $response->assertSee('PRD-1001');
    }

    public function test_produk_gambar_url_accessor(): void
    {
        $produk = new Produk([
            'nama_produk' => 'Test',
            'gambar' => 'produk/sample.jpg',
        ]);

        $this->assertNotNull($produk->gambar_url);
        $this->assertStringContainsString('storage/produk/sample.jpg', $produk->gambar_url);
    }
}
