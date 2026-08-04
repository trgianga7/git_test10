<?php

namespace App\Http\Resources\TrangChuResource\DanhSachSanPham;

use Illuminate\Http\Resources\Json\JsonResource;

class DanhSachSanPhamResource extends JsonResource
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
            'ma_sp' => $this->ma_sp,
            'ten_san_pham' => $this->sanpham->ten_san_pham,
            'ten_phu' => $this->ten_phu,
            'gia_ban' => $this->gia_ban,
            'gia_khuyen_mai' => $this->gia_khuyen_mai,
            'anh_dai_dien' => $this->anh_dai_dien,
        ];
    }
}
