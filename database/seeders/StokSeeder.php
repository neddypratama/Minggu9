<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'stok_id' => 1,
                'barang_id' => 1,
                'user_id' => 3,
                'stok_tanggal' => '2024-02-28 12:12:12.000000',
                'stok_jumlah' => 20,
            ],
            [
                'stok_id' => 2,
                'barang_id' => 2,
                'user_id' => 3,
                'stok_tanggal' => '2024-02-28 12:22:12.000000',
                'stok_jumlah' => 20,
            ],
            [
                'stok_id' => 3,
                'barang_id' => 3,
                'user_id' => 3,
                'stok_tanggal' => '2024-02-28 12:32:12.000000',
                'stok_jumlah' => 20,
            ],
            [
                'stok_id' => 4,
                'barang_id' => 4,
                'user_id' => 3,
                'stok_tanggal' => '2024-02-28 12:42:12.000000',
                'stok_jumlah' => 20,
            ],
            [
                'stok_id' => 5,
                'barang_id' => 5,
                'user_id' => 3,
                'stok_tanggal' => '2024-02-28 12:52:12.000000',
                'stok_jumlah' => 20,
            ],
            [
                'stok_id' => 6,
                'barang_id' => 6,
                'user_id' => 3,
                'stok_tanggal' => '2024-02-28 13:02:12.000000',
                'stok_jumlah' => 20,
            ],
            [
                'stok_id' => 7,
                'barang_id' => 7,
                'user_id' => 3,
                'stok_tanggal' => '2024-02-28 13:12:12.000000',
                'stok_jumlah' => 20,
            ],
            [
                'stok_id' => 8,
                'barang_id' => 8,
                'user_id' => 3,
                'stok_tanggal' => '2024-02-28 13:22:12.000000',
                'stok_jumlah' => 20,
            ],
            [
                'stok_id' => 9,
                'barang_id' => 9,
                'user_id' => 3,
                'stok_tanggal' => '2024-02-28 13:32:12.000000',
                'stok_jumlah' => 20,
            ],
            [
                'stok_id' => 10,
                'barang_id' => 10,
                'user_id' => 3,
                'stok_tanggal' => '2024-02-28 13:42:12.000000',
                'stok_jumlah' => 20,
            ],
        ];
        DB::table('t_stok')->insert($data);
    }
}
