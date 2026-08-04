<!DOCTYPE html>
<html>
<head>
    <title>Lịch sử mua hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('css/QuanLy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ThongBao.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Load.css') }}">
</head>
</head>
<body>
<div class="container mt-4">
<h2>Chi tiết đơn hàng</h2>
    <div class="row">
        <div class="col-md-12">
            <h4>Trạng thái hóa đơn</h4>
            <div class="trang-thai-hd">

                <div class="trang-thai-ds @if($trangThaiMoi && $trangThaiMoi->ls_trang_thai == 1) active @endif">
                    <strong>Chờ xác nhận</strong>
                    @if($trangThaiMoi && $trangThaiMoi->ls_trang_thai == 1)
                        <p>{{ \Carbon\Carbon::parse($trangThaiMoi->thoi_gian_trang_thai)->format('H:i:s d/m/Y') }}</p>
                    @endif
                </div>

                <div class="trang-thai-ds @if($trangThaiMoi && $trangThaiMoi->ls_trang_thai == 2) active @endif">
                    <strong>Đã xác nhận</strong>
                    @if($trangThaiMoi && $trangThaiMoi->ls_trang_thai == 2)
                        <p>{{ \Carbon\Carbon::parse($trangThaiMoi->thoi_gian_trang_thai)->format('H:i:s d/m/Y') }}</p>
                    @endif
                </div>

                <div class="trang-thai-ds @if($trangThaiMoi && $trangThaiMoi->ls_trang_thai == 3) active @endif">
                    <strong>Đang giao hàng</strong>
                    @if($trangThaiMoi && $trangThaiMoi->ls_trang_thai == 3)
                        <p>{{ \Carbon\Carbon::parse($trangThaiMoi->thoi_gian_trang_thai)->format('H:i:s d/m/Y') }}</p>
                    @endif
                </div>

                <div class="trang-thai-ds @if($trangThaiMoi && $trangThaiMoi->ls_trang_thai == 4) active @endif">
                    <strong>Đã giao hàng</strong>
                    @if($trangThaiMoi && $trangThaiMoi->ls_trang_thai == 4)
                        <p>{{ \Carbon\Carbon::parse($trangThaiMoi->thoi_gian_trang_thai)->format('H:i:s d/m/Y') }}</p>
                    @endif
                </div>

                <div class="trang-thai-ds @if($trangThaiMoi && $trangThaiMoi->ls_trang_thai == 5) active @endif">
                    <strong>Hoàn thành</strong>
                    @if($trangThaiMoi && $trangThaiMoi->ls_trang_thai == 5)
                        <p>{{ \Carbon\Carbon::parse($trangThaiMoi->thoi_gian_trang_thai)->format('H:i:s d/m/Y') }}</p>
                    @endif
                </div>

                <div class="trang-thai-ds @if($trangThaiMoi && $trangThaiMoi->ls_trang_thai == 6) active @endif">
                    <strong>Đã hủy</strong>
                    @if($trangThaiMoi && $trangThaiMoi->ls_trang_thai == 6)
                        <p>{{ \Carbon\Carbon::parse($trangThaiMoi->thoi_gian_trang_thai)->format('H:i:s d/m/Y') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <hr>

    <h4>Thông tin mua hàng</h4>
    <table border="1" cellpadding="8">
        <tr>
            <th>Mã hóa đơn</th>
            <td>{{ $hoaDon->ma_hd }}</td>
        </tr>
        <tr>
            <th>Người mua</th>
            {{-- <td>{{ $hoaDon->ten_khach_hang }}</td> --}}
            <td>{{ $hoaDon->ten_nguoi_nhan }}</td>
        </tr>
        <tr>
            <th>SĐT người nhận</th>
            <td>{{ $hoaDon->sdt_nguoi_nhan ?? 'Lỗi' }}</td>
        </tr>
        <tr>
            <th>Địa chỉ</th>
            <td>{{ $hoaDon->dia_chi_hd }}</td>
        </tr>
        <tr>
            <th>Loại hình</th>
            <td>{{ $hoaDon->loai_hinh_ten }}</td>
        </tr>
        <tr>
            <th>Trạng thái</th>
            <td>{{ $hoaDon->trangthaihd->trang_thai }}</td>
        </tr>
        <tr>
            <th>Ngày tạo</th>
            <td>{{$hoaDon->ngayTaoDayDu() }}</td>
        </tr>
    </table>

    <hr>

    <h4>Danh sách sản phẩm</h4>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá bán</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @php $tong = 0; @endphp
            @foreach($hoaDonChiTiet as $i => $ct)
                @php
                    $thanhTien = $ct->gia_ban * $ct->so_luong;
                    $tong += $thanhTien;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><img src="{{ asset('storage/' . $ct->sanPhamChiTiet->anh_dai_dien) }}" class="anh-sp-upd" ></td>
                    <td>{{ $ct->ten_san_pham }}</td>
                    <td>{{ number_format($ct->gia_ban) }} đ</td>
                    <td>{{ $ct->so_luong }}</td>
                    <td>{{ number_format($thanhTien) }} đ</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <h4>Thanh toán</h4>
    <table border="1" cellpadding="8">
        <tr>
            <th>Tổng tiền gốc</th>
            <td>{{ number_format($hoaDon->tong_tien_goc) }} đ</td>
        </tr>
        <tr>
            <th>Giảm giá</th>
            <td>{{ number_format($hoaDon->giam_gia) }} {{ $hoaDon->loai_giam_gia_hd }}</td>
        </tr>
        <tr>
            <th>Tổng thanh toán</th>
            <td><b>{{ number_format($hoaDon->tong_tien_thuc) }} đ</b></td>
        </tr>
    </table>

    <br>
    <a href="{{ url('/danh-sach-ca-nhan') }}">Về menu cá nhân</a>



<div id="global-loader" class="loader-overlay">
    <div class="spinner"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
window.toastSuccess = @json(session('success'));
window.validationErrors = @json($errors->all());
</script>

<script src="{{ asset('js/Load.js') }}"></script>
<script src="{{ asset('js/ThongBao.js') }}"></script>   
</body>
</html>

