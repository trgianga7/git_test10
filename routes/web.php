<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController\ChucVuController;
use App\Http\Controllers\WebController\NguoiDungController;
use App\Http\Controllers\WebController\DanhMucController;
use App\Http\Controllers\WebController\SanPhamController;
use App\Http\Controllers\WebController\SanPhamChiTietController;
use App\Http\Controllers\WebController\DiaChiController;
use App\Http\Controllers\WebController\KhachHangController;
use App\Http\Controllers\WebController\HoaDonController;
use App\Http\Controllers\WebController\GiamGiaController;
use App\Http\Controllers\WebController\ThongKeController;
use App\Http\Controllers\WebController\HomeController;
use App\Http\Controllers\WebController\MuaHangOnline\GioHangController;
use App\Http\Controllers\WebController\MuaHangOnline\ThanhToanController;
use App\Http\Controllers\AuthController\DangNhapController;
use App\Http\Controllers\AuthController\DangKyController;
use App\Http\Controllers\AuthController\LichSuMuaHang\LichSuMuaHangController;
use App\Http\Middleware\QuyenHan;
use App\Http\Controllers\ImportControllers\NguoiDungImportController;
use App\Http\Controllers\ExportControllers\NguoiDungExportController;
use App\Http\Controllers\ImportControllers\ChucVuImportController;
use App\Http\Controllers\ExportControllers\ChucVuExportController;
use App\Http\Controllers\ExportControllers\ExportNhieuSheetController\QuyenHanChucVuExportController;
use App\Http\Controllers\ImportControllers\DiaChiImportController;
use App\Http\Controllers\ExportControllers\DiaChiExportController;
use App\Http\Controllers\ImportControllers\KhachHangImportController;
use App\Http\Controllers\ExportControllers\KhachHangExportController;
use App\Http\Controllers\ImportControllers\DanhMucImportController;
use App\Http\Controllers\ExportControllers\DanhMucExportController;
use App\Http\Controllers\ImportControllers\SanPhamImportController;
use App\Http\Controllers\ExportControllers\SanPhamExportController;
use App\Http\Controllers\ImportControllers\SanPhamChiTietImportController;
use App\Http\Controllers\ExportControllers\SanPhamChiTietExportController;
use App\Http\Controllers\ImportControllers\HoaDonImportController;
use App\Http\Controllers\ExportControllers\HoaDonExportController;
use App\Http\Controllers\ImportControllers\HoaDonChiTietImportController;
use App\Http\Controllers\ExportControllers\HoaDonChiTietExportController;
use App\Http\Controllers\ImportControllers\GiamGiaImportController;
use App\Http\Controllers\ExportControllers\GiamGiaExportController;
use App\Http\Controllers\AuthController\QuanLyThongTinController;
use App\Http\Controllers\AuthController\QuanLyDiaChiController;
use App\Http\Controllers\AuthController\QuanLySoDu\QuanLySoDuController;
use App\Http\Controllers\WebController\QuanLyDanhGiaController;
use App\Events\TestEvent; 
use App\Http\Controllers\ApiController\DiaChiApiController;
use App\Http\Controllers\ApiController\NguoiDungApiController;
use App\Http\Controllers\ApiController\ChucVuApiController;
use App\Http\Controllers\ApiController\KhachHangApiController;
use App\Http\Controllers\ApiController\DanhMucApiController;
use App\Http\Controllers\ApiController\SanPhamApiController;
use App\Http\Controllers\ApiController\SanPhamChiTietApiController;
use App\Http\Controllers\ApiController\HoaDonApiController;
use App\Http\Controllers\ApiController\GiamGiaApiController;
use App\Http\Controllers\ApiController\ThongKeApiController;
use App\Http\Controllers\ApiController\HomeApiController;
use App\Http\Controllers\ApiController\GioHangApiController;
use App\Http\Controllers\ApiController\ThanhToanApiController;

Route::get('/quan-ly', function () {
    return view('DanhSach'); 
});

//Laravel socket
Route::get('/test-broadcast', function () {
    broadcast(new TestEvent('WebSocket OK!!!'));
    return 'sent';
});

//Quyền truy cập và login
Route::get('/dang-ky', [DangKyController::class, 'showForm'])->name('dang-ky.form');
Route::post('/dang-ky', [DangKyController::class, 'register'])->name('dang-ky.post');

Route::get('/login', [DangNhapController::class, 'showLogin'])->name('dang-nhap');
Route::post('/login', [DangNhapController::class, 'login'])->name('login.post');

Route::post('/logout', [DangNhapController::class, 'logout'])->name('logout');


//Bảng thông tin cá nhân
Route::middleware('auth:admin')->group(function () {
    Route::get('/quan-ly-thong-tin/admin', [QuanLyThongTinController::class, 'index'])
        ->name('quan_ly_thong_tin');
});

Route::middleware(['auth:customer', 'kiem_tra_trang_thai'])->group(function () {
    Route::get('/quan-ly-thong-tin/customer', [QuanLyThongTinController::class, 'indexCustomer'])
        ->name('quan_ly_thong_tin_customer');
    
    Route::post('/customer/dia-chi/store', 
        [QuanLyDiaChiController::class, 'store']
    )->name('customer.dia_chi_store');

    Route::get('/customer/dia-chi/{id}/edit', 
        [QuanLyDiaChiController::class, 'edit']
    )->name('customer.dia_chi_edit');

    Route::post('/customer/dia-chi/{id}/update', 
        [QuanLyDiaChiController::class, 'update']
    )->name('customer.dia_chi_update');

    Route::get('/customer/dia-chi/{id}/delete', 
        [QuanLyDiaChiController::class, 'delete']
    )->name('customer.dia_chi_delete');

    Route::put('/customer/dia-chi/update/{id}',
        [QuanLyDiaChiController::class, 'update']
    )->name('customer.dia_chi_update');
    });
    
// Cập nhật thông tin cá nhân
Route::middleware(['auth:admin'])->post('/quan-ly-thong-tin/update', [QuanLyThongTinController::class, 'updateAdmin'])
    ->name('quan_ly_thong_tin_update_admin');

Route::middleware(['auth:customer'])->post('/quan-ly-thong-tin/update-customer', [QuanLyThongTinController::class, 'updateCustomer'])
    ->name('quan_ly_thong_tin_update_customer');

Route::middleware(['auth:admin,customer'])->get('/danh-sach-ca-nhan', [QuanLyThongTinController::class,'danhSachCaNhan']
    )->name('danh_sach_ca_nhan'); 

// Thông tin đơn hàng cá nhân
Route::resource('lich-su-mua-hang', LichSuMuaHangController::class);

Route::get('/lich-su-don-hang/xem-chi-tiet/{id}', [LichSuMuaHangController::class, 'xemHoaDon'])
    ->name('lich-su-don-hang.xemChiTiet');

// Thông tin số dư cá nhân
Route::get('/thong-tin-so-du', [QuanLySoDuController::class, 'xemSoDu'])
    ->name('quan-ly-so-du.xemSoDu');

// Đánh giá sản phẩm (khách)
Route::post('/danh-gia', [HomeController::class, 'themDanhGia'])->name('danh-gia.themDanhGia');


//Trang chủ mua hàng
Route::get('/', [HomeController::class, 'view'])->name('trang-chu.Home');


//Menu - Sản phẩm
Route::get('/san-pham', [HomeController::class, 'menuSanPham'])->name('trang-chu.SanPham');

Route::get('/trang-chu/san-pham/{id}', [HomeController::class, 'chiTiet'])
    ->name('san-pham.chiTiet');

//Giỏ hàng    
Route::get('/gio-hang', [GioHangController::class, 'index'])->name('gio-hang.index');

//Chi Tiết SP
Route::get('/san-pham/chi-tiet/{ma_sp}', [HomeController::class, 'chiTietSanPham'])->name('trang-chu.ChiTiet');

//Thanh toán
Route::get('/thanh-toan', [ThanhToanController::class, 'index'])->name('thanh-toan.index');

//Thanh toán QR
Route::get('/thanh-toan/qr/{id}', [ThanhToanController::class, 'taoQR'])
    ->name('thanh-toan.qr');


//Giảm giá khi thanh toán
Route::post('/kiem-tra-giam-gia', [ThanhToanController::class, 'kiemTraMa'])
    ->name('thanh-toan.kiem-tra-ma');

//Phân quyền mới
Route::middleware(['auth:admin', 'kiem_tra_quyen', 'kiem_tra_trang_thai'])->group(function () {

    Route::prefix('/quan-ly/nguoi-dung')->group(function () {
        Route::get('/', [NguoiDungController::class, 'view']);
        Route::get('/create', [NguoiDungController::class, 'create']);   
        Route::get('/edit/{uuid}', [NguoiDungController::class, 'edit']);    
    });

    Route::prefix('/quan-ly/chuc-vu')->group(function () {
        Route::get('/', [ChucVuController::class, 'view']);
        Route::get('/create', [ChucVuController::class, 'create']);
        Route::get('/edit/{id}', [ChucVuController::class, 'edit']);
    });

    Route::prefix('/quan-ly/dia-chi')->group(function () {
        Route::get('/', [DiaChiController::class, 'view']);
        Route::get('/create', [DiaChiController::class, 'create']);
        Route::get('/edit/{id}', [DiaChiController::class, 'edit']);
    });

    Route::prefix('/quan-ly/khach-hang')->group(function () {
        Route::get('/', [KhachHangController::class, 'view']);
        Route::get('/create', [KhachHangController::class, 'create']);   
        Route::get('/edit/{uuid}', [KhachHangController::class, 'edit']);    
    });

    Route::prefix('/quan-ly/danh-muc')->group(function () {
        Route::get('/', [DanhMucController::class, 'view']);
        Route::get('/create', [DanhMucController::class, 'create']);   
        Route::get('/edit/{id}', [DanhMucController::class, 'edit']);    
    });

    Route::prefix('/quan-ly/san-pham')->group(function () {
        Route::get('/', [SanPhamController::class, 'view']);
        Route::get('/create', [SanPhamController::class, 'create']);   
        Route::get('/edit/{key_sp}', [SanPhamController::class, 'edit']);    
    });

    Route::prefix('/quan-ly/san-pham-chi-tiet')->group(function () {
        Route::get('/', [SanPhamChiTietController::class, 'view']);
        Route::get('/create', [SanPhamChiTietController::class, 'create']);   
        Route::get('/edit/{ma_sp}', [SanPhamChiTietController::class, 'edit']);    
    });

    Route::prefix('/quan-ly/hoa-don')->group(function () {
        Route::get('/', [HoaDonController::class, 'view']);
        Route::get('/create', [HoaDonController::class, 'create']);   
        Route::get('/edit/{ma_hd}', [HoaDonController::class, 'edit']);  
        Route::get('/view-info/{ma_hd}', [HoaDonController::class, 'viewInfo']);  
    });

    Route::prefix('/quan-ly/giam-gia')->group(function () {
        Route::get('/', [GiamGiaController::class, 'view']);
        Route::get('/create', [GiamGiaController::class, 'create']);   
        Route::get('/edit/{id}', [GiamGiaController::class, 'edit']);
        
        Route::get('/create-sp-giam-gia', [GiamGiaController::class, 'create_sp_giam_gia']);
    });

    Route::get('/quan-ly/thong-ke', [ThongKeController::class, 'thong_ke']);

    Route::put('/nguoi-dung/{id}/mo-khoa', [NguoiDungController::class, 'mokhoa'])
    ->name('nguoi-dung.mokhoa');
    Route::put('/khach-hang/{id}/mo-khoa', [KhachHangController::class, 'mokhoa'])
    ->name('khach-hang.mokhoa');
    Route::get('/quan-ly/danh-gia', [QuanLyDanhGiaController::class, 'index'])
        ->name('quan-ly-danh-gia.index');
    Route::put('/quan-ly/danh-gia/{id}', [QuanLyDanhGiaController::class, 'update'])
        ->name('quan-ly-danh-gia.update');
    Route::delete('/quan-ly/danh-gia/{id}', [QuanLyDanhGiaController::class, 'destroy'])
        ->name('quan-ly-danh-gia.destroy');

    //API
    Route::prefix('api')->group(function () {

        Route::prefix('nguoi-dung')->group(function () {
            Route::get('/', [NguoiDungApiController::class, 'index']);
            Route::get('/{uuid}', [NguoiDungApiController::class, 'show']);
            Route::post('/', [NguoiDungApiController::class, 'store']);
            Route::put('/{uuid}', [NguoiDungApiController::class, 'update']);
            Route::delete('/{uuid}', [NguoiDungApiController::class, 'destroy']);
        });
    
        Route::prefix('chuc-vu')->group(function () {
            Route::get('/', [ChucVuApiController::class, 'index']);
            Route::get('/all', [ChucVuApiController::class, 'getAll']);
            Route::get('/{id}', [ChucVuApiController::class, 'show']);
            Route::post('/', [ChucVuApiController::class, 'store']);
            Route::put('/{id}', [ChucVuApiController::class, 'update']);
            Route::delete('/{id}', [ChucVuApiController::class, 'destroy']);
        });

        Route::get('/chuc-nang/all', [ChucVuApiController::class, 'getAllChucNang']
        );
    
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
            Route::get('/{uuid}', [KhachHangApiController::class, 'show']);
            Route::post('/', [KhachHangApiController::class, 'store']);
            Route::put('/{uuid}', [KhachHangApiController::class, 'update']);
            Route::delete('/{uuid}', [KhachHangApiController::class, 'destroy']);
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
            Route::get('/{key_sp}', [SanPhamApiController::class, 'show']);
            Route::post('/', [SanPhamApiController::class, 'store']);
            Route::put('/{key_sp}', [SanPhamApiController::class, 'update']);
            Route::delete('/{key_sp}', [SanPhamApiController::class, 'destroy']);
        });

        Route::prefix('san-pham-chi-tiet')->group(function () {
            Route::get('/', [SanPhamChiTietApiController::class, 'index']);
            Route::get('/all', [SanPhamChiTietApiController::class, 'getAll']);
            Route::get('/{ma_sp}', [SanPhamChiTietApiController::class, 'show']);
            Route::post('/', [SanPhamChiTietApiController::class, 'store']);
            Route::put('/{ma_sp}', [SanPhamChiTietApiController::class, 'update']);
            Route::delete('/{ma_sp}', [SanPhamChiTietApiController::class, 'destroy']);
        });

        Route::prefix('hoa-don')->group(function () {
            Route::get('/', [HoaDonApiController::class, 'index']);
            Route::get('/all', [HoaDonApiController::class, 'getAll']);
            Route::get('/{ma_hd}', [HoaDonApiController::class, 'show']);
            Route::post('/', [HoaDonApiController::class, 'store']);
            Route::put('/{ma_hd}', [HoaDonApiController::class, 'update']);
            Route::delete('/{ma_hd}', [HoaDonApiController::class, 'destroy']);
        });
        
        Route::prefix('giam-gia')->group(function () {
            Route::get('/', [GiamGiaApiController::class, 'index']);
            Route::get('/all', [GiamGiaApiController::class, 'getAll']);
            Route::get('/{id}', [GiamGiaApiController::class, 'show']);
            Route::post('/', [GiamGiaApiController::class, 'store']);
            Route::put('/{id}', [GiamGiaApiController::class, 'update']);
            Route::delete('/{id}', [GiamGiaApiController::class, 'destroy']);
        });

        Route::prefix('giam-gia-san-pham')->group(function () {
            Route::get('/', [GiamGiaApiController::class, 'sanPhamGiamGia']);
            Route::post('/', [GiamGiaApiController::class, 'themSanPhamGiamGia']);
            Route::put('/{id}', [GiamGiaApiController::class, 'huySanPhamGiamGia']);
        }); 


        Route::prefix('thong-ke')->group(function () {
            Route::get('/', [ThongKeApiController::class, 'index']);
        });

    });


    //Chức năng import + export excel
    /*Route::post('/nguoi-dung/import', [NguoiDungImportController::class, 'import'])
    ->name('nguoi_dung.import');

    Route::get('/nguoi_dung/export', [NguoiDungExportController::class, 'export'])
        ->name('nguoi_dung.export');    

    Route::post('/chuc-vu/import', [ChucVuImportController::class, 'import'])
        ->name('chuc_vu.import');

    Route::get('/chuc_vu/export', [ChucVuExportController::class, 'export'])
        ->name('chuc_vu.export');  

    Route::get('/chuc-vu-day-du/export', [QuyenHanChucVuExportController::class, 'exportChucVu'])
        ->name('chuc_vu_day_du.export');

    Route::post('/dia-chi/import', [DiaChiImportController::class, 'import'])
        ->name('dia-chi.import');

    Route::get('/dia_chi/export', [DiaChiExportController::class, 'export'])
        ->name('dia_chi.export');  

    Route::post('/khach-hang/import', [KhachHangImportController::class, 'import'])
        ->name('khach-hang.import');

    Route::get('/khach_hang/export', [KhachHangExportController::class, 'export'])
        ->name('khach_hang.export');  

    Route::post('/danh-muc/import', [DanhMucImportController::class, 'import'])
        ->name('danh_muc.import');

    Route::get('/danh_muc/export', [DanhMucExportController::class, 'export'])
        ->name('danh_muc.export');  

    Route::post('/san-pham/import', [SanPhamImportController::class, 'import'])
        ->name('san-pham.import');

    Route::get('/san_pham/export', [SanPhamExportController::class, 'export'])
        ->name('san_pham.export'); 

    Route::post('/san-pham-chi-tiet/import', [SanPhamChiTietImportController::class, 'import'])
        ->name('san-pham-chi-tiet.import');

    Route::get('/san_pham_chi_tiet/export', [SanPhamChiTietExportController::class, 'export'])
        ->name('san_pham_chi_tiet.export'); 

    Route::post('/hoa-don/import', [HoaDonImportController::class, 'import'])
        ->name('hoa-don.import');

    Route::get('/hoa_don/export', [HoaDonExportController::class, 'export'])
        ->name('hoa_don.export'); 

    Route::post('/hoa-don-chi-tiet/import', [HoaDonChiTietImportController::class, 'import'])
        ->name('hoa-don-chi-tiet.import');

    Route::get('/hoa_don_chi_tiet/export', [HoaDonChiTietExportController::class, 'export'])
        ->name('hoa_don_chi_tiet.export');

    Route::post('/giam-gia/import', [GiamGiaImportController::class, 'import'])
        ->name('giam-gia.import');

    Route::get('/giam_gia/export', [GiamGiaExportController::class, 'export'])
        ->name('giam_gia.export');*/

});

//API public - Trang chủ, sản phẩm
Route::get('/api/trang-chu/san-pham', [HomeApiController::class, 'danhSachSanPham']);
Route::get('/api/trang-chu/san-pham/danh-muc', [HomeApiController::class, 'danhSachDanhMuc']);

//API public - Giỏ hàng
Route::get('api/gio-hang/so-luong', [GioHangApiController::class, 'soLuong']);
Route::get('api/gio-hang',[GioHangApiController::class, 'index']);
Route::post('api/gio-hang/them', [GioHangApiController::class, 'them']);
Route::post('api/gio-hang/cap-nhat',[GioHangApiController::class, 'capNhat']);
Route::post('api/gio-hang/xoa', [GioHangApiController::class, 'xoa']);

//API public - Thanh toán
Route::get('api/thanh-toan',[ThanhToanApiController::class, 'index']);
Route::post('api/thanh-toan',[ThanhToanApiController::class, 'thanhToan']);

//API public - Thanh toán -> giảm giá
Route::post('api/thanh-toan/kiem-tra-ma',[ThanhToanApiController::class, 'kiemTraMa']);

//API public - Chi tiết sp
Route::get('api/san-pham-view/{ma_sp}',[HomeApiController::class, 'chiTietSanPham']);

//API public - Chi tiết sp mua ngay
Route::post('api/mua-ngay',[HomeApiController::class, 'muaNgay']);

//API public - Post đánh giá
Route::post('api/danh-gia',[HomeApiController::class, 'postDanhGia']);

