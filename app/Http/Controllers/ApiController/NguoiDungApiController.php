<?php

namespace App\Http\Controllers\ApiController;

use App\Services\NguoiDung\NguoiDungService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NguoiDungApiController extends Controller
{
    protected NguoiDungService $nguoiDungService;

    public function __construct(NguoiDungService $nguoiDungService)
    {
        $this->nguoiDungService = $nguoiDungService;
    }

    public function index(Request $request)
    {
        return response()->json(
            $this->nguoiDungService->getList($request->search)
        );
    }

    public function show($uuid)
    {
        return response()->json(
            $this->nguoiDungService->getDetail($uuid)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_chuc_vu' => 'required',
            'ten_nguoi_dung' => 'required',
            'email' => 'required|max:255|unique:nguoi_dung,email',
            'mat_khau' => 'required',
            'sdt_lien_he' => 'nullable'
        ]);

        $user = $this->nguoiDungService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Thêm thành công',
            'data' => $user
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $timNguoiDung = $this->nguoiDungService->getDetail($uuid);

        $data = $request->validate([
            'id_chuc_vu' => 'required',
            'ten_nguoi_dung' => 'required',
            'email' => 'required|max:255|unique:nguoi_dung,email,' . $timNguoiDung->id,
            'mat_khau' => 'nullable',
            'sdt_lien_he' => 'nullable',
            'trang_thai' => 'required|integer|in:0,1'
        ]);

        $user = $this->nguoiDungService->update(
            $timNguoiDung->id, 
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'data' => $user
        ]);
    }

    public function destroy($uuid)
    {
        $this->nguoiDungService->delete($uuid);

        return response()->json([
            'success' => true,
            'message' => 'Xóa thành công'
        ]);
    }

}