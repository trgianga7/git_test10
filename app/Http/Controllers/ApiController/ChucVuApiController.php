<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ChucVu\ChucVuService;

class ChucVuApiController extends Controller
{
    protected ChucVuService $chucVuService;

    public function __construct(ChucVuService $chucVuService)
    {
        $this->chucVuService = $chucVuService;
    }

    public function index(Request $request)
    {
        $data = $this->chucVuService->getList(
            $request->search
        );

        return response()->json($data);
    }

    public function getAll()
    {
        return response()->json(
            $this->chucVuService->getListAll()
        );
    }

    public function getAllChucNang()
    {
        $data = $this->chucVuService->getAllChucNang();

        return response()->json($data);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_chuc_vu' => 'required|unique:chuc_vu,ten_chuc_vu',
            'chuc_nang_ids' => 'nullable|array',
            'chuc_nang_ids.*' => 'integer|exists:chuc_nang,id'
        ]);

        $this->chucVuService->create(
            $data,
            $request->chuc_nang_ids ?? []
        );

        return response()->json([
            'message' => 'Thêm chức vụ thành công'
        ]);
    }

    public function show($id)
    {
        return response()->json(
            $this->chucVuService->getDetail($id)
                                ->load('chucNangs')
        );
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'ten_chuc_vu' => 'required|unique:chuc_vu,ten_chuc_vu,' . $id,
            'trang_thai' => 'required|integer|in:0,1'
        ]);

        $this->chucVuService->update(
            $id,
            $data,
            $request->chuc_nang_ids ?? []
        );

        return response()->json([
            'message' => 'Cập nhật thành công'
        ]);
    }

    public function destroy($id)
    {
        $this->chucVuService->delete($id);

        return response()->json([
            'message' => 'Xóa thành công'
        ]);
    }
}