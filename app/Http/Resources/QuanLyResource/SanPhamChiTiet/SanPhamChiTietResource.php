<?php

namespace App\Http\Resources\QuanLyResource\SanPhamChiTiet;

use Illuminate\Http\Resources\Json\JsonResource;

class SanPhamChiTietResource extends JsonResource
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
            'id_san_pham' => $this->sanpham->id,
            'ma_sp' => $this->ma_sp,
            'anh_dai_dien' => $this->anh_dai_dien,
            'ten_phu' => $this->ten_phu,
            'mo_ta' => $this->mo_ta,
            'gia_ban' => $this->gia_ban,
            'gia_khuyen_mai' => $this->gia_khuyen_mai,
            'khuyen_mai' => $this->khuyen_mai,
            'so_luong' => $this->so_luong,
            'trang_thai' => $this->trang_thai,
            'ngay_tao' => $this->ngay_tao,
            'sanpham' => [
                'id' => $this->sanpham->id,
                'ten_san_pham' => $this->sanpham->ten_san_pham,
            ]
            
        ];
    }
}
