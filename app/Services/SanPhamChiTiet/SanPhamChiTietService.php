<?php

namespace App\Services\SanPhamChiTiet;

use App\Models\SanPhamChiTietModel;
//use App\Models\SanPhamModel;
use App\Models\HinhAnhModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Support\Cache\SanPhamCache;

class SanPhamChiTietService
{
    public function getList($search = null, $perPage = 5)
    {
        $query = SanPhamChiTietModel::with([
            'sanpham',
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('sanpham', function ($q2) use ($search) {
                    $q2->where('ten_san_pham', 'like', "{$search}%");
                })
                ->orWhere('ten_phu', 'like', "{$search}%");
            });
        }

        return $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();
    }

    public function getListAll()
    {
        return SanPhamChiTietModel::with('sanpham')
                            ->where('trang_thai', '!=', 0)
                            ->orderBy('ten_phu')->get();
    }

    /*public function getSanPham()
    {
        return SanPhamModel::where('trang_thai', 1)->get();
    }*/

    public function create($data)
    {
        $this->checkAnh($data['anh'] ?? []);

        return DB::transaction(function () use ($data) {

            $avatar = $data['anh_dai_dien'] ?? null;
            $images = $data['anh'] ?? [];

            unset($data['anh_dai_dien'], $data['anh']);

            $data['gia_khuyen_mai'] = $data['gia_ban'];
            $data['trang_thai'] = 1;
            $data['ngay_tao'] = now();

            $sanPhamCt = SanPhamChiTietModel::create($data);

            if ($avatar) {
                $fileName = 'AnhSP'.$sanPhamCt->id.'_'.Str::random(5).'.'.$avatar->extension();

                $path = $avatar->storeAs(
                    'anh_san_pham',
                    $fileName,
                    'public'
                );

                $sanPhamCt->update([
                    'anh_dai_dien' => $path
                ]);
            }

            foreach ($images as $file) {
                $fileName = 'AnhSP'.$sanPhamCt->ma_sp.'_'.Str::random(5).'.'.$file->extension();

                $path = $file->storeAs(
                    'anh_san_pham',
                    $fileName,
                    'public'
                );

                HinhAnhModel::create([
                    'id_san_pham_chi_tiet' => $sanPhamCt->id,
                    'anh' => $path
                ]);
            }

            SanPhamCache::forgetProduct($sanPhamCt->id_san_pham);

            return $sanPhamCt;
        });
    }

    public function update($id, $data)
    {
        $this->checkAnh($data['anh'] ?? []);

        return DB::transaction(function () use ($id, $data) {

            $avatar = $data['anh_dai_dien'] ?? null;
            $images = $data['anh'] ?? [];

            unset($data['anh_dai_dien'], $data['anh']);

            $sanPhamCt = SanPhamChiTietModel::findOrFail($id);
            //$sanPhamCt = SanPhamChiTietModel::where('ma_sp', $ma_sp)->firstOrFail();

            //$data['ma_sp'] = (string) Str::uuid();
            $data['gia_khuyen_mai'] = $data['gia_ban'];
            $sanPhamCt->update($data);

            if ($avatar) {

                if (
                    $sanPhamCt->anh_dai_dien &&
                    Storage::disk('public')->exists($sanPhamCt->anh_dai_dien)
                ) {
                    Storage::disk('public')->delete($sanPhamCt->anh_dai_dien);
                }

                $fileName =
                    'AnhSP' .
                    $sanPhamCt->id . '_' .
                    Str::random(5) . '.' .
                    $avatar->extension();

                $path = $avatar->storeAs(
                    'anh_san_pham',
                    $fileName,
                    'public'
                );

                $sanPhamCt->update([
                    'anh_dai_dien' => $path
                ]);
            }

            if (!empty($images)) {

                foreach ($sanPhamCt->hinhAnhs as $oldImg) {

                    if (Storage::disk('public')->exists($oldImg->anh)) {
                        Storage::disk('public')->delete($oldImg->anh);
                    }

                    $oldImg->delete();
                }

                foreach ($images as $file) {

                    $fileName =
                        'AnhSP' .
                        $sanPhamCt->id . '_' .
                        Str::random(5) . '.' .
                        $file->extension();

                    $path = $file->storeAs(
                        'anh_san_pham',
                        $fileName,
                        'public'
                    );

                    HinhAnhModel::create([
                        'id_san_pham_chi_tiet' => $sanPhamCt->id,
                        'anh' => $path
                    ]);
                }
            }

            SanPhamCache::forgetProduct($sanPhamCt->id_san_pham);

            return $sanPhamCt->fresh()->load('hinhAnhs');
        });
    }

    public function getDetail($ma_sp)
    {
        return SanPhamChiTietModel::with('hinhAnhs')
                                    ->where('ma_sp', $ma_sp)
                                    ->firstOrFail();;
    }

    private function checkAnh($images)
    {
        if (!empty($images) && count($images) > 4) {
            throw new \Exception('Chỉ được upload tối đa 4 ảnh.');
        }
    }

    public function delete($ma_sp)
    {
        $sanPhamCt = $this->getDetail($ma_sp);

        SanPhamCache::forgetProduct($sanPhamCt->id_san_pham);

        return $sanPhamCt->delete();
    }
}