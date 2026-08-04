<?php

namespace App\Http\Resources\TrangChuResource\ChiTietSanPham;

use Illuminate\Http\Resources\Json\JsonResource;

class HoaDonChiTietResource extends JsonResource
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
            'ma_sp' => $this->sanPhamChiTiet->ma_sp,
            'ten_san_pham' => $this->ten_san_pham,
            'ten_phu' => $this->sanPhamChiTiet->ten_phu,
            'so_luong' => $this->so_luong
        ];
    }
}
