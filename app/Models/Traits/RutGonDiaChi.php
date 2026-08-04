<?php

namespace App\Models\Traits;

trait RutGonDiaChi
{
    protected function rutGonDiaChi(?string $name): ?string
    {
        if (!$name) {
            return null;
        }

        $name = trim($name);
        $name = mb_strtolower($name, 'UTF-8');

        $remove = [
            'phường ',
            'xã ',
            'thị trấn ',
            'thị xã ',
            'quận ',
            'huyện ',
            'tp ',
            'thành phố ',
        ];

        return trim(str_replace($remove, '', $name));
    }
}