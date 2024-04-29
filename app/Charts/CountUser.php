<?php

namespace App\Charts;

use App\Models\UserModel;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class CountUser
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        $aktif = UserModel::where('activate', '=', 1)->count();
        $non_aktif = UserModel::where('activate', '=', 0)->count();

        $label = [
            'User Aktif',
            'User Belum Aktif',
        ];

        return $this->chart->donutChart()
            ->setTitle('Data User Aktif')
            ->setSubtitle(date('Y'))
            ->addData([$aktif, $non_aktif])
            ->setLabels($label);
    }
}
