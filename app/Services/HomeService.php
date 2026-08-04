<?php

namespace App\Services;

use App\Models\SanPhamModel;
use App\Models\SanPhamChiTietModel;
use App\Models\DanhMucModel;
use App\Models\DanhGiaModel;
use App\Models\DinhKemDanhGiaModel;
use App\Models\HoaDonCtModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Support\Cache\HomeCache;
use App\Support\Cache\SanPhamCache;

class HomeService
{
    public function tatCaDanhMuc()
    {
        return Cache::remember(HomeCache::category(), now()->addHours(24),
            fn() => DanhMucModel::where('trang_thai',1)->get()
        );
    }

    public function TimSanPham($request)
    {
        return SanPhamChiTietModel::query()
            ->join('san_pham', 'san_pham_chi_tiet.id_san_pham', '=', 'san_pham.id')
            ->select(
                'san_pham_chi_tiet.*',
                'san_pham.ten_san_pham',
                'san_pham.id_danh_muc'
            )
            ->where('san_pham_chi_tiet.trang_thai', 1)
            ->where('san_pham.trang_thai', 1)

            ->when($request->id_danh_muc, function ($q) use ($request) {
                $q->where('san_pham.id_danh_muc', $request->id_danh_muc);
            })
            ->when($request->gia_tu, function ($q) use ($request) {
                $q->where('san_pham_chi_tiet.gia_ban', '>=', $request->gia_tu);
            })
            ->when($request->gia_den, function ($q) use ($request) {
                $q->where('san_pham_chi_tiet.gia_ban', '<=', $request->gia_den);
            })
            ->paginate(10)
            ->withQueryString();
    }

    /*public function sanPhamChiTiet($id)
    {
        return SanPhamChiTietModel::with(['sanpham', 'sanpham.sanPhamChiTiets'])
            ->findOrFail($id);
    }*/
    public function sanPhamChiTiet($ma_sp)
    {
        return Cache::remember(SanPhamCache::detail($ma_sp), now()->addMinutes(30), function () use ($ma_sp) {
                /*return SanPhamChiTietModel::with([
                    'sanpham',
                    'sanpham.sanPhamChiTiets',
                    'hinhAnhs',
                    'nguoiban'
                ])
                ->where('ma_sp', $ma_sp)
                ->firstOrFail();*/
                return SanPhamChiTietModel::with([
                        'sanpham' => function ($q) {$q->where('trang_thai', 1);},
                        'sanpham.sanPhamChiTiets' => function ($q) {$q->where('trang_thai', 1);},
                        'hinhAnhs',
                        'nguoiban'
                    ])
                    ->where('ma_sp', $ma_sp)
                    ->where('trang_thai', 1)
                    ->whereHas('sanpham', function ($q) {
                        $q->where('trang_thai', 1);
                    })
                    ->firstOrFail();

            }
        );
    }

    public function muaNgay($request)
    {
        $sp = SanPhamChiTietModel::findOrFail($request->id_san_pham_chi_tiet);

        $cart = [];

        $cart[$sp->id] = [
            'id' => $sp->id,
            'ten_san_pham' => $sp->sanPham->ten_san_pham,
            'ten_phu' => $sp->ten_phu,
            'gia_ban' => $sp->gia_ban,
            'so_luong' => $request->so_luong,
            'anh' => $sp->anh_dai_dien
        ];

        session()->put('cart', $cart);
    }

    /*public function danhSachDanhGia($id)
    {
        return DanhGiaModel::with(['khachHang', 'dinhKems'])
            ->where('id_san_pham_chi_tiet', $id)
            ->where('trang_thai', 1)
            ->latest('thoi_gian_danh_gia')
            ->get();
    }*/
    public function danhSachDanhGia($id)
    { 
        $spct = SanPhamChiTietModel::findOrFail($id);

        return DanhGiaModel::with([
                'khachHang', 
                'dinhKems',
                'sanPhamChiTiet'
            ])
            ->whereHas('sanPhamChiTiet', function($q) use ($spct) {
                $q->where('id_san_pham', $spct->id_san_pham);
            })
            ->where('trang_thai', 1)
            ->latest('thoi_gian_danh_gia')
            //->get();
            ->paginate(7);
    }

    /*public function daMuaChuaDanhGia($id)
    {
        return HoaDonCtModel::whereHas('hoaDon', function($q){
                $q->where('id_khach_hang', auth('customer')->id())
                  ->where('trang_thai', 5);
            })
            ->where('id_san_pham_chi_tiet', $id)
            ->whereDoesntHave('danhGia')
            ->get();
    }*/

    /*public function reviewQuery($id)
    {
        return DanhGiaModel::where('id_san_pham_chi_tiet', $id)
                            ->where('trang_thai', 1);
    }*/
    public function reviewSummary($idSanPham)
    {
        return Cache::remember(HomeCache::reviewSummary($idSanPham), now()->addMinutes(30), function () use ($idSanPham){
    
            return DanhGiaModel::whereHas('sanPhamChiTiet', function ($q) use ($idSanPham) {

                    $q->where('id_san_pham', $idSanPham);

                })
                ->where('trang_thai', 1)
                ->selectRaw("
                    COUNT(*) total,
                    ROUND(AVG(danh_gia),1) average,

                    SUM(CASE WHEN danh_gia >= 4.5 THEN 1 ELSE 0 END) star5,
                    SUM(CASE WHEN danh_gia >= 3.5 AND danh_gia < 4.5 THEN 1 ELSE 0 END) star4,
                    SUM(CASE WHEN danh_gia >= 2.5 AND danh_gia < 3.5 THEN 1 ELSE 0 END) star3,
                    SUM(CASE WHEN danh_gia >= 1.5 AND danh_gia < 2.5 THEN 1 ELSE 0 END) star2,
                    SUM(CASE WHEN danh_gia < 1.5 THEN 1 ELSE 0 END) star1
                ")
                ->first();

            }
        );
    }

    public function daMuaChuaDanhGia($id)
    {
        $spct = SanPhamChiTietModel::findOrFail($id);

        /*return HoaDonCtModel::whereHas('hoaDon', function($q){
                
                $q->where('id_khach_hang', auth('customer')->id())
                ->where('trang_thai', 5);
            })
            ->whereHas('sanPhamChiTiet', function($q) use ($spct) {
                $q->where('id_san_pham', $spct->id_san_pham);
            })
            ->whereDoesntHave('danhGia')
            ->get();*/
        return HoaDonCtModel::with('sanPhamChiTiet')
            ->whereHas('hoaDon', function ($q) {
                $q->where('id_khach_hang', auth('customer')->id())
                  ->where('trang_thai', 5);
            })
            ->whereHas('sanPhamChiTiet', function ($q) use ($spct) {
                $q->where('id_san_pham', $spct->id_san_pham);
            })
            ->whereDoesntHave('danhGia')
            ->get();
    }

    public function themDanhGia(Request $request)
    {
        return DB::transaction(function () use ($request) {

            $cthd = HoaDonCtModel::where('id', $request->id_hoa_don_chi_tiet)
                ->whereHas('hoaDon', function($q){
                    $q->where('id_khach_hang', auth('customer')->id())
                    ->where('trang_thai', 5);
                })
                ->first();

            if (!$cthd) {
                return [
                    'status' => false,
                    'message' => 'Không hợp lệ hoặc chưa mua hàng'
                ];
            }

            if (DanhGiaModel::where('id_hoa_don_chi_tiet', $cthd->id)->exists()) {
                return [
                    'status' => false,
                    'message' => 'Bạn đã đánh giá rồi'
                ];
            } 

            $danhGia = DanhGiaModel::create([
                'id_san_pham_chi_tiet' => $cthd->id_san_pham_chi_tiet,
                'id_hoa_don_chi_tiet' => $cthd->id,
                'ma_danh_gia' => Str::random(10),
                'danh_gia' => $request->danh_gia,
                'noi_dung' => $request->noi_dung,
                'id_khach_hang' => auth('customer')->id(),
                'trang_thai' => 1,
                'thoi_gian_danh_gia' => now(),
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {

                    //$fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $fileName = 'DANHGIA'.auth('customer')->id().'$'.$cthd->id. '_' . now()->format('dmY_His').'_'.Str::random(8).'.'.$file->extension();
                    $file->storeAs('public/anh_danh_gia', $fileName);

                    DinhKemDanhGiaModel::create([
                        'id_danh_gia' => $danhGia->id,
                        'dinh_kem' => $fileName
                    ]);
                }
            }

            //Cache::forget("review_summary:{$cthd->sanPhamChiTiet->id_san_pham}");
            HomeCache::forgetReviewSummary($cthd->sanPhamChiTiet->id_san_pham);

            SanPhamCache::forgetDetail($cthd->sanPhamChiTiet->ma_sp);

            return [
                'status' => true,
                'message' => 'Đánh giá thành công'
            ];
        });
    }
}