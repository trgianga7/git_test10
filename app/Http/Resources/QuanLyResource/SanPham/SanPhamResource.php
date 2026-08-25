<?php

namespace App\Http\Resources\QuanLyResource\SanPham;

use Illuminate\Http\Resources\Json\JsonResource;

class SanPhamResource extends JsonResource
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
            'id_danh_muc' => $this->danhmuc->id,
            'key_sp' => $this->key_sp,
            'ten_san_pham' => $this->ten_san_pham,
            'trang_thai' => $this->trang_thai,
            'nguoi_tao' => $this->nguoi_tao,
            'ngay_tao' => $this->ngay_tao,
            'nguoidung' => [
                'id' => $this->nguoidung->id,
                'ten_nguoi_dung' => $this->nguoidung->ten_nguoi_dung,
            ],
            'danhmuc' => [
                'id' => $this->danhmuc->id,
                'ten_danh_muc' => $this->danhmuc->ten_danh_muc,
            ],
            
        ];
    }
}
