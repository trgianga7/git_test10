<?php

namespace App\Http\Controllers\WebController;

use App\Services\QuanLyDanhGia\QuanLyDanhGiaService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuanLyDanhGiaController extends Controller 
{

    protected QuanLyDanhGiaService $danhGiaService;

    public function __construct(QuanLyDanhGiaService $danhGiaService)
    {
        $this->danhGiaService = $danhGiaService;
    }

    public function index(Request $request)
    {
        $perPage = 5;

        $search = $request->input('search');

        $danhgia = $this->danhGiaService->getList($search, $perPage);

        return view('quan_ly_danh_gia.DanhGia', [
            'danhgia' => $danhgia,
            'showAdd' => false,
            'showEdit' => false,
            'search' => $search
        ]);
    }

    public function create()
    {
        $perPage = 5;

        $danhgia = $this->danhGiaService->getList(null, $perPage);
        return view('quan_ly_danh_gia.DanhGia', [
            'danhgia' => $danhgia,
            'showAdd' => true
        ]);
    }

    /*public function store(Request $request)
    {
        $data = $request->validate([
            
        ]);

        $this->danhGiaService->create($data);

        return redirect()->route('quan-ly-danh-gia.index')->with('success', 'Đã thêm danh mục!');
    }*/

    /*public function edit($id){
        $perPage = 5;

        $danhgia = $this->danhGiaService->getList(null, $perPage);

        return view('quan_ly_danh_gia.DanhGia', [
            'danhgia' => $danhgia,
            'danhMucFind' => $this->danhGiaService->getDetail($id),
            'showAdd' => false,
            'showEdit' => true
    ]);
    } */

    public function update($id)
    {
        $this->danhGiaService->update($id);

        return redirect()->route('quan-ly-danh-gia.index')->with('success', 'Đổi trạng thái thành công!');
    }

    public function destroy($id)
    {
        $danhGia = $this->danhGiaService->getDetail($id);
        $this->danhGiaService->delete($danhGia);
        return redirect()->route('quan-ly-danh-gia.index')->with('success', 'Đã xóa!');
    }
}
