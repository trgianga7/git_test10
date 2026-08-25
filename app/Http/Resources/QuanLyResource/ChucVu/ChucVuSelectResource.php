<?php

namespace App\Http\Resources\QuanLyResource\ChucVu;

use Illuminate\Http\Resources\Json\JsonResource;

class ChucVuSelectResource extends JsonResource
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
            'ten_chuc_vu' => $this->ten_chuc_vu,
        ];
    }
}
