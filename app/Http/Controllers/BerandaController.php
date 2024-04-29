<?php

namespace App\Http\Controllers;

use App\Charts\CountUser;
use App\Charts\ProfitBarang;
use App\Models\BarangModel;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index(CountUser $countUser, ProfitBarang $profitBarang) {
        $breadcrump = (object) [
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome']
        ];

        $activeMenu = 'dashboard';

        return view('halamanDepan.beranda', ['breadcrumb' => $breadcrump, 'activeMenu' => $activeMenu, 'countUser' => $countUser->build(), 'profitBarang' => $profitBarang->build(),]);
    }
}
