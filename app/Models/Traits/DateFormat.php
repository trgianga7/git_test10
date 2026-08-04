<?php

namespace App\Models\Traits;

use Carbon\Carbon;

trait DateFormat
{
    public function formatDate($date)
    {
        if (!$date) return null;
        return Carbon::parse($date)->format('d-m-Y');
    }

    public function getNgayTaoAttribute($value)
    {
        return $this->formatDate($value);
    }

    public function formatDateTime($date)
    {
        if (!$date) return null;

        return Carbon::parse($date)->format('d-m-Y H:i:s');
    }

    public function ngayTaoDayDu()
    {
        return $this->formatDateTime($this->getRawOriginal('ngay_tao'));
    }

    public function getNgayBatDauGiamGiaAttribute()
    {
        if (!$this->ngay_bat_dau) {
            return null;
        }

        return Carbon::parse($this->ngay_bat_dau)->format('Y-m-d H:i');
    }

    public function getNgayHetHanGiamGiaAttribute()
    {
        if (!$this->ngay_het_han) {
            return null;
        }
    
        return Carbon::parse($this->ngay_het_han)->format('Y-m-d H:i');
    }
}
