<?php

namespace App\Http\Resources\QuanLyResource\HoaDon;

use Illuminate\Http\Resources\Json\JsonResource;

class HoaDonResource extends JsonResource
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
            'id' => $this->id,
            'id_khach_hang' => $this->khachhang?->id,
            'ma_hd' => $this->ma_hd,
            'dia_chi_hd' => $this->dia_chi_hd,
            'ten_nguoi_nhan' => $this->ten_nguoi_nhan,
            'sdt_nguoi_nhan' => $this->sdt_nguoi_nhan,
            'tong_tien_goc' => $this->tong_tien_goc,
            'ten_giam_gia' => $this->ten_giam_gia,
            'giam_gia' => $this->giam_gia,
            'loai_giam_gia_hd' => $this->loai_giam_gia_hd,
            'tong_tien_thuc' => $this->tong_tien_thuc,
            'loai_hinh' => $this->loai_hinh,
            'trang_thai_thanh_toan' => $this->trang_thai_thanh_toan,
            'trang_thai' => $this->trang_thai,
            'ngay_tao' => $this->ngay_tao,
            'ten_loai_hinh' => $this->ten_loai_hinh,
            'khachhang' => [
                'id' => $this->khachhang?->id,
                'ten_khach_hang' => $this->khachhang?->ten_khach_hang,
            ],
            'chi_tiets' => $this->chiTiets->map(function ($ct) {
                return [
                    'id' => $ct->id,
                    'id_san_pham_chi_tiet' => $ct->id,
                    'ten_san_pham' => $ct->ten_san_pham,
                    'gia_ban' => $ct->gia_ban,
                    //'giam_gia_sp' => $ct->giam_gia_sp,
                    'so_luong' => $ct->so_luong,
                    'tong_tien_hd' => $ct->tong_tien_hd,
                    //'ngay_tao' => $ct->ngay_tao,
                ];
            }),
            'trangthaihd' => [
                'id' => $this->trangthaihd?->id,
                'trang_thai' => $this->trangthaihd?->trang_thai,
            ],

            
        ];
    }
}
