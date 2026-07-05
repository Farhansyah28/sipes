<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\KategoriBarang;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\BatchStok;

class KantinSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = 1;

        $katMakanan = KategoriBarang::create(['tenant_id' => $tenantId, 'nama' => 'Makanan Ringan']);
        $katMinuman = KategoriBarang::create(['tenant_id' => $tenantId, 'nama' => 'Minuman']);
        $katATK = KategoriBarang::create(['tenant_id' => $tenantId, 'nama' => 'Alat Tulis']);

        $sup1 = Supplier::create(['tenant_id' => $tenantId, 'nama' => 'Agen Sembako Makmur', 'kontak' => '08123456789', 'alamat' => 'Pasar Induk']);
        $sup2 = Supplier::create(['tenant_id' => $tenantId, 'nama' => 'Toko Buku Gramedia', 'kontak' => '08987654321', 'alamat' => 'Mall Pusat Kota']);

        // Makanan
        $b1 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katMakanan->id, 'kode_barang' => 'MKN-001', 'nama' => 'Chitato Rasa Sapi Panggang', 'harga_jual' => 6000]);
        $b2 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katMakanan->id, 'kode_barang' => 'MKN-002', 'nama' => 'Indomie Goreng', 'harga_jual' => 3500]);
        // Minuman
        $b3 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katMinuman->id, 'kode_barang' => 'MNM-001', 'nama' => 'Teh Pucuk Harum', 'harga_jual' => 4000]);
        $b4 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katMinuman->id, 'kode_barang' => 'MNM-002', 'nama' => 'Aqua Botol 600ml', 'harga_jual' => 3000]);
        // ATK
        $b5 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katATK->id, 'kode_barang' => 'ATK-001', 'nama' => 'Buku Tulis Sinar Dunia', 'harga_jual' => 5000]);
        $b6 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katATK->id, 'kode_barang' => 'ATK-002', 'nama' => 'Pulpen Standard R4', 'harga_jual' => 2000]);

        // Batch Stok (Restock - FIFO data setup)
        BatchStok::create([
            'tenant_id' => $tenantId, 'barang_id' => $b1->id, 'supplier_id' => $sup1->id, 
            'qty_awal' => 50, 'qty_sisa' => 5, // sisa 5 dari batch lama
            'harga_beli_satuan' => 4500, 'tanggal_masuk' => date('Y-m-d', strtotime('-2 months')), 'tanggal_kadaluarsa' => date('Y-m-d', strtotime('+3 months'))
        ]);
        BatchStok::create([
            'tenant_id' => $tenantId, 'barang_id' => $b1->id, 'supplier_id' => $sup1->id, 
            'qty_awal' => 100, 'qty_sisa' => 100, // batch baru utuh
            'harga_beli_satuan' => 4700, 'tanggal_masuk' => date('Y-m-d', strtotime('-1 week')), 'tanggal_kadaluarsa' => date('Y-m-d', strtotime('+5 months'))
        ]);
        $b1->stok_total = 105;
        $b1->save();

        BatchStok::create([
            'tenant_id' => $tenantId, 'barang_id' => $b3->id, 'supplier_id' => $sup1->id, 
            'qty_awal' => 24, 'qty_sisa' => 20, 
            'harga_beli_satuan' => 3000, 'tanggal_masuk' => date('Y-m-d', strtotime('-2 weeks')), 'tanggal_kadaluarsa' => date('Y-m-d', strtotime('+10 months'))
        ]);
        $b3->stok_total = 20;
        $b3->save();

        BatchStok::create([
            'tenant_id' => $tenantId, 'barang_id' => $b5->id, 'supplier_id' => $sup2->id, 
            'qty_awal' => 50, 'qty_sisa' => 45, 
            'harga_beli_satuan' => 3500, 'tanggal_masuk' => date('Y-m-d', strtotime('-1 month')), 'tanggal_kadaluarsa' => null
        ]);
        $b5->stok_total = 45;
        $b5->save();
        $katPeralatanMandi = KategoriBarang::create(['tenant_id' => $tenantId, 'nama' => 'Peralatan Mandi']);

        // Makanan Tambahan
        $b7 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katMakanan->id, 'kode_barang' => 'MKN-003', 'nama' => 'Taro Net Seaweed', 'harga_jual' => 5000, 'stok_minimal' => 20]);
        $b8 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katMakanan->id, 'kode_barang' => 'MKN-004', 'nama' => 'Biskuit Roma Kelapa', 'harga_jual' => 12000, 'stok_minimal' => 10]);
        $b9 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katMakanan->id, 'kode_barang' => 'MKN-005', 'nama' => 'Beng-Beng Share It', 'harga_jual' => 15000, 'stok_minimal' => 5]);
        $b10 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katMakanan->id, 'kode_barang' => 'MKN-006', 'nama' => 'Roti Aoka Cokelat', 'harga_jual' => 3000, 'stok_minimal' => 30]);

        // Minuman Tambahan
        $b11 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katMinuman->id, 'kode_barang' => 'MNM-003', 'nama' => 'Kopiko 78c', 'harga_jual' => 7000, 'stok_minimal' => 15]);
        $b12 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katMinuman->id, 'kode_barang' => 'MNM-004', 'nama' => 'Susu Bear Brand', 'harga_jual' => 11000, 'stok_minimal' => 20]);
        $b13 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katMinuman->id, 'kode_barang' => 'MNM-005', 'nama' => 'Floridina Orange', 'harga_jual' => 3500, 'stok_minimal' => 20]);

        // ATK Tambahan
        $b14 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katATK->id, 'kode_barang' => 'ATK-003', 'nama' => 'Pensil 2B Faber Castell', 'harga_jual' => 4500, 'stok_minimal' => 15]);
        $b15 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katATK->id, 'kode_barang' => 'ATK-004', 'nama' => 'Penghapus Joyko', 'harga_jual' => 1500, 'stok_minimal' => 15]);
        $b16 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katATK->id, 'kode_barang' => 'ATK-005', 'nama' => 'Tipe-X Kenko', 'harga_jual' => 6000, 'stok_minimal' => 10]);

        // Peralatan Mandi
        $b17 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katPeralatanMandi->id, 'kode_barang' => 'MND-001', 'nama' => 'Sabun Lifebuoy Merah', 'harga_jual' => 4000, 'stok_minimal' => 15]);
        $b18 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katPeralatanMandi->id, 'kode_barang' => 'MND-002', 'nama' => 'Sampo Clear Men Sachet', 'harga_jual' => 1000, 'stok_minimal' => 50]);
        $b19 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katPeralatanMandi->id, 'kode_barang' => 'MND-003', 'nama' => 'Pasta Gigi Pepsodent', 'harga_jual' => 6500, 'stok_minimal' => 10]);
        $b20 = Barang::create(['tenant_id' => $tenantId, 'kategori_barang_id' => $katPeralatanMandi->id, 'kode_barang' => 'MND-004', 'nama' => 'Deterjen Daia Putih 290g', 'harga_jual' => 5500, 'stok_minimal' => 20]);

        // --- BATCH STOK TAMBAHAN ---
        $items = [
            [$b2, 3000, 100], [$b4, 2500, 150], [$b6, 1500, 50],
            [$b7, 4000, 40], [$b8, 10500, 30], [$b9, 13000, 20], [$b10, 2500, 100],
            [$b11, 5500, 30], [$b12, 9500, 40], [$b13, 2800, 50],
            [$b14, 3500, 40], [$b15, 1000, 60], [$b16, 4500, 25],
            [$b17, 3200, 50], [$b18, 800, 200], [$b19, 5200, 30], [$b20, 4500, 40],
        ];

        foreach ($items as $item) {
            $barang = $item[0];
            $hpp = $item[1];
            $qty = $item[2];

            BatchStok::create([
                'tenant_id' => $tenantId, 
                'barang_id' => $barang->id, 
                'supplier_id' => $sup1->id, 
                'qty_awal' => $qty, 
                'qty_sisa' => $qty, 
                'harga_beli_satuan' => $hpp, 
                'tanggal_masuk' => date('Y-m-d', strtotime('-1 week')), 
                'tanggal_kadaluarsa' => date('Y-m-d', strtotime('+6 months'))
            ]);
            $barang->stok_total = $qty;
            $barang->save();
        }
    }
}
