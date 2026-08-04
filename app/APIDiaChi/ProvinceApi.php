<?php

namespace App\APIDiaChi;

/*class ProvinceApi extends GhnClient
{
    public function all()
    {
        return $this->request('GET', '/master-data/province');

    }

    public function findNameById(int $id)
    {
        foreach ($this->all() as $p) {
            if ($p['ProvinceID'] == $id) {
                return $p['ProvinceName'];
            }
        }
        return null;    

    }

}*/

class ProvinceApi extends GhnClient
{
    public function all(): array
    {
        $res = $this->request('GET', '/master-data/province');
        return $res['data'] ?? []; // chỉ lấy phần data
    }

    public function findNameById(int $id): ?string
    {
        foreach ($this->all() as $p) {
            if (isset($p['ProvinceID']) && (int)$p['ProvinceID'] === $id) {
                return $p['ProvinceName'] ?? null;
            }
        }
        return null;
    }
}


