<?php

/*namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\APIModel\TinhModel;
use App\Models\APIModel\HuyenModel;
use App\Models\APIModel\PhuongModel;
use App\APIDiaChi\ProvinceApi;
use App\APIDiaChi\DistrictApi;
use App\APIDiaChi\WardApi;

class ImportDiaChiFromGHN extends Command
{
    protected $signature = 'ghn:import-diachi';
    protected $description = 'Import dữ liệu tỉnh/huyện/phường từ GHN vào DB';

    public function handle()
    {
        $this->info('Bắt đầu import dữ liệu từ GHN...');

        // Import tỉnh
        $provinces = app(ProvinceApi::class)->all();
        foreach ($provinces as $p) {
            TinhModel::updateOrCreate(
                ['province_id' => $p['ProvinceID']],
                ['province_name' => $p['ProvinceName']]
            );
        }
        $this->info('Đã import tỉnh');

        // Import huyện cho từng tỉnh
        foreach ($provinces as $p) {
            $districts = app(DistrictApi::class)->byProvince($p['ProvinceID']);
            
            foreach ($districts as $d) {
                $this->info("Đang import huyện {$d['DistrictName']} ({$d['DistrictID']})");

                try {
                    $wards = app(WardApi::class)->byDistrict($d['DistrictID']);
                } catch (\Exception $e) {
                    $this->error("Lỗi khi lấy phường cho huyện {$d['DistrictName']}: " . $e->getMessage());
                    continue;
                }

                // Kiểm tra dữ liệu phường
                if (empty($wards)) {
                    $this->warn("Không có phường cho huyện {$d['DistrictName']}");
                } else {
                    $this->info("Có " . count($wards) . " phường cho huyện {$d['DistrictName']}");
                }

                HuyenModel::updateOrCreate(
                    ['district_id' => $d['DistrictID']],
                    [
                        'district_name' => $d['DistrictName'],
                        'province_id'   => $p['ProvinceID']
                    ]
                );

                // Import phường cho từng huyện
                $wards = app(WardApi::class)->byDistrict($d['DistrictID']);            
                foreach ($wards as $w) {
                    PhuongModel::updateOrCreate(
                        ['ward_code' => $w['WardCode']],
                        [
                            'ward_name'   => $w['WardName'],
                            'district_id' => $d['DistrictID']
                        ]
                    );
                }
            }
        }

        $this->info('Đã import huyện và phường');
        $this->info('Hoàn tất import dữ liệu địa chỉ từ GHN!');
    }
}*/

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\APIModel\TinhModel;
use App\Models\APIModel\HuyenModel;
use App\Models\APIModel\PhuongModel;
use App\APIDiaChi\ProvinceApi;
use App\APIDiaChi\DistrictApi;
use App\APIDiaChi\WardApi;
use Illuminate\Support\Facades\DB;

class ImportDiaChiFromGHN extends Command
{
    protected $signature = 'ghn:import-diachi';
    protected $description = 'Import dữ liệu tỉnh/huyện/phường từ GHN (CHUẨN)';

    public function handle()
    {
        $this->info('Reset bảng địa chỉ...');
        DB::table('phuong')->truncate();
        DB::table('huyen')->truncate();
        DB::table('tinh')->truncate();

        $this->info('Import tỉnh...');
        $provinces = app(ProvinceApi::class)->all();

        foreach ($provinces as $p) {
            TinhModel::create([
                'province_id'   => $p['ProvinceID'],
                'province_name' => $p['ProvinceName'],
            ]);
        }

        $this->info('Import huyện & phường...');
        foreach ($provinces as $p) {

            $districts = app(DistrictApi::class)->byProvince($p['ProvinceID']);

            foreach ($districts as $d) {

                HuyenModel::create([
                    'district_id'   => $d['DistrictID'],
                    'district_name' => $d['DistrictName'],
                    'province_id'   => $d['ProvinceID'],
                ]);

                $wards = app(WardApi::class)->byDistrict($d['DistrictID']);

                foreach ($wards as $w) {
                    PhuongModel::create([
                        'ward_code'   => $w['WardCode'],
                        'ward_name'   => $w['WardName'],
                        'district_id' => $d['DistrictID'],
                    ]);
                }
            }
        }

        $this->info('Import GHN hoàn tất dữ liệu chuẩn!');
    }
}
