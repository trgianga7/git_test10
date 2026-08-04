<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController\GHNController;
use App\Http\Controllers\ApiController\DiaChiApiController;
use App\Http\Controllers\ApiController\NguoiDungApiController;
use App\Http\Controllers\ApiController\ChucVuApiController;
use App\Http\Controllers\ApiController\KhachHangApiController;
use App\Http\Controllers\ApiController\DanhMucApiController;
use App\Http\Controllers\ApiController\SanPhamApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//API giao hang
Route::prefix('ghn')->group(function () {
    Route::get('/provinces', [GHNController::class, 'provinces']);
    Route::get('/districts/{provinceId}', [GHNController::class, 'districts']);
    Route::get('/wards/{districtId}', [GHNController::class, 'wards']);
});

//API (tạm tắt) 
/*
Route::get('/dia-chi/huyen/{province_id}', [DiaChiApiController::class, 'getHuyen']);

Route::get('/dia-chi/phuong/{district_id}', [DiaChiApiController::class, 'getPhuong']);

Route::prefix('nguoi-dung')->group(function () {
    Route::get('/', [NguoiDungApiController::class, 'index']);
    Route::get('/{id}', [NguoiDungApiController::class, 'show']);
    Route::post('/', [NguoiDungApiController::class, 'store']);
    Route::put('/{id}', [NguoiDungApiController::class, 'update']);
    Route::delete('/{id}', [NguoiDungApiController::class, 'destroy']);
});

Route::prefix('chuc-vu')->group(function () {
    Route::get('/', [ChucVuApiController::class, 'index']);
    Route::get('/all', [ChucVuApiController::class, 'getAll']);
    Route::get('/{id}', [ChucVuApiController::class, 'show']);
    Route::post('/', [ChucVuApiController::class, 'store']);
    Route::put('/{id}', [ChucVuApiController::class, 'update']);
    Route::delete('/{id}', [ChucVuApiController::class, 'destroy']);
});

Route::prefix('dia-chi')->group(function () {
    Route::get('/tinh', [DiaChiApiController::class, 'getTinh']);
    Route::get('/huyen/{province_id}', [DiaChiApiController::class, 'getHuyen']);
    Route::get('/phuong/{district_id}', [DiaChiApiController::class, 'getPhuong']);
    
    Route::get('/', [DiaChiApiController::class, 'index']);
    Route::get('/{id}', [DiaChiApiController::class, 'show']);
    Route::post('/', [DiaChiApiController::class, 'store']);
    Route::put('/{id}', [DiaChiApiController::class, 'update']);
    Route::delete('/{id}', [DiaChiApiController::class, 'destroy']);
});

Route::prefix('khach-hang')->group(function () {
    Route::get('/', [KhachHangApiController::class, 'index']);
    Route::get('/all', [KhachHangApiController::class, 'getAll']);
    Route::get('/{id}', [KhachHangApiController::class, 'show']);
    Route::post('/', [KhachHangApiController::class, 'store']);
    Route::put('/{id}', [KhachHangApiController::class, 'update']);
    Route::delete('/{id}', [KhachHangApiController::class, 'destroy']);
});

Route::prefix('danh-muc')->group(function () {
    Route::get('/', [DanhMucApiController::class, 'index']);
    Route::get('/all', [DanhMucApiController::class, 'getAll']);
    Route::get('/{id}', [DanhMucApiController::class, 'show']);
    Route::post('/', [DanhMucApiController::class, 'store']);
    Route::put('/{id}', [DanhMucApiController::class, 'update']);
    Route::delete('/{id}', [DanhMucApiController::class, 'destroy']);
});

Route::prefix('san-pham')->group(function () {
    Route::get('/', [SanPhamApiController::class, 'index']);
    Route::get('/all', [SanPhamApiController::class, 'getAll']);
    Route::get('/{id}', [SanPhamApiController::class, 'show']);
    Route::post('/', [SanPhamApiController::class, 'store']);
    Route::put('/{id}', [SanPhamApiController::class, 'update']);
    Route::delete('/{id}', [SanPhamApiController::class, 'destroy']);
});
*/