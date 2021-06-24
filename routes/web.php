<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Barang\BarangController;
use App\Http\Controllers\BarangKeluar\BarangKeluarController;
use App\Http\Controllers\BarangMasuk\BarangMasukController;
use App\Http\Controllers\Operator\KategoriBarangController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Satuan\SatuanController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use RealRashid\SweetAlert\Facades\Alert;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::group(['middleware' => ['auth', 'role:admin']], function(){
  Route::group(['prefix' => 'user'], function(){
    Route::get('data',[UserController::class, 'getUser'])->name('data.user');
    Route::get('', [UserController::class, 'index'])->name('user.index');
    Route::post('', [UserController::class, 'store'])->name('user.store');
    Route::delete('{id}', [UserController::class, 'destroy'])->name('user.delete');
    Route::patch('{id}', [UserController::class, 'restore'])->name('user.restore');

  });
  Route::get('backup', function(){
    Artisan::call('database:backup');
    Alert::success('Sukses', 'Database berhasil dibackup. Silahkan periksa folder storage Anda');
    $path = storage_path('app/Laravel/*');
     $latest_ctime = 0;
     $latest_filename = '';
     $files = glob($path);
     foreach($files as $file)
     {
             if (is_file($file) && filectime($file) > $latest_ctime)
             {
                     $latest_ctime = filectime($file);
                     $latest_filename = $file;
             }
     }
    return response()->download($latest_filename);
    // return redirect()->back();
  })->name('backup');
});

Route::group(['middleware' => ['auth', 'role:operator']], function(){
    Route::group(['prefix' => 'kategori'], function(){
      Route::get('data',[KategoriBarangController::class, 'getKategori'])->name('data.kategori');
      Route::get('', [KategoriBarangController::class, 'index'])->name('kategori.index');
      Route::get('{id}', [KategoriBarangController::class, 'show'])->name('kategori.show');
      Route::post('', [KategoriBarangController::class, 'store'])->name('kategori.store');
      Route::post('{id}', [KategoriBarangController::class, 'update'])->name('kategori.update');
      Route::delete('{id}', [KategoriBarangController::class, 'destroy'])->name('kategori.delete');
    });

    Route::group(['prefix' => 'satuan'], function() {
      Route::get('data',[SatuanController::class, 'getSatuan'])->name('data.satuan');
      Route::get('', [SatuanController::class,'index'])->name('satuan.index');
      Route::get('{id}', [SatuanController::class,'show'])->name('satuan.show');
      Route::post('', [SatuanController::class,'store'])->name('satuan.store');
      Route::post('{id}', [SatuanController::class,'update'])->name('satuan.update');
      Route::delete('{id}', [SatuanController::class,'destroy'])->name('satuan.delete');
    });

    Route::group(['prefix' => 'barang'], function() {
      Route::get('data',[BarangController::class, 'getBarang'])->name('data.barang');
      Route::get('', [BarangController::class,'index'])->name('barang.index');
      Route::get('{id}', [BarangController::class,'show'])->name('barang.show');
      Route::post('', [BarangController::class,'store'])->name('barang.store');
      Route::post('{id}', [BarangController::class,'update'])->name('barang.update');
      Route::post('gambar/{id}', [BarangController::class,'updateImage'])->name('barang.update-image');
      Route::delete('{id}', [BarangController::class,'destroy'])->name('barang.delete');
    });

    Route::group(['prefix' => 'barang-masuk'], function() {
      Route::get('data',[BarangMasukController::class, 'getBarangMasuk'])->name('data.barang-masuk');
      Route::get('data/{id}',[BarangMasukController::class, 'getBarangMasukDetail'])->name('data.detail-barang-masuk');
      Route::get('', [BarangMasukController::class,'index'])->name('barang-masuk.index');
      Route::get('/tambah/barang-masuk', [BarangMasukController::class,'create'])->name('barang-masuk.create');
      Route::post('/update/barang-masuk/{id}',[BarangMasukController::class, 'updateBarang'])->name('barang-masuk.update-barang');
      Route::get('{id}', [BarangMasukController::class,'show'])->name('barang-masuk.show');
      Route::post('', [BarangMasukController::class,'store'])->name('barang-masuk.store');
      Route::post('{id}', [BarangMasukController::class,'update'])->name('barang-masuk.update');
      Route::post('/status/{id}', [BarangMasukController::class,'updateStatus'])->name('barang-masuk.update-status');
      Route::delete('{id}', [BarangMasukController::class,'destroy'])->name('barang-masuk.delete');
    });

    Route::group(['prefix' => 'barang-keluar'], function() {
      Route::get('data',[BarangKeluarController::class, 'getBarangKeluar'])->name('data.barang-keluar');
      Route::get('data/{id}',[BarangKeluarController::class, 'getBarangKeluarDetail'])->name('data.detail-barang-keluar');
      Route::get('', [BarangKeluarController::class,'index'])->name('barang-keluar.index');
      Route::get('/tambah/barang-keluar', [BarangKeluarController::class,'create'])->name('barang-keluar.create');
      Route::post('/update/barang-keluar/{id}',[BarangKeluarController::class, 'updateBarang'])->name('barang-keluar.update-barang');
      Route::get('{id}', [BarangKeluarController::class,'show'])->name('barang-keluar.show');
      Route::post('', [BarangKeluarController::class,'store'])->name('barang-keluar.store');
      Route::post('{id}', [BarangKeluarController::class,'update'])->name('barang-keluar.update');
      Route::post('/status/{id}', [BarangKeluarController::class,'updateStatus'])->name('barang-keluar.update-status');
      Route::delete('{id}', [BarangKeluarController::class,'destroy'])->name('barang-keluar.delete');
    });
    

    Route::group(['prefix' => 'laporan'], function(){
      Route::get('data-stock',[ReportController::class, 'reportStock'])->name('data-stock');
      Route::get('data-masuk',[ReportController::class, 'reportBarangMasuk'])->name('data-masuk');
      Route::get('data-keluar',[ReportController::class, 'reportBarangKeluar'])->name('data-keluar');
      Route::get('stock',[ReportController::class, 'stock'])->name('stock');
      Route::get('barang-masuk',[ReportController::class, 'barangMasuk'])->name('barang-masuk');
      Route::get('barang-keluar',[ReportController::class, 'barangKeluar'])->name('barang-keluar');
      
    });
});
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
