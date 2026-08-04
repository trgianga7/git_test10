<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class NguoiDungObserver
{
    public function created($model)
    {
        Cache::forget('nguoidung_total');
    }

    public function deleted($model)
    {
        Cache::forget('nguoidung_total');
    }

    public function restored($model)
    {
        Cache::forget('nguoidung_total');
    }

    public function forceDeleted($model)
    {
        Cache::forget('nguoidung_total');
    }
}
