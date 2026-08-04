<?php

namespace Database\Seeders;

use App\Models\NguoiDungModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        NguoiDungModel::create([
            'uuid' => (string) Str::uuid(),

            'id_chuc_vu' => 1,
            'ten_nguoi_dung' => 'Giang',

            'email' => '1@gmail.com',
            'mat_khau' => Hash::make('123456'),

            'trang_thai' => 1,

            'so_lan_sai' => 0,
            'diem' => 0,

            'sdt_lien_he' => NULL,

            'ngay_tao' => now(),
        ]);
    }
}