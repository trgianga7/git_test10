<?php

namespace App\Services\ChucVu;

use App\Models\ChucVuModel;
use App\Models\ChucNangModel;
use Illuminate\Support\Facades\DB;

class ChucVuService
{ 
    public function getList($search = null, $perPage = 5)
    {
        $query = ChucVuModel::query(); 

        if ($search) {
            $query->where('ten_chuc_vu', 'like', "{$search}%");
        }

        return $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString(); 
    }

    public function getListAll()
    {
        return ChucVuModel::where('trang_thai', 1)->get();
    }

    public function getAllChucNang()
    {
        return ChucNangModel::where('trang_thai', 1)
            ->orderBy('id', 'asc')
            ->get(['id', 'ten_chuc_nang', 'route'])
            ->groupBy(function ($cn) {

                return str_replace('_', '-', explode('.', $cn->route)[0]);
            });
    }

    public function getChucNangTheoNhom()
    {
        $dsChucNang = ChucNangModel::where('trang_thai', 1)->get();

        /*return $dsChucNang->groupBy(function ($cn) {
            return explode('.', $cn->route)[0];
        });*/
        return $dsChucNang->groupBy(function ($cn) {
            return str_replace('_','-', explode('.', $cn->route)[0]);
        });
    }

    public function getTieuDe()
    {
        return [
            'chuc-vu' => 'Chức vụ',
            'nguoi-dung' => 'Người dùng',
            'khach-hang' => 'Khách hàng',
            'danh-muc' => 'Danh mục',
            'san-pham' => 'Sản phẩm',
            'san-pham-chi-tiet' => 'Sản phẩm chi tiết',
            'hoa-don' => 'Hóa đơn',
            'dia-chi' => 'Địa chỉ',
        ];
    }

    public function create($data, $chuc_nang_ids = [])
    {
        return DB::transaction(function () use ($data, $chuc_nang_ids) {

            $data['trang_thai'] = 1;
            $data['ngay_tao'] = now();
    
            $chucVu = ChucVuModel::create($data);
    
            if (!empty($chuc_nang_ids)) {
                $chucVu->chucNangs()->sync($chuc_nang_ids);
            }
    
            return $chucVu;
        });
    }

    public function update($id, $data, $chuc_nang_ids = [])
    {
        return DB::transaction(function () use ($id, $data, $chuc_nang_ids) {

            $chucVu = ChucVuModel::findOrFail($id);
    
            $chucVu->update($data);
    
            $chucVu->chucNangs()->sync($chuc_nang_ids);
    
            return $chucVu;
        });    
    }

    public function getDetail($id)
    {
        return ChucVuModel::findOrFail($id);
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {

            $chucVu = ChucVuModel::findOrFail($id);

            $chucVu->chucNangs()->detach();

            return $chucVu->delete();
        });
    }
}