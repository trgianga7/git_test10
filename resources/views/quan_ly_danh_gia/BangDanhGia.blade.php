<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Mã đánh giá</th>
        <th>Đánh giá</th>
        <th>Nội dung</th>
        <th>Người đánh giá</th>
        <th>Ngày đánh giá</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
    </tr>
    @foreach($danhgia as $dg)
    <tr>
        <td>{{ $dg->id }}</td>
        <td>{{ $dg->sanPhamChiTiet->sanPham->ten_san_pham ?? ''}}</td>
        <td>{{ $dg->ma_danh_gia ?? ''}}</td>
        <td>{{ $dg->danh_gia ?? ''}}</td>
        <td>{{ $dg->noi_dung ?? ''}}</td>
        <td>{{ $dg->khachHang->ten_khach_hang ?? ''}}</td>
        <td>{{ $dg->thoi_gian_danh_gia }}</td>
        <td>{{ $dg->trang_thai ? 'Hiển thị' : 'Vô hiệu hóa' }}</td>
        <td>
            <a href="#"
               onclick="event.preventDefault(); if(confirm('Bạn có chắc muốn đổi trạng thái ?')) {
                   document.getElementById('put-form-{{ $dg->id }}').submit();
               }">
               Đổi trạng thái
            </a>
            <form id="put-form-{{ $dg->id }}" action="{{ route('quan-ly-danh-gia.update', $dg->id) }}" method="POST" style="display:none;">
                @csrf
                @method('PUT')
            </form>
               |
            <a href="#"
               onclick="event.preventDefault(); if(confirm('Bạn có chắc muốn xóa?')) {
                   document.getElementById('delete-form-{{ $dg->id }}').submit();
               }">
               Xóa
            </a>
            <form id="delete-form-{{ $dg->id }}" action="{{ route('quan-ly-danh-gia.destroy', $dg->id) }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        </td>
    </tr>
    @endforeach
</table>

<!-- Phân trang -->
@include('partials.phan_trang', ['paginator' => $danhgia])


