<?php

namespace App\Http\Resources\TrangChuResource\ChiTietSanPham;

use Illuminate\Http\Resources\Json\JsonResource;

class DanhGiaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'danh_gia' => $this->danh_gia,
            'noi_dung' => $this->noi_dung,
            'thoi_gian_danh_gia' => $this->thoi_gian_danh_gia,

            'khach_hang' => [
                'ten_khach_hang' => $this->khachHang->ten_khach_hang,
            ],

            'san_pham_chi_tiet' => [
                'ma_sp' => $this->sanPhamChiTiet->ma_sp,
                'ten_phu' => $this->sanPhamChiTiet->ten_phu,
            ],

            'dinh_kems' => $this->dinhKems->map(function ($item) {
                return [
                    'dinh_kem' => $item->dinh_kem,
                ];
            }),
        ];
    }
}
