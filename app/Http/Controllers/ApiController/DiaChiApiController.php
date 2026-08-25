<?php

namespace App\Http\Controllers\ApiController;

use App\Models\DiaChiModel;
use App\Models\APIModel\TinhModel;
use App\Models\APIModel\HuyenModel;
use App\Models\APIModel\PhuongModel;
use App\Services\DiaChi\DiaChiService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuanLyResource\DiaChi\DiaChiResource;

class DiaChiApiController extends Controller
{
    protected DiaChiService $diaChiService;

    public function __construct(DiaChiService $diaChiService)
    {
        $this->diaChiService = $diaChiService;
    }

    public function index(Request $request)
    {
        /*return response()->json(
            $this->diaChiService->getList($request->search)
        );*/

        $diaChi = $this->diaChiService->getList($request->search);

        return response()->json([
            'data' => DiaChiResource::collection($diaChi),
            'current_page' => $diaChi->currentPage(),
            'last_page' => $diaChi->lastPage(),
            'per_page' => $diaChi->perPage(),
            'total' => $diaChi->total()
        ]);
    }

    public function show($id)
    {
        return response()->json(
            $this->diaChiService->getDetail($id)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_khach_hang' => 'required|int',
            'tinh' => 'required|int',
            'huyen' => 'required|int',
            'phuong' => 'required|string',
            'dia_chi' => 'required|string'
        ]);

        $diaChi = $this->diaChiService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Thêm thành công',
            'data' => $diaChi
        ]);
    }

    
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            //'id_khach_hang' => 'required|int',
            'tinh' => 'required|int',
            'huyen' => 'required|int',
            'phuong' => 'required|string',
            'dia_chi' => 'required|string',
            'trang_thai' => 'required|integer|in:0,1'
        ]);

        $diaChi = $this->diaChiService->update($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'data' => $diaChi
        ]);
    }
    
    public function destroy($id)
    {
        $this->diaChiService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Xóa thành công'
        ]);
    }

    public function getTinh()
    {
        return response()->json(
            TinhModel::orderBy('province_name')->get()
        );
    }

    public function getHuyen($province_id)
    {
        return response()->json(
            HuyenModel::where('province_id', $province_id)
                ->orderBy('district_name')
                ->get()
        );
    }

    public function getPhuong($district_id)
    {
        return response()->json(
            PhuongModel::where('district_id', $district_id)
                ->orderBy('ward_name')
                ->get()
        );
    }

}