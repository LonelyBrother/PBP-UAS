<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    Cdashboard,
    Cmotor,
    Cpelunasan,
    Ctopsis,
    Clogin,
    Cpembeli
};

/*
|--------------------------------------------------------------------------
| ROUTE LOGIN (BELUM LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [Clogin::class, 'index'])->name('login');
    Route::post('/login', [Clogin::class, 'login_proses'])->name('login_proses');
});

/*
|--------------------------------------------------------------------------
| ROUTE SETELAH LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ROOT REDIRECT
    |--------------------------------------------------------------------------
    */
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [Cdashboard::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | MOTOR
    |--------------------------------------------------------------------------
    */
    Route::prefix('motor')->name('motor.')->group(function () {

        // ===============================
        // LIST & CRUD
        // ===============================
        Route::get('/', [Cmotor::class, 'index'])->name('index');
        Route::get('/create', [Cmotor::class, 'create'])->name('create');
        Route::post('/', [Cmotor::class, 'store'])->name('store');

        Route::get('/{id}', [Cmotor::class, 'show'])
            ->whereNumber('id')
            ->name('show');

        Route::get('/{id}/edit', [Cmotor::class, 'edit'])
            ->whereNumber('id')
            ->name('edit');

        Route::put('/{id}', [Cmotor::class, 'update'])
            ->whereNumber('id')
            ->name('update');

        Route::delete('/{id}', [Cmotor::class, 'destroy'])
            ->whereNumber('id')
            ->name('destroy');

        // ===============================
        // DESTROY ALL (KHUSUS)
        // ===============================
        Route::delete('/destroy-all', [Cmotor::class, 'destroyAll'])
            ->name('destroyAll');

        // ===============================
        // IMPORT / EXPORT
        // ===============================
        Route::post('/import', [Cmotor::class, 'import'])->name('import');

        Route::get('/export/excel', [Cmotor::class, 'exportExcel'])
            ->name('export.excel');

        Route::get('/export/pdf', [Cmotor::class, 'exportPdf'])
            ->name('export.pdf');

        // ===============================
        // CETAK
        // ===============================
        Route::get('/cetak', [Cmotor::class, 'cetak'])
            ->name('cetak');
    });


    /*
    |--------------------------------------------------------------------------
    | PELUNASAN
    |--------------------------------------------------------------------------
    */
    Route::delete('/pelunasan/destroy-all', [Cpelunasan::class, 'destroyAll'])->name('pelunasan.destroyAll');
    Route::resource('pelunasan', Cpelunasan::class);

    /*
    |--------------------------------------------------------------------------
    | PEMBELI
    |--------------------------------------------------------------------------
    */
    Route::delete('/pembeli/destroy-all', [Cpembeli::class, 'destroyAll'])->name('pembeli.destroyAll');
    Route::resource('pembeli', Cpembeli::class);
    Route::get('/kabupaten/{provinsi_id}', [Cpembeli::class, 'getKabupaten']);
    Route::get('/kecamatan/{kabupaten_id}', [Cpembeli::class, 'getKecamatan']);

    /*
    |--------------------------------------------------------------------------
    | TOPSIS
    |--------------------------------------------------------------------------
    */
    Route::get('/topsis', [Ctopsis::class, 'topsis'])->name('motor.topsis');
    Route::get('/topsis/form', [Ctopsis::class, 'formBobot'])->name('topsis.formBobot');
    Route::post('/topsis/hitung', [Ctopsis::class, 'hitungTopsis'])->name('topsis.hitung');
    Route::get('/topsis/export-pdf', [Ctopsis::class, 'exportPdf'])->name('topsis.exportPdf');
    Route::get('/topsis/chart', [Ctopsis::class, 'chart'])->name('topsis.chart');

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [Clogin::class, 'logout'])
        ->name('logout');
});
