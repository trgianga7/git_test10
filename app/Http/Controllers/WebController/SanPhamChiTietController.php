<?php

namespace App\Http\Controllers\WebController;

use App\Services\SanPhamChiTiet\SanPhamChiTietService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SanPhamChiTietController extends Controller
{

    protected SanPhamChiTietService $sanPhamChiTietService;

    public function __construct(SanPhamChiTietService $sanPhamChiTietService)
    {
        $this->sanPhamChiTietService = $sanPhamChiTietService;
    }

    public function view()
    {
        return view('san_pham_chi_tiet.layout');
    }

    public function create()
    {
        return view('san_pham_chi_tiet.create');
    }

    public function edit($ma_sp)
    {
        return view('san_pham_chi_tiet.update', compact('ma_sp'));
    }

    /*
    public function index(Request $request)
    {
        $perPage = 5;

        $search = $request->input('search');

        $sanphamct = $this->sanPhamChiTietService->getList($search, $perPage);
        $sanphams = $this->sanPhamChiTietService->getSanPham();

        return view('san_pham_chi_tiet.SanPhamChiTiet', [
            'sanphamct' => $sanphamct,
            'sanphams' => $sanphams,
            'showAdd' => false,
            'showEdit' => false,
            'search' => $search
        ]);
    }

    public function create()
    {
        $perPage = 5;

        $sanphamct = $this->sanPhamChiTietService->getList(null, $perPage);
        $sanphams = $this->sanPhamChiTietService->getSanPham();
        return view('san_pham_chi_tiet.SanPhamChiTiet', [
            'sanphamct' => $sanphamct,
            'sanphams' => $sanphams,
            'showAdd' => true
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_san_pham' => 'required|integer',
            'images' => 'nullable|array|max:4',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'ten_phu' => 'required|string',
            'mo_ta' => 'nullable|string',
            'gia_goc' => 'required|integer',
            'gia_ban' => 'required|integer',
            'so_luong' => 'required|integer',
        ]);

        $this->sanPhamChiTietService->create($data);

        return redirect()->route('san-pham-chi-tiet.index')->with('success', 'Đã thêm sản phẩm chi tiết!');
    }

    public function edit($id){
        $perPage = 5;

        $sanphamct = $this->sanPhamChiTietService->getList(null, $perPage);
        $sanphams = $this->sanPhamChiTietService->getSanPham();

        return view('san_pham_chi_tiet.SanPhamChiTiet', [
            'sanphamct' => $sanphamct,
            'sanphams' => $sanphams,
            'sanPhamCtFind' => $this->sanPhamChiTietService->getDetail($id),
            'showAdd' => false,
            'showEdit' => true
    ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'id_san_pham' => 'required|integer',
            //'ma_sp' => 'required|string|unique:san_pham_chi_tiet,ma_sp,' .$id,
            'images' => 'nullable|array|max:4',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'ten_phu' => 'required|string', 
            'mo_ta' => 'nullable|string',
            'gia_goc' => 'required|integer',
            'gia_ban' => 'required|integer',
            'so_luong' => 'required|integer',
            'trang_thai' => 'required|in:0,1',
            //'anh_sp' => 'nullable|image|mimes:jpg,jpeg,png,webp'
            'anh_dai_dien_index' => 'nullable|integer|min:0|max:3',
        ],
        [
            'images.max' => 'Chỉ có thể lưu tối đa 4 ảnh',
            'images.*.mimes' => 'Tệp được chọn phải là ảnh',
            'images.*.max' => 'Ảnh không được vượt quá 2MB',
            'images.*.image' => '123',
        ]
        );

        $data['images'] = $request->file('images') ?? [];

        $this->sanPhamChiTietService->update($id, $data);

        return redirect()->route('san-pham-chi-tiet.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $sanPhamCt = $this->sanPhamChiTietService->getDetail($id);
        $this->sanPhamChiTietService->delete($sanPhamCt);
        
        return redirect()->route('san-pham-chi-tiet.index')->with('success', 'Đã xóa!');
    }
    */
}
