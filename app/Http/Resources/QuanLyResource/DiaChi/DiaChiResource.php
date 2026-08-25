<?php

namespace App\Http\Resources\QuanLyResource\DiaChi;

use Illuminate\Http\Resources\Json\JsonResource;

class DiaChiResource extends JsonResource
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
            'id_khach_hang' => $this->khachhang->id,
            'tinh' => $this->tinh_ten->province_id,
            'huyen' => $this->huyen_ten->district_id,
            'phuong' => $this->phuong_ten->ward_code,
            'dia_chi' => $this->dia_chi,
            'trang_thai' => $this->trang_thai,
            'ngay_tao' => $this->ngay_tao,
            'khachhang' => [
                'id' => $this->khachhang->id,
                'ten_khach_hang' => $this->khachhang->ten_khach_hang,
            ],
            'tinh_ten' => [
                'province_id' => $this->tinh_ten->province_id,
                'province_name' => $this->tinh_ten->province_name,
            ],
            'huyen_ten' => [
                'district_id' => $this->huyen_ten->district_id,
                'district_name' => $this->huyen_ten->district_name,
            ],
            'phuong_ten' => [
                'ward_code' => $this->phuong_ten->ward_code,
                'ward_name' => $this->phuong_ten->ward_name,
            ],
            
        ];
    }
}
