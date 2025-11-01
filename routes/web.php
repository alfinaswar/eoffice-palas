<?php

use App\Http\Controllers\BookingListController;
use App\Http\Controllers\DependentDropdownController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterAngsuranController;
use App\Http\Controllers\MasterBankController;
use App\Http\Controllers\MasterCustomer;
use App\Http\Controllers\MasterGradeController;
use App\Http\Controllers\MasterJenisController;
use App\Http\Controllers\MasterKantorController;
use App\Http\Controllers\MasterProjekController;
use App\Http\Controllers\PenawaranHargaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "web" middleware group. Make something great!
 * |
 */

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('provinces', [DependentDropdownController::class, 'provinces'])->name('provinces');
Route::get('cities', [DependentDropdownController::class, 'cities'])->name('cities');
Route::get('districts', [DependentDropdownController::class, 'districts'])->name('districts');
Route::get('villages', [DependentDropdownController::class, 'villages'])->name('villages');
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
    Route::prefix('master/customer')->group(function () {
        Route::get('/', [MasterCustomer::class, 'index'])->name('customer.index');
        Route::get('/create', [MasterCustomer::class, 'create'])->name('customer.create');
        Route::post('/store', [MasterCustomer::class, 'store'])->name('customer.store');
        Route::get('/edit/{id}', [MasterCustomer::class, 'edit'])->name('customer.edit');
        Route::put('/update/{id}', [MasterCustomer::class, 'update'])->name('customer.update');
        Route::get('/show/{id}', [MasterCustomer::class, 'show'])->name('customer.show');
        Route::delete('/delete/{id}', [MasterCustomer::class, 'destroy'])->name('customer.destroy');
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
    Route::prefix('master/angsuran')->group(function () {
        Route::get('/', [MasterAngsuranController::class, 'index'])->name('master-angsuran.index');
        Route::get('/create', [MasterAngsuranController::class, 'create'])->name('master-angsuran.create');
        Route::post('/store', [MasterAngsuranController::class, 'store'])->name('master-angsuran.store');
        Route::get('/edit/{id}', [MasterAngsuranController::class, 'edit'])->name('master-angsuran.edit');
        Route::put('/update/{id}', [MasterAngsuranController::class, 'update'])->name('master-angsuran.update');
        Route::delete('/delete/{id}', [MasterAngsuranController::class, 'destroy'])->name('master-angsuran.destroy');
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
        Route::get('/', [PenawaranHargaController::class, 'index'])->name('penawaran-harga.index');
        Route::get('/create', [PenawaranHargaController::class, 'create'])->name('penawaran-harga.create');
        Route::post('/store', [PenawaranHargaController::class, 'store'])->name('penawaran-harga.store');
        Route::get('/edit/{id}', [PenawaranHargaController::class, 'edit'])->name('penawaran-harga.edit');
        Route::get('/approval/{id}', [PenawaranHargaController::class, 'Approval'])->name('penawaran-harga.Approval');
        Route::get('/cetak-dokumen/{id}', [PenawaranHargaController::class, 'DownloadPengajuan'])->name('penawaran-harga.cetakPengajuan');
        Route::POST('/acc-pengajuan/{id}', [PenawaranHargaController::class, 'AccPengajuan'])->name('penawaran-harga.persetujuan');
        Route::put('/update/{id}', [PenawaranHargaController::class, 'update'])->name('penawaran-harga.update');
        Route::put('/update-approval/{id}', [PenawaranHargaController::class, 'UpdateApproval'])->name('penawaran-harga.update-approval');
        Route::get('/show/{id}', [PenawaranHargaController::class, 'show'])->name('penawaran-harga.show');
        Route::delete('/delete/{id}', [PenawaranHargaController::class, 'destroy'])->name('penawaran-harga.destroy');
    });
    Route::prefix('booking-list')->group(function () {
        Route::get('/', [BookingListController::class, 'index'])->name('booking-list.index');
        Route::get('/create/{id}', [BookingListController::class, 'create'])->name('booking-list.create');
        Route::post('/store', [BookingListController::class, 'store'])->name('booking-list.store');
        Route::get('/edit/{id}', [BookingListController::class, 'edit'])->name('booking-list.edit');
        Route::get('/cetak-bukti-pembayaran/{id}', [BookingListController::class, 'PrintKwitansi'])->name('booking-list.print');
        Route::put('/update/{id}', [BookingListController::class, 'update'])->name('booking-list.update');
        Route::get('/show/{id}', [BookingListController::class, 'show'])->name('booking-list.show');
        Route::delete('/delete/{id}', [BookingListController::class, 'destroy'])->name('booking-list.destroy');
    });
    Route::prefix('transaksi')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/create/{id}', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/store', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::post('/bayar-tagihan', [TransaksiController::class, 'PembayaranTagihan'])->name('transaksi.bayar');
        Route::get('/edit/{id}', [TransaksiController::class, 'edit'])->name('transaksi.edit');
        Route::get('/cetak-bukti-pembayaran/{id}', [TransaksiController::class, 'PrintKwitansi'])->name('transaksi.print');
        Route::put('/update/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
        Route::get('/pembayaran/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::get('/daftar-tagihan/{id}', [TransaksiController::class, 'Tagihan'])->name('transaksi.list-tagihan');
        Route::delete('/delete/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    });
});
