<?php

namespace App\Http\Resources\QuanLyResource\GiamGia;

use Illuminate\Http\Resources\Json\JsonResource;

class GiamGiaSanPhamResource extends JsonResource
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
            'ten_phu' => $this->ten_phu,
            'gia_ban' => $this->gia_ban,
            'gia_khuyen_mai' => $this->gia_khuyen_mai,
            'sanpham' => [
                'id' => $this->sanpham->id,
                'ten_san_pham' => $this->sanpham->ten_san_pham,
            ],

            
        ];
    }
}
