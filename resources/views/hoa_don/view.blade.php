@extends('DanhSach')

@section('title', 'Chi tiết hóa đơn')

@section('content')

<link rel="stylesheet" href="{{ asset('css/HoaDon/HoaDon.css') }}">
<link rel="stylesheet" href="{{ asset('css/HoaDon/ViewHoaDon.css') }}">

<h2>Chi tiết hóa đơn</h2>

<input type="hidden" id="ma_hd_value" value="{{ $ma_hd }}">

<div class="invoice-view">

    <div class="invoice-header">
        <div>
            <h3 id="ma_hd_text"></h3>
            <p id="ngay_tao"></p>
        </div>

        <div>
            <a href="/quan-ly/hoa-don" class="btn-back">Quay lại</a>
            <button onclick="window.print()">In hóa đơn</button>
        </div>
    </div>

    <div class="info-grid">

        <div class="info-box">
            <h4>Thông tin người nhận</h4>
            <p><b>Tên:</b> <span id="ten_nguoi_nhan"></span></p>
            <p><b>SĐT:</b> <span id="sdt_nguoi_nhan"></span></p>
            <p><b>Địa chỉ:</b> <span id="dia_chi_hd"></span></p>
        </div>

        <div class="info-box">
            <h4>Thông tin thanh toán</h4>
            <p><b>Loại hình:</b> <span id="loai_hinh"></span></p>
            <p><b>TT thanh toán:</b> <span id="tt_thanhtoan"></span></p>
            <p><b>Trạng thái:</b> <span id="trang_thai"></span></p>
        </div>

    </div>

    <h4>Lịch sử trạng thái</h4>
    <div id="timeline" class="trang-thai-hd"></div>

    <h4>Sản phẩm</h4>

    <table class="table">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên Sản phẩm</th>
                <th>Giá</th>
                <th>SL</th>
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody id="ds_san_pham"></tbody>
    </table>

    <div class="box-tong-tien">
        <div class="dong-tien">
            <span>Tổng tiền gốc</span>
            <span id="tong_goc"></span>
        </div>

        <div class="dong-tien">
            <span>Giảm giá</span>
            <span id="giam_gia"></span>
        </div>

        <div class="line"></div>

        <div class="dong-tien tong-thanh-toan">
            <span>Thanh toán</span>
            <span id="tong_thuc"></span>
        </div>
    </div>

</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function(){

    let ma_hd = $('#ma_hd_value').val();

    $.get('/api/hoa-don/' + ma_hd,function(res){

        let hd = res.data;
        const loaiHinh = {
            0: 'Thanh toán khi nhận',
            1: 'Thanh toán số dư',
            2: 'Thanh toán chuyển khoản'
        };

        $('#ma_hd_text').text('Mã hóa đơn: ' + hd.ma_hd);
        //console.log(hd.ngay_tao);
        $('#ngay_tao').text('Ngày tạo: ' + formatDate(hd.ngay_tao));

        $('#ten_nguoi_nhan').text(hd.ten_nguoi_nhan);
        $('#sdt_nguoi_nhan').text(hd.sdt_nguoi_nhan);
        $('#dia_chi_hd').text(hd.dia_chi_hd);

        $('#loai_hinh').text(loaiHinh[hd.loai_hinh] ?? 'Không xác định');

        $('#tt_thanhtoan').text(
            hd.trang_thai_thanh_toan == 1
            ? 'Đã thanh toán'
            : 'Chưa thanh toán'
        );

        let tt = res.listTrangThai.find(
            x => x.id == hd.trang_thai
        );

        $('#trang_thai').text(tt.trang_thai);

        renderTimeline(
            hd.trang_thai,
            hd.thoi_gian_trang_thai
        );

        renderSanPham(hd.chi_tiets);

        $('#tong_goc').text(Number(hd.tong_tien_goc).toLocaleString()+' đ');

        $('#giam_gia').text(Number(hd.giam_gia).toLocaleString()+' đ');

        $('#tong_thuc').text(Number(hd.tong_tien_thuc).toLocaleString()+' đ');

    });
});

function renderSanPham(ds){

    let html='';

    ds.forEach(sp=>{
        html += `
            <tr>
                <td>
                    <img src="/storage/${sp.san_pham_chi_tiet.anh_dai_dien}"
                         width="50" height="50">
                </td>
                <td>${sp.ten_san_pham}</td>
                <td>${Number(sp.gia_ban).toLocaleString()} đ</td>
                <td>${sp.so_luong}</td>
                <td>${Number(sp.tong_tien_hd).toLocaleString()} đ</td>
            </tr>
        `;
    });

    $('#ds_san_pham').html(html);
}

function renderTimeline(currentStatus, list){

    let map = {};

    list.forEach(x=>{
        if(!map[x.ls_trang_thai]
            || new Date(x.thoi_gian_trang_thai)
            > new Date(map[x.ls_trang_thai].thoi_gian_trang_thai)){
            map[x.ls_trang_thai]=x;
        }
    });

    let status = [
        {id:1,text:'Chờ xác nhận'},
        {id:2,text:'Đã xác nhận'},
        {id:3,text:'Đang giao'},
        {id:4,text:'Đã giao'},
        {id:5,text:'Hoàn thành'},
        {id:6,text:'Đã hủy'}
    ];

    let html='';

    status.forEach(s=>{
        html += `
            <div class="trang-thai-ds
                ${currentStatus==s.id?'active':''}">
                <strong>${s.text}</strong>
                <p>${
                    map[s.id]
                    ? formatDate(map[s.id].thoi_gian_trang_thai)
                    : ''
                }</p>
            </div>
        `;
    });

    $('#timeline').html(html);
}

function formatDate(date){
    return new Date(date).toLocaleString('vi-VN');
}
</script>