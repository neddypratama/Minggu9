<?php

namespace App\Charts;

use App\Models\BarangModel;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class ProfitBarang
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $barang = BarangModel::all();
        $i = 0;
        foreach ($barang as $b) {
            $profit[$i] = $b->harga_jual - $b->harga_beli;

            $label[$i] = $b->barang_nama;
            $i++;
        }

        return $this->chart->lineChart()
            ->setTitle('Profit Barang dari Harga Jual dan Harga Beli')
            ->setSubtitle(date('Y'))
            ->addData('Profit', $profit)
            ->setXAxis($label);
    }
}
