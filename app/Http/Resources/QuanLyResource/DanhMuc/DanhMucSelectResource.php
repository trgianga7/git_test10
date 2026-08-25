<?php

namespace App\Http\Resources\QuanLyResource\DanhMuc;

use Illuminate\Http\Resources\Json\JsonResource;

class DanhMucSelectResource extends JsonResource
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
            'ten_danh_muc' => $this->ten_danh_muc,
        ];
    }
}
