<?php

namespace App\Services\MuaHangOnline;

use App\Models\SanPhamChiTietModel;
use App\Models\HoaDonModel;
use App\Models\KhachHangModel;
use App\Models\GiamGiaModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Support\Cache\SanPhamCache;

class ThanhToanService 
{
    private function layDuLieuThanhToan()
    {
        return session()->has('buy_now') ? session('buy_now') : session('cart', []);
    }

    public function hienThiThanhToan()
    {
        $customer = auth('customer')->user();

        $cart = $this->layDuLieuThanhToan();

        $tongTien = 0;

        foreach ($cart as $item) {

            $tongTien += $item['gia_ap_dung'] * $item['so_luong'];
        }

        $diaChi = [];

        if ($customer) {

            $diaChi = $customer->diaChis()
                ->with([
                    'tinh_ten',
                    'huyen_ten',
                    'phuong_ten'
                ])
                ->where('trang_thai', 1)
                ->get()
                ->map(function ($item) {

                    return [
                        'id' => $item->id,
                        'ket_hop_dia_chi' => $item->ket_hop_dia_chi
                    ];
                })
                ->values();
        }

        return [
            'ten_nguoi_nhan' => $customer->ten_khach_hang ?? '',
            'sdt' => $customer->sdt ?? '',
            'so_du' => $customer->vi ?? 0,
            'dia_chi' => $diaChi,
            'gio_hang' => array_values($cart),
            'tong_tien' => $tongTien
        ];
    }

    public function thanhToan($request)
    {
        $diaChi = trim($request->dia_chi ?? '');

        if (empty($diaChi)) {
            throw ValidationException::withMessages([
                'dia_chi_hd' => 'Địa chỉ không được để trống!'
            ]);
        }

        $giamGia = null;

        if ($request->filled('ma_giam_gia')) {
            $giamGia = GiamGiaModel::where('ma_giam_gia', $request->ma_giam_gia)
                ->where('trang_thai', 1)
                ->where('ngay_het_han', '>=', now())
                ->lockForUpdate()
                ->first();

            if (!$giamGia) {
                throw ValidationException::withMessages([
                    'ma_giam_gia_ap_dung' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn'
                ]);
            }
        }

        $cart = $this->layDuLieuThanhToan();

        $ct = collect($cart)->map(function ($item) {
            return [
                'id_san_pham_chi_tiet' => $item['id'],
                'so_luong' => $item['so_luong'],
            ];
        })->values()->toArray();

        return DB::transaction(function () use ($request, $ct, $giamGia, $diaChi) {

            $tongTienGoc = 0;
            $hoaDonCt = [];

            foreach ($ct as $item) {
                $sp = SanPhamChiTietModel::lockForUpdate()
                    ->with('sanPham')
                    ->findOrFail($item['id_san_pham_chi_tiet']);

                if ($sp->so_luong < $item['so_luong']) {
                    throw ValidationException::withMessages([
                        'san_pham' => "Sản phẩm {$sp->sanPham->ten_san_pham} không đủ số lượng"
                    ]);
                }

                $giaApDung = $sp->gia_khuyen_mai > 0 ? $sp->gia_khuyen_mai : $sp->gia_ban;

                $thanhTien = $giaApDung * $item['so_luong'];

                $hoaDonCt[] = [
                    'id_san_pham_chi_tiet' => $sp->id,
                    'ten_san_pham' => $sp->sanPham->ten_san_pham . " - " . $sp->ten_phu,
                    'gia_ban' => $giaApDung,
                    'so_luong' => $item['so_luong'],
                    'tong_tien_hd' => $thanhTien,
                    'ngay_tao' => now(),
                ];

                $sp->decrement('so_luong', $item['so_luong']);
                $tongTienGoc += $thanhTien;
            }

            $soTienGiam = 0;
            $giaTriGiam = 0;
            $loaiGiamGiaHd = null;

            if ($giamGia) {
                $giaTriGiam = $giamGia->gia_tri;

                if ($giamGia->loai_giam_gia == 0) {
                    $soTienGiam = $giamGia->gia_tri;
                    $loaiGiamGiaHd = 'đ';
                } else {
                    $soTienGiam = ($tongTienGoc * $giamGia->gia_tri) / 100;
                    $loaiGiamGiaHd = '%';
                }

                $soTienGiam = min($soTienGiam, $tongTienGoc);

                if ($giamGia->so_luong <= 0) {
                    throw ValidationException::withMessages([
                        'ma_giam_gia_ap_dung' => 'Mã giảm giá đã hết lượt sử dụng'
                    ]);
                }

                $giamGia->decrement('so_luong', 1);
            }

            $tongTienThuc = $tongTienGoc - $soTienGiam;

            $isQR  = $request->phuong_thuc === 'qr';
            $isPAY = $request->phuong_thuc === 'pay';

            if ($isPAY) {
                $khachHang = KhachHangModel::lockForUpdate()
                    ->findOrFail(auth('customer')->id());

                if ($khachHang->vi < $tongTienThuc) {
                    throw ValidationException::withMessages([
                        'vi' => 'Số dư không đủ để thanh toán'
                    ]);
                }

                $khachHang->decrement('vi', $tongTienThuc);
            }

            SanPhamCache::forgetProduct($sp->id_san_pham);

            $hoaDon = HoaDonModel::create([
                'id_khach_hang' => auth('customer')->id() ?? NULL,
                'dia_chi_hd' => $diaChi,
                'ten_nguoi_nhan' => $request->ten_nguoi_nhan,
                'sdt_nguoi_nhan' => $request->sdt_nguoi_nhan,
                'tong_tien_goc' => $tongTienGoc,
                'ten_giam_gia' => $giamGia?->ten_giam_gia,
                'giam_gia' => $giaTriGiam,
                'loai_giam_gia_hd' => $loaiGiamGiaHd,
                'tong_tien_thuc' => $tongTienThuc,
                'loai_hinh' => $isQR ? 2 : ($isPAY ? 1 : 0),
                'trang_thai_thanh_toan' => $isPAY ? 1 : 0,
                'trang_thai' => 1,
                'ngay_tao' => now(),
            ]);

            $hoaDon->chiTiets()->createMany($hoaDonCt);

            DB::table('thoi_gian_trang_thai')->insert([
                'id_hoa_don' => $hoaDon->id,
                'ls_trang_thai' => $hoaDon->trang_thai,
                'thoi_gian_trang_thai' => now(),
            ]);


            if(session()->has('buy_now')){

                session()->forget('buy_now');
            
            }else{
            
                session()->forget('cart');
            
            }

            return $hoaDon;
        });
    }

    public function kiemTraMa($request){
        $ma = GiamGiaModel::where('ma_giam_gia', $request->ma_giam_gia)
            ->where('trang_thai', 1)
            ->whereDate('ngay_het_han', '>=', now())
            ->first();

        if (!$ma) {
            return [
                'success' => false
            ];
        }    

        return [
            'success' => true,
            'loai' => $ma->loai_giam_gia,
            'gia_tri' => $ma->gia_tri
        ];
    }

    public function taoQR($id)
    {
        $hoaDon = HoaDonModel::findOrFail($id);

        if ($hoaDon->trang_thai_thanh_toan != 0) {
            throw new \Exception("Hóa đơn đã thanh toán hoặc không hợp lệ");
        }

        $soTien = $hoaDon->tong_tien_thuc;
        $noiDung = "HD" . $hoaDon->id;

        $bank = "TPB";
        $account = "0903444568";

        $qrUrl = "https://img.vietqr.io/image/{$bank}-{$account}-compact.png"
            . "?amount={$soTien}"
            . "&addInfo=" . urlencode($noiDung)
            . "&accountName=" . urlencode("Giang");

        return [
            'qrUrl' => $qrUrl,
            'hoaDon' => $hoaDon
        ];
    }

}