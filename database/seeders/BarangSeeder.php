<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id' => 1,
                'kategori_id' => 1,
                'barang_kode' => 'MKN01',
                'barang_nama' => 'Roti Tawar',
                'harga_beli' => 10000,
                'harga_jual' => 14000,
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 1,
                'barang_kode' => 'MKN02',
                'barang_nama' => 'Roti Sisir',
                'harga_beli' => 12000,
                'harga_jual' => 16000,
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 2,
                'barang_kode' => 'MNM01',
                'barang_nama' => 'Air Putih',
                'harga_beli' => 2000,
                'harga_jual' => 5000,
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 2,
                'barang_kode' => 'MNM02',
                'barang_nama' => 'Air Soda',
                'harga_beli' => 4000,
                'harga_jual' => 7000,
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 3,
                'barang_kode' => 'RKK01',
                'barang_nama' => 'Smith Mentol',
                'harga_beli' => 10000,
                'harga_jual' => 12000,
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 3,
                'barang_kode' => 'RKK02',
                'barang_nama' => 'Djarum Souper',
                'harga_beli' => 15000,
                'harga_jual' => 18000,
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 4,
                'barang_kode' => 'KBS01',
                'barang_nama' => 'Rinso Bubuk',
                'harga_beli' => 3000,
                'harga_jual' => 6000,
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 4,
                'barang_kode' => 'KBS02',
                'barang_nama' => 'Rinso Cair',
                'harga_beli' => 4000,
                'harga_jual' => 8000,
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 5,
                'barang_kode' => 'KSH01',
                'barang_nama' => 'Obat Tetes Mata',
                'harga_beli' => 13000,
                'harga_jual' => 18000,
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 5,
                'barang_kode' => 'KSH02',
                'barang_nama' => 'Vitacimin C',
                'harga_beli' => 1000,
                'harga_jual' => 3000,
            ],
        ];
        DB::table('m_barang')->insert($data);
    }
}
