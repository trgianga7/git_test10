<?php

namespace App\Http\Controllers\ApiController;

use App\Services\NguoiDung\NguoiDungService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuanLyResource\NguoiDung\NguoiDungResource;

class NguoiDungApiController extends Controller
{
    protected NguoiDungService $nguoiDungService;

    public function __construct(NguoiDungService $nguoiDungService)
    {
        $this->nguoiDungService = $nguoiDungService;
    }

    public function index(Request $request)
    {
        /*return response()->json(
            $this->nguoiDungService->getList($request->search)
        );*/

        $nguoiDung = $this->nguoiDungService->getList($request->search);

        return response()->json([
            'data' => NguoiDungResource::collection($nguoiDung),
            'current_page' => $nguoiDung->currentPage(),
            'last_page' => $nguoiDung->lastPage(),
            'per_page' => $nguoiDung->perPage(),
            'total' => $nguoiDung->total()
        ]);
    }

    public function show($uuid)
    {
        $nguoiDungDetail = $this->nguoiDungService->getDetail($uuid);

        return response()->json(
            new NguoiDungResource($nguoiDungDetail)
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

    public function moKhoa($uuid)
    {
        $nguoiDung = $this->nguoiDungService->mokhoa($uuid);

        return response()->json([
            'message' => 'Đã mở khóa tài khoản',
            'data' => $nguoiDung
        ]);
    }

}