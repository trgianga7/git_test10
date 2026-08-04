<?php

namespace App\Http\Resources\TrangChuResource\ChiTietSanPham;

use Illuminate\Http\Resources\Json\JsonResource;

class ChiTietSanPhamResource extends JsonResource
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
            'ten_phu' => $this->ten_phu,
            'gia_ban' => $this->gia_ban,
            'gia_khuyen_mai' => $this->gia_khuyen_mai,
            'so_luong' => $this->so_luong,
            'nhieu_mo_ta' => explode('|', $this->mo_ta),
            'anh_dai_dien' => $this->anh_dai_dien,
            'hinh_anhs' => $this->hinhAnhs->map(function ($item) {
                return [
                    'anh' => $item->anh,
                ];
            }),
            'san_pham_goc' => [
                'key_sp' => $this->sanpham->key_sp,
                'ten_san_pham' => $this->sanpham->ten_san_pham,
                'san_pham_chi_tiets' => $this->sanpham->sanPhamChiTiets->map(function($item){
                    return [
                        'ma_sp'=>$item->ma_sp,
                        'ten_phu'=>$item->ten_phu,
                        'gia_ban'=>$item->gia_ban,
                        'gia_khuyen_mai'=>$item->gia_khuyen_mai,
                        'anh_dai_dien'=>$item->anh_dai_dien,
                    ];
                }),
    
            ],
            
        ];
    }
}
