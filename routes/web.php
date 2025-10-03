<?php

use App\Http\Controllers\DependentDropdownController;
use App\Http\Controllers\MasterBankController;
use App\Http\Controllers\MasterGradeController;
use App\Http\Controllers\MasterJenisController;
use App\Http\Controllers\MasterKantorController;
use App\Http\Controllers\MasterProjekController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    Route::prefix('master/bank')->group(function () {

        Route::get('/', [MasterBankController::class, 'index'])->name('master-bank.index');
        Route::get('/create', [MasterBankController::class, 'create'])->name('master-bank.create');
        Route::post('/store', [MasterBankController::class, 'store'])->name('master-bank.store');
        Route::get('/edit/{id}', [MasterBankController::class, 'edit'])->name('master-bank.edit');
        Route::put('/update/{id}', [MasterBankController::class, 'update'])->name('master-bank.update');
        Route::get('/show/{id}', [MasterBankController::class, 'show'])->name('master-bank.show');
        Route::delete('/delete/{id}', [MasterBankController::class, 'destroy'])->name('master-bank.destroy');
    });
    Route::prefix('master/kantor')->group(function () {

        Route::get('/', [MasterKantorController::class, 'index'])->name('master-kantor.index');
        Route::get('/create', [MasterKantorController::class, 'create'])->name('master-kantor.create');
        Route::post('/store', [MasterKantorController::class, 'store'])->name('master-kantor.store');
        Route::get('/edit/{id}', [MasterKantorController::class, 'edit'])->name('master-kantor.edit');
        Route::put('/update/{id}', [MasterKantorController::class, 'update'])->name('master-kantor.update');
        Route::get('/show/{id}', [MasterKantorController::class, 'show'])->name('master-kantor.show');
        Route::delete('/delete/{id}', [MasterKantorController::class, 'destroy'])->name('master-kantor.destroy');
    });
    Route::prefix('master/proyek')->group(function () {
        // route untuk master proyek
        Route::get('/', [MasterProjekController::class, 'index'])->name('master-proyek.index');
        Route::get('/create', [MasterProjekController::class, 'create'])->name('master-proyek.create');
        Route::post('/store', [MasterProjekController::class, 'store'])->name('master-proyek.store');
        Route::get('/edit/{id}', [MasterProjekController::class, 'edit'])->name('master-proyek.edit');
        Route::put('/update/{id}', [MasterProjekController::class, 'update'])->name('master-proyek.update');
        Route::get('/show/{id}', [MasterProjekController::class, 'show'])->name('master-proyek.show');
        Route::delete('/delete/{id}', [MasterProjekController::class, 'destroy'])->name('master-proyek.destroy');
    });
    Route::prefix('master/grade')->group(function () {

        Route::get('/', [MasterGradeController::class, 'index'])->name('master-grade.index');
        Route::get('/create', [MasterGradeController::class, 'create'])->name('master-grade.create');
        Route::post('/store', [MasterGradeController::class, 'store'])->name('master-grade.store');
        Route::get('/edit/{id}', [MasterGradeController::class, 'edit'])->name('master-grade.edit');
        Route::put('/update/{id}', [MasterGradeController::class, 'update'])->name('master-grade.update');
        Route::get('/show/{id}', [MasterGradeController::class, 'show'])->name('master-grade.show');
        Route::delete('/delete/{id}', [MasterGradeController::class, 'destroy'])->name('master-grade.destroy');
    });
    Route::prefix('master/jenis-produk')->group(function () {
        Route::get('/', [MasterJenisController::class, 'index'])->name('master-jenis-produk.index');
        Route::get('/create', [MasterJenisController::class, 'create'])->name('master-jenis-produk.create');
        Route::post('/store', [MasterJenisController::class, 'store'])->name('master-jenis-produk.store');
        Route::get('/edit/{id}', [MasterJenisController::class, 'edit'])->name('master-jenis-produk.edit');
        Route::put('/update/{id}', [MasterJenisController::class, 'update'])->name('master-jenis-produk.update');
        Route::get('/show/{id}', [MasterJenisController::class, 'show'])->name('master-jenis-produk.show');
        Route::delete('/delete/{id}', [MasterJenisController::class, 'destroy'])->name('master-jenis-produk.destroy');
    });
    Route::prefix('produk')->group(function () {
        Route::get('/', [ProdukController::class, 'index'])->name('produk.index');
        Route::get('/create', [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/store', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/edit/{id}', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/update/{id}', [ProdukController::class, 'update'])->name('produk.update');
        Route::get('/show/{id}', [ProdukController::class, 'show'])->name('produk.show');
        Route::delete('/delete/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    });
    Route::prefix('pengajuan/penawaran-harga')->group(function () {
        Route::get('/', [ProdukController::class, 'index'])->name('penawaran.index');
        Route::get('/create', [ProdukController::class, 'create'])->name('penawaran.create');
        Route::post('/store', [ProdukController::class, 'store'])->name('penawaran.store');
        Route::get('/edit/{id}', [ProdukController::class, 'edit'])->name('penawaran.edit');
        Route::put('/update/{id}', [ProdukController::class, 'update'])->name('penawaran.update');
        Route::get('/show/{id}', [ProdukController::class, 'show'])->name('penawaran.show');
        Route::delete('/delete/{id}', [ProdukController::class, 'destroy'])->name('penawaran.destroy');
    });
});
Route::get('provinces', [DependentDropdownController::class, 'provinces'])->name('provinces');
Route::get('cities', [DependentDropdownController::class, 'cities'])->name('cities');
Route::get('districts', [DependentDropdownController::class, 'districts'])->name('districts');
Route::get('villages', [DependentDropdownController::class, 'villages'])->name('villages');
