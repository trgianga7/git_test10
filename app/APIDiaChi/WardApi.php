<?php

namespace App\APIDiaChi;

/*class WardApi extends GhnClient
{
    public function byDistrict(int $districtId)
    {
        return $this->request('POST', '/master-data/ward', [
            'district_id' => $districtId
        ]);
    }

    public function findNameByCode(string $wardCode, int $districtId)
    {
        foreach ($this->byDistrict($districtId) as $w) {
            if ($w['WardCode'] == $wardCode) {
                return $w['WardName'];
            }
        }
        return null;
    }
}*/
class WardApi extends GhnClient
{
    public function byDistrict(int $districtId): array
    {
        $res = $this->request('POST', '/master-data/ward', [
            'district_id' => $districtId
        ]);
    
        if (($res['code'] ?? 200) !== 200) {
            // Trả về lỗi
            throw new \Exception("GHN API lỗi cho district_id={$districtId}: " . ($res['message'] ?? 'Unknown'));
        }
    
        return $res['data'] ?? [];
    
    }




    public function findNameByCode(string $wardCode, int $districtId): ?string
    {
        foreach ($this->byDistrict($districtId) as $w) {
            if (isset($w['WardCode']) && $w['WardCode'] === $wardCode) {
                return $w['WardName'] ?? null;
            }
        }
        return null;
    }
}



