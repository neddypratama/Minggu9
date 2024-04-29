<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'penjualan_id' => 1,
                'user_id' => 3,
                'pembeli' => 'Messi',
                'penjualan_kode' => 'PJL001',
                'penjualan_tanggal' => '2024-02-29 12-02-12.000000',
            ],
            [
                'penjualan_id' => 2,
                'user_id' => 3,
                'pembeli' => 'Ronaldo',
                'penjualan_kode' => 'PJL002',
                'penjualan_tanggal' => '2024-02-29 12-12-12.000000',
            ],
            [
                'penjualan_id' => 3,
                'user_id' => 3,
                'pembeli' => 'Ronaldino',
                'penjualan_kode' => 'PJL003',
                'penjualan_tanggal' => '2024-02-29 12-22-12.000000',
            ],
            [
                'penjualan_id' => 4,
                'user_id' => 3,
                'pembeli' => 'Pogba',
                'penjualan_kode' => 'PJL004',
                'penjualan_tanggal' => '2024-02-29 12-32-12.000000',
            ],
            [
                'penjualan_id' => 5,
                'user_id' => 3,
                'pembeli' => 'Modric',
                'penjualan_kode' => 'PJL005',
                'penjualan_tanggal' => '2024-02-29 12-42-12.000000',
            ],
            [
                'penjualan_id' => 6,
                'user_id' => 3,
                'pembeli' => 'Neymar',
                'penjualan_kode' => 'PJL006',
                'penjualan_tanggal' => '2024-02-29 12-52-12.000000',
            ],
            [
                'penjualan_id' => 7,
                'user_id' => 3,
                'pembeli' => 'Mbape',
                'penjualan_kode' => 'PJL007',
                'penjualan_tanggal' => '2024-02-29 13-02-12.000000',
            ],
            [
                'penjualan_id' => 8,
                'user_id' => 3,
                'pembeli' => 'Lewandowski',
                'penjualan_kode' => 'PJL008',
                'penjualan_tanggal' => '2024-02-29 13-12-12.000000',
            ],
            [
                'penjualan_id' => 9,
                'user_id' => 3,
                'pembeli' => 'Harry',
                'penjualan_kode' => 'PJL009',
                'penjualan_tanggal' => '2024-02-29 13-22-12.000000',
            ],
            [
                'penjualan_id' => 10,
                'user_id' => 3,
                'pembeli' => 'Suarez',
                'penjualan_kode' => 'PJL010',
                'penjualan_tanggal' => '2024-02-29 13-32-12.000000',
            ],
        ];
        DB::table('t_penjualan')->insert($data);
    }
}
