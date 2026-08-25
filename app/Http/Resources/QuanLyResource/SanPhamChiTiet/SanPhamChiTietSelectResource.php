<?php

namespace App\Http\Resources\QuanLyResource\SanPhamChiTiet;

use Illuminate\Http\Resources\Json\JsonResource;

class SanPhamChiTietSelectResource extends JsonResource
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
            //'uuid' => $this->uuid,
            'id_san_pham' => $this->id_san_pham,
            'ma_sp' => $this->ma_sp,
            'ten_phu' => $this->ten_phu,
            'anh_dai_dien' => $this->anh_dai_dien,
            'gia_ban' => $this->gia_ban,
            'gia_khuyen_mai' => $this->gia_khuyen_mai,
            'so_luong' => $this->so_luong,
            'sanpham' => [
                'id' => $this->sanpham->id,
                'ten_san_pham' => $this->sanpham->ten_san_pham,
            ]
        ];
    }
}
