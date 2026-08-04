<?php

namespace App\Http\Controllers\ApiController;

use App\Services\DanhMuc\DanhMucService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DanhMucApiController extends Controller
{
    protected DanhMucService $danhMucService;

    public function __construct(DanhMucService $danhMucService)
    {
        $this->danhMucService = $danhMucService;
    }

    public function index(Request $request)
    {
        return response()->json(
            $this->danhMucService->getList($request->search)
        );
    }

    public function show($id)
    {
        return response()->json(
            $this->danhMucService->getDetail($id)
        );
    }

    public function getAll(Request $request)
    {
        return response()->json(
            $this->danhMucService->getListAll()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_danh_muc' => 'required|string|max:255|unique:danh_muc,ten_danh_muc'
        ]);

        $danhMuc = $this->danhMucService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Thêm thành công',
            'data' => $danhMuc
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'ten_danh_muc' => 'required|string|max:255|unique:danh_muc,ten_danh_muc,' .$id,
            'trang_thai' => 'required|integer|in:0,1'
        ]);

        $danhMuc = $this->danhMucService->update($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'data' => $danhMuc
        ]);
    }

    public function destroy($id)
    {
        $this->danhMucService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Xóa thành công'
        ]);
    }

}