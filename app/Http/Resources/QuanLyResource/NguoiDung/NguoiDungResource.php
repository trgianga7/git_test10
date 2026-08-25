<?php

namespace App\Http\Resources\QuanLyResource\NguoiDung;

use Illuminate\Http\Resources\Json\JsonResource;

class NguoiDungResource extends JsonResource
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
            'uuid' => $this->uuid,
            'id_chuc_vu' => $this->id_chuc_vu,
            'ten_nguoi_dung' => $this->ten_nguoi_dung,
            'anh_dai_dien' => $this->anh_dai_dien,
            'email' => $this->email,
            'trang_thai' => $this->trang_thai,
            'so_lan_sai' => $this->so_lan_sai,
            'thoi_gian_khoa' => $this->thoi_gian_khoa,
            'diem' => $this->diem,
            'sdt_lien_he' => $this->sdt_lien_he,
            'ngay_tao' => $this->ngay_tao,
            'chucvu' => [
                'id' => $this->chucvu->id,
                'ten_chuc_vu' => $this->chucvu->ten_chuc_vu,
            ],

            
        ];
    }
}
