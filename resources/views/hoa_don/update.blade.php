@extends('DanhSach')

@section('title', 'Cập nhật hóa đơn')

@section('content')

<link rel="stylesheet" href="{{ asset('css/HoaDon/HoaDon.css') }}">

<h2>Cập nhật hóa đơn</h2>

<input type="hidden" id="ma_hd" value="{{ $ma_hd }}">

<div class="trang-thai-hd" id="timeline"></div>

<div class="create-wrapper">
    <div class="left-form">

        <div class="form-group">
            <label>Tên người nhận</label>
            <input id="ten_nguoi_nhan">
        </div>

        <div class="form-group">
            <label>SĐT người nhận</label>
            <input id="sdt_nguoi_nhan">
        </div>

        <div class="form-group">
            <label>Địa chỉ</label>
            <input id="dia_chi_hd">
        </div>

        <div class="form-group">
            <label>Loại hình</label>

            <div class="radio-group">
                <label class="radio-item">
                    <input type="radio" name="loai_hinh" value="0">
                    <span>Thanh toán khi nhận</span>
                </label>

                <label class="radio-item">
                    <input type="radio" name="loai_hinh" value="1">
                    <span>Thanh toán số dư</span>
                </label>

                <label class="radio-item">
                    <input type="radio" name="loai_hinh" value="2">
                    <span>Thanh toán chuyển khoản</span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label>Trạng thái</label>
            <select id="trang_thai"></select>
        </div>

        <div class="div-button">
            <button onclick="updateHoaDon()">Cập nhật</button>
            <a href="/quan-ly/hoa-don" class="btn-back">Quay lại</a>
        </div>

    </div>

    <div class="right-form">

        <h4>Sản phẩm đã mua</h4>

        <div id="ds-san-pham-da-mua"></div>

        <div class="box-tong-tien">
            <div class="dong-tien">
                <span>Tổng tiền gốc</span>
                <span id="tong_goc">0 đ</span>
            </div>

            <div class="dong-tien giam-gia">
                <span>Giảm giá</span>
                <span id="giam_gia">0 đ</span>
            </div>

            <div class="line"></div>

            <div class="dong-tien tong-thanh-toan">
                <span>Thanh toán</span>
                <span id="tong_thuc">0 đ</span>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){

    let ma_hd = $('#ma_hd').val();

    ajaxRequest({

        url:'/api/hoa-don/' + ma_hd,

        loading:false,
        showSuccess:false,

        successCallback:function(res){

            let hd = res.data;

            $('#ten_nguoi_nhan').val(hd.ten_nguoi_nhan);
            $('#sdt_nguoi_nhan').val(hd.sdt_nguoi_nhan);
            $('#dia_chi_hd').val(hd.dia_chi_hd);

            $('input[name="loai_hinh"][value="'+hd.loai_hinh+'"]').prop('checked', true);

            let html = '';

            res.listTrangThai.forEach(tt => {
                html += `
                    <option value="${tt.id}"
                        ${tt.id == hd.trang_thai ? 'selected' : ''}>
                        ${tt.trang_thai}
                    </option>
                `;
            });

            $('#trang_thai').html(html);

            renderTimeline(hd.trang_thai, hd.thoi_gian_trang_thai);
            renderSanPhamDaMua(hd.chi_tiets);
            renderTongTien(hd);
        }
    });
});


function updateHoaDon(){

    let ma_hd = $('#ma_hd').val();

    ajaxRequest({
        url:'/api/hoa-don/' + ma_hd,
        type:'PUT',
        showSuccess:false,

        data:{
            ten_nguoi_nhan: $('#ten_nguoi_nhan').val(),
            sdt_nguoi_nhan: $('#sdt_nguoi_nhan').val(),
            dia_chi_hd: $('#dia_chi_hd').val(),
            loai_hinh: $('input[name="loai_hinh"]:checked').val(),
            trang_thai: $('#trang_thai').val()
        },

        successCallback:function(res){

            redirectWithToast('/quan-ly/hoa-don',res.message);

        },

        error:function(err){

            if(err.status == 422){
                let msg =
                    Object.values(err.responseJSON.errors)[0][0];
                alert(msg);
            }
        }
        
    });
}


function renderTimeline(currentStatus, thoiGianTrangThai) {

    thoiGianTrangThai = thoiGianTrangThai || [];

    // lấy bản ghi mới nhất theo từng trạng thái
    let latestMap = {};

    thoiGianTrangThai.forEach(item => {
        let key = item.ls_trang_thai;

        if (
            !latestMap[key] ||
            new Date(item.thoi_gian_trang_thai) >
            new Date(latestMap[key].thoi_gian_trang_thai)
        ) {
            latestMap[key] = item;
        }
    });

    let list = [
        {id: 1, text: 'Chờ xác nhận'},
        {id: 2, text: 'Đã xác nhận'},
        {id: 3, text: 'Đang giao hàng'},
        {id: 4, text: 'Đã giao hàng'},
        {id: 5, text: 'Hoàn thành'},
        {id: 6, text: 'Đã hủy'}
    ];

    let html = '';

    list.forEach(item => {

        let active = currentStatus == item.id ? 'active' : '';

        let found = latestMap[item.id];

        let tg = found
            ? `<p>${formatDate(found.thoi_gian_trang_thai)}</p>`
            : '';

        html += `
            <div class="trang-thai-ds ${active}">
                <strong>${item.text}</strong>
                ${tg}
            </div>
        `;
    });

    $('#timeline').html(html);
}


function formatDate(date){
    return new Date(date)
        .toLocaleString('vi-VN');
}

function renderSanPhamDaMua(ds){

let html = '';

ds.forEach(sp => {
    html += `
        <div class="product-row">

            <img src="/storage/${sp.san_pham_chi_tiet.anh_dai_dien}"
                 class="anh-sp-upd">

            <div class="thong-tin-sp-upd">
                <div class="ten-sp-upd">
                    ${sp.ten_san_pham}
                </div>

                <div class="thuoc-tinh-sp-upd">
                    <span>Giá: ${Number(sp.gia_ban).toLocaleString()} đ</span>
                    <span>Số lượng: ${sp.so_luong}</span>
                    <span class="text-danger">
                        Tổng: ${Number(sp.tong_tien_hd).toLocaleString()} đ
                    </span>
                </div>
            </div>

        </div>
    `;
});

$('#ds-san-pham-da-mua').html(html);
}

function renderTongTien(hd){
    $('#tong_goc').text(
        Number(hd.tong_tien_goc).toLocaleString() + ' đ'
    );

    $('#giam_gia').text(
        Number(hd.giam_gia).toLocaleString() + ' đ'
    );

    $('#tong_thuc').text(
        Number(hd.tong_tien_thuc).toLocaleString() + ' đ'
    );
}
</script>

@endsection