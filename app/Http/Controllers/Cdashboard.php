<?php

namespace App\Http\Controllers;

use App\Services\MmotorService;

class Cdashboard extends Controller
{
    public function index()
    {
        // Hitung per kategori via service
        $maticCount   = MmotorService::query('matic')->count();
        $sportCount   = MmotorService::query('sport')->count();
        $listrikCount = MmotorService::query('listrik')->count();

        // Total semua motor
        $motorCount = $maticCount + $sportCount + $listrikCount;

        return view('dashboard', compact('motorCount', 'maticCount', 'sportCount', 'listrikCount'));
    }
}
