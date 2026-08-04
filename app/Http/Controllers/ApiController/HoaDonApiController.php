<?php

namespace App\Http\Controllers\ApiController;

use App\Services\HoaDon\HoaDonService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HoaDonApiController extends Controller
{
    protected HoaDonService $hoaDonService;

    public function __construct(HoaDonService $hoaDonService)
    {
        $this->hoaDonService = $hoaDonService;
    }

    public function index(Request $request)
    {
        return response()->json(
            $this->hoaDonService->getList(
                $request->search,
                $request->trang_thai
            )
        );
    }

    public function getAll(Request $request)
    {
        return response()->json(
            $this->hoaDonService->getListAll()
        );
    }
    
    /*public function show($id)
    {
        return response()->json(
            $this->hoaDonService->getDetail($id)
        );
    }*/
    public function show($ma_hd)
    {
        $hoaDon = $this->hoaDonService->getDetail($ma_hd);
        $listTrangThai = $this->hoaDonService->getListTrangThai();

        return response()->json([
            'data' => $hoaDon,
            'listTrangThai' => $listTrangThai
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_khach_hang' => 'required|exists:khach_hang,id',
            'ten_nguoi_nhan' => 'required|string|max:255',
            'sdt_nguoi_nhan' => 'nullable|string|max:255',
            'dia_chi_hd' => 'nullable|string|max:255',
            'loai_hinh' => 'required|in:0,1',
            'trang_thai_thanh_toan' => 'required|in:0,1',
            'san_pham' => 'required|array|min:1',
            'san_pham.*.id_san_pham_chi_tiet' => 'required|exists:san_pham_chi_tiet,id',
            'san_pham.*.so_luong' => 'required|integer|min:1',
        ], [
            'id_khach_hang.required' => 'Vui lòng chọn khách hàng',
            'id_khach_hang.exists' => 'Khách hàng không tồn tại',
            'ten_nguoi_nhan.required' => 'Vui lòng nhập tên người nhận',
            'sdt_nguoi_nhan.required' => 'Vui lòng nhập số điện thoại',
            'sdt_nguoi_nhan.regex' => 'Số điện thoại không hợp lệ',
            'loai_hinh.required' => 'Vui lòng chọn loại hình',
            'trang_thai_thanh_toan.required'
                => 'Vui lòng chọn trạng thái thanh toán',
            'san_pham.required' => 'Vui lòng chọn sản phẩm',
            'san_pham.min' => 'Phải chọn ít nhất 1 sản phẩm',
        ]);

        $hoaDon = $this->hoaDonService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Thêm thành công',
            'data' => $hoaDon
        ]);
    }
    
    public function update(Request $request, $ma_hd)
    {
        $timHoaDon = $this->hoaDonService->getDetail($ma_hd);

        $data = $request->validate([
            'ten_nguoi_nhan'=>'required|string|max:255',
            'sdt_nguoi_nhan'=>'required|string',
            'dia_chi_hd'=>'required|max:255',
            'loai_hinh'=>'required|in:0,1',
            'trang_thai'=>'required|exists:trang_thai_hoa_don,id'
        ]);

        $hoaDon = $this->hoaDonService->update($timHoaDon->id, $data);

        return response()->json([
            'success'=>true,
            'message'=>'Cập nhật thành công',
            'data'=> $hoaDon
        ]);
    }

    public function destroy($ma_hd)
    {
        $this->hoaDonService->delete($ma_hd);

        return response()->json([
            'success' => true,
            'message' => 'Xóa thành công'
        ]);
    }

}