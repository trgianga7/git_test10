<?php

namespace App\Services\HoaDon;

use App\Models\HoaDonModel;
use App\Models\KhachHangModel;
use App\Models\HoaDonCtModel;
use App\Models\SanPhamChiTietModel;
use App\Models\TrangThaiHoaDonModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Support\Cache\SanPhamCache;

class HoaDonService
{
    public function getList($search = null, $trangThai = null, $perPage = 5)
    {
        $query = HoaDonModel::with([
            'khachhang',
            'chiTiets',
            //'chiTiets.sanPhamChiTiet.sanPham',
            'trangthaihd'
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
    
                $q->where('ma_hd', 'like', "{$search}%")
                  ->orWhere('ten_nguoi_nhan', 'like', "{$search}%")
    
                  ->orWhereHas('khachhang', function ($q2) use ($search) {
                      $q2->where('ten_khach_hang', 'like', "{$search}%")
                         ->orWhere('sdt', 'like', "{$search}%");
                  });
    
            });
        }

        if ($trangThai !== null && $trangThai !== '') {
            $query->where('trang_thai', $trangThai);
        }

        return $query->orderBy('id', 'desc')->paginate($perPage)->withQueryString();;
    }

    public function xemHoaDon($ma_hd)
    {
        return HoaDonModel::query()
            ->select('hoa_don.*', 'khach_hang.ten_khach_hang')
            ->join('khach_hang', 'hoa_don.id_khach_hang', '=', 'khach_hang.id')
            ->where('hoa_don.ma_hd', $ma_hd)
            ->firstOrFail();
    }

    /*public function getHoaDonChiTiet($id)
    {
        return HoaDonCtModel::where('id_hoa_don', $id)->get();
    }*/

    /*public function getSanPhamChiTiet()
    {
        return SanPhamChiTietModel::with('sanpham')->where('trang_thai', 1)->get();
    }*/

    /*public function getKhachHang()
    {
        return KhachHangModel::where('trang_thai', 1)->get();
    }*/

    /*public function getKhachHangAnDanh()
    {
        return KhachHangModel::where('trang_thai', 1)
        ->where('loai_khach_hang', '!=', 0)
        ->get(); 
    }*/

    public function create($data)
    {
        return DB::transaction(function () use ($data) {

            $nguoiNhan = KhachHangModel::where('id', $data['id_khach_hang'])
                ->value('ten_khach_hang');

            $tongTienGoc = 0;
            $hoaDonCt = [];

            foreach ($data['san_pham'] as $ct) {

                $sp = SanPhamChiTietModel::lockForUpdate()
                    ->findOrFail($ct['id_san_pham_chi_tiet']);

                $soLuong = (int)$ct['so_luong'];

                if ($sp->so_luong < $soLuong) {
                    throw ValidationException::withMessages([
                        'san_pham' => "Sản phẩm {$sp->sanpham->ten_san_pham} không đủ số lượng"
                    ]);
                }

                $thanhTien = $sp->gia_ban * $soLuong;

                $hoaDonCt[] = [
                    'id_san_pham_chi_tiet' => $sp->id,
                    'ten_san_pham' => $sp->sanpham->ten_san_pham . ' - ' . $sp->ten_phu,
                    'gia_ban' => $sp->gia_ban,
                    'so_luong' => $soLuong,
                    'tong_tien_hd' => $thanhTien,
                    'ngay_tao' => now()
                ];

                $sp->decrement('so_luong', $soLuong);

                $tongTienGoc += $thanhTien;
            }

            $giamGia = $data['giam_gia'] ?? 0;
            $tongTienThuc = max($tongTienGoc - $giamGia, 0);

            SanPhamCache::forgetProduct($sp->id_san_pham);

            $hoaDon = HoaDonModel::create([
                'id_khach_hang' => $data['id_khach_hang'],
                'ten_nguoi_nhan' => $data['ten_nguoi_nhan'],
                'sdt_nguoi_nhan' => $data['sdt_nguoi_nhan'],
                'dia_chi_hd' => $data['dia_chi_hd'],
                'tong_tien_goc' => $tongTienGoc,
                'giam_gia' => $giamGia,
                'tong_tien_thuc' => $tongTienThuc,
                'loai_hinh' => $data['loai_hinh'],
                'trang_thai_thanh_toan' => $data['trang_thai_thanh_toan'],
                'trang_thai' => $data['loai_hinh'] == 1 ? 5 : 1,
                'ngay_tao' => now(),
            ]);

            $hoaDon->chiTiets()->createMany($hoaDonCt);

            DB::table('thoi_gian_trang_thai')->insert([
                'id_hoa_don' => $hoaDon->id,
                'ls_trang_thai' => $hoaDon->trang_thai,
                'thoi_gian_trang_thai' => now(),
            ]);

            return $hoaDon;
        });
    }

    public function update($id, $data)
    {
        return DB::transaction(function() use ($id, $data){

            $hoaDon = HoaDonModel::findOrFail($id);

            $trangThaiCu = $hoaDon->trang_thai;

            if(in_array($trangThaiCu,[5,6])){
                throw ValidationException::withMessages([
                    'trang_thai' => 'Không thể cập nhật đơn này'
                ]);
            }

            $hoaDon->update([
                'ten_nguoi_nhan'=>$data['ten_nguoi_nhan'],
                'sdt_nguoi_nhan'=>$data['sdt_nguoi_nhan'],
                'dia_chi_hd'=>$data['dia_chi_hd'],
                'loai_hinh'=>$data['loai_hinh'],
                'trang_thai_thanh_toan' =>$data['trang_thai'] == 5 ? 1 : $hoaDon->trang_thai_thanh_toan,
                'trang_thai'=>$data['trang_thai']
            ]);

            if($trangThaiCu != $data['trang_thai']){
                DB::table('thoi_gian_trang_thai')
                    ->insert([
                        'id_hoa_don'=>$hoaDon->id,
                        'ls_trang_thai'=>$data['trang_thai'],
                        'thoi_gian_trang_thai'=>now()
                    ]);
            }

            return $hoaDon;
        });
    }

    /*public function getDetail($id)
    {
        return HoaDonModel::with([
            'chiTiets.sanPhamChiTiet',
            'khachhang'
        ])->findOrFail($id);
    }*/
    public function getDetail($ma_hd)
    {
        return HoaDonModel::with([
            'thoiGianTrangThai',
            'chiTiets.sanPhamChiTiet'
        ])
        ->where('ma_hd', $ma_hd)->firstOrFail();
    }

    public function getListTrangThai()
    {
        return TrangThaiHoaDonModel::all();
    }

    public function TrangThaiMoi($id)
    {
        $trangThaiMoi = $hoaDon = HoaDonModel::findOrFail($id);

        return $trangThaiMoi->thoiGianTrangThai()->orderBy('thoi_gian_trang_thai', 'desc')->first();    

    }

    public function delete($ma_hd)
    {
        $hoaDon = $this->getDetail($ma_hd);
        return $hoaDon->delete();
    }

}