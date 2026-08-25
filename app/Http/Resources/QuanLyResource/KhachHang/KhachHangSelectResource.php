<?php

namespace App\Http\Resources\QuanLyResource\KhachHang;

use Illuminate\Http\Resources\Json\JsonResource;

class KhachHangSelectResource extends JsonResource
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
            'ten_khach_hang' => $this->ten_khach_hang,


            
        ];
    }
}
