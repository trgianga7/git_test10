<?php

namespace App\Http\Resources\QuanLyResource\SanPham;

use Illuminate\Http\Resources\Json\JsonResource;

class SanPhamSelectResource extends JsonResource
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
            'ten_san_pham' => $this->ten_san_pham,


            
        ];
    }
}
