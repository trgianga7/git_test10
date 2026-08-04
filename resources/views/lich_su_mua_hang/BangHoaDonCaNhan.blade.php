@php 
    use Illuminate\Support\Str; 
@endphp

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Mã HĐ</th>
        <th>Địa chỉ HĐ</th>
        <th>Tổng tiền</th>
        <th>Trạng thái</th>
        <th>Ngày tạo</th>
        <th>Hành động</th>
    </tr>

    @foreach($dsDonHang as $hd)
        <tr>
            <td>{{ $hd->id }}</td>
            <td>{{ $hd->ma_hd ?? '' }}</td>
            <td>{{ Str::limit($hd->dia_chi_hd, 25, '...') ?? '' }}</td>
            <td>{{ $hd->tong_tien_thuc }} đ</td>
            <td>{{ $hd->trangthaihd->trang_thai }}</td>
            <td>{{ $hd->ngay_tao }}</td>
            <td>
                <a href="{{ route('lich-su-don-hang.xemChiTiet', $hd->id) }}">Xem</a>
            </td>
        </tr>
    @endforeach
</table>

<!-- Phân trang -->
@include('partials.phan_trang', ['paginator' => $dsDonHang])