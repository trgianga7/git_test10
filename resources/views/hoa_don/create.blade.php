@extends('DanhSach')

@section('title', 'Thêm hóa đơn')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" href="{{ asset('css/HoaDon/HoaDon.css') }}">

<h2>Thêm hóa đơn</h2> 

<div class="create-wrapper">
    <div class="left-form">    
        <div class="form-group">
            <label>Khách hàng</label>
            <select name="id_khach_hang" id="id_khach_hang" class="select2">
                <option value=""></option>
            </select>
        </div>

        <div class="form-group">
            <label>Tên người nhận</label>
            <input id="ten_nguoi_nhan">
        </div>

        <div class="form-group">
            <label>Số điện thoại người nhận</label>
            <input id="sdt_nguoi_nhan">
        </div>

        <div class="form-group">
            <label>Địa chỉ nhận hàng</label>
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
            <label>Trạng thái thanh toán</label>

            <div class="radio-group">
                <label class="radio-item">
                    <input type="radio" name="trang_thai_thanh_toan" value="1">
                    <span>Đã thanh toán</span>
                </label>

                <label class="radio-item">
                    <input type="radio" name="trang_thai_thanh_toan" value="0">
                    <span>Chưa thanh toán</span>
                </label>
            </div>
        </div>
    
        <div class="div-button">
            <button onclick="save()">Lưu</button>
            <a href="/quan-ly/hoa-don" class="btn-back">Quay lại</a>
        </div>
    </div>

    <div class="right-them-sp">
        <div class="header-sp">
            <h3>Danh sách sản phẩm</h3>
            <button type="button" onclick="openModal()">+ Thêm sản phẩm</button>
        </div>

        <div class="table-selected-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Tên Sản phẩm</th>
                        <th>Ảnh</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody id="ds-san-pham"></tbody>
            </table>
        </div>

        <div class="tong-tien-box">
        <div>Tổng tiền: <span id="tong_tien">0</span> đ</div>
        <div>Giảm giá: <span id="giam_gia">0</span> đ</div>
        <div class="thanh-toan">
            Tổng thanh toán: <span id="tong_thuc">0</span> đ
        </div>
    </div>

    </div>
    
</div>

<div id="imageModal" class="modal-hoa-don">
    <div class="modal-content" style="width: 800px;">

        <div class="modal-header">
            <h3>Chọn sản phẩm</h3>
            <button type="button" class="modal-close" onclick="closeModal()">×</button>
        </div>

        <div class="modal-body">

            <!-- Search -->
            <input id="search_sp" placeholder="Tìm sản phẩm..." style="width:100%; margin-bottom:10px; padding:6px;">

            <!-- Bảng SPCT -->
            <div style="max-height:400px; overflow:auto;">
                <table class="table-spct">
                    <thead>
                        <tr>
                            <th>Chọn</th>
                            <th>Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Số lượng</th>
                        </tr>
                    </thead>

                    <tbody id="ds-spct"></tbody>
                </table>
            </div>

        </div>

        <div class="modal-footer">
            <button class="btn-add" onclick="addNhieuSanPham()">Thêm vào hóa đơn</button>
            <button class="btn-cancel" onclick="closeModal()">Hủy</button>
        </div>

    </div>
</div>

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

function openModal(){
    document.getElementById('imageModal').classList.add('active');

    if(allSPCT.length > 0){
        renderSPCT(allSPCT);
    }
}

function closeModal(){
    document.getElementById('imageModal').classList.remove('active');
}

$(document).ready(function () {

    $('#id_khach_hang').select2({
        //allowClear: true,
        //placeholder: '-- Chọn khách hàng --',
        width: '100%'
    });

    ajaxRequest({

        url:'/api/khach-hang/all',

        loading:false,
        showSuccess:false,

        successCallback:function(res){

            let html = '<option value="">-- Chọn khách hàng --</option>';

            res.forEach(kh => {
                html += `
                    <option value="${kh.id}">
                        ${kh.ten_khach_hang}
                    </option>
                `;
            });

            $('#id_khach_hang').html(html);
        }
    });

    $('input').on('input', function () {
        $(this).removeClass('error');
    });

    $('.select2').on('change', function () {
        $(this).next('.select2-container').removeClass('error');
    });

    modalThemSp();
});

function validateField(selector, message){

    let value = $(selector).val();

    if(!value || !value.trim()){

        if($(selector).hasClass('select2')){
            $(selector).next('.select2-container').addClass('error');
        }else{
            $(selector).addClass('error');
        }

        showToast(message, 'warning');

        return false;
    }

    return true;
}

function renderSanPham() {
    let html = '';
    let tongTien = 0;

    dsSanPham.forEach((sp, index) => {
        let thanhTien = sp.gia * sp.so_luong;
        tongTien += thanhTien;

        html += `
            <tr>
                <td>${sp.ten}</td>
                <td>
                    <img src="/storage/${sp.anh}"
                         width="50"
                         height="50"
                         style="object-fit:cover;border-radius:6px;">
                </td>
                <td>${Number(sp.gia).toLocaleString()}</td>
                <td>${sp.so_luong}</td>
                <td>
                    <button class="xoa-san-pham-chon" onclick="xoaSanPham(${index})">X</button>
                </td>
            </tr>
        `;
    });

    $('#ds-san-pham').html(html);

    let giamGia = 0;
    let tongThuc = tongTien - giamGia;

    $('#tong_tien').text(tongTien.toLocaleString());
    $('#giam_gia').text(giamGia.toLocaleString());
    $('#tong_thuc').text(tongThuc.toLocaleString());
}

function xoaSanPham(index) {
    dsSanPham.splice(index, 1);
    renderSanPham();
}

let dsSanPham = [];

function save() {
    $('.error').removeClass('error');

    if (!validateField('#id_khach_hang', 'Vui lòng chọn khách hàng')) return;
    if (!validateField('#ten_nguoi_nhan', 'Vui lòng nhập tên người nhận')) return;
    if (!validateField('#sdt_nguoi_nhan', 'Vui lòng nhập số điện thoại người nhận')) return;
    if (!validateField('#dia_chi_hd', 'Vui lòng nhập địa chỉ nhận hàng')) return;

    let loaiHinh = $('input[name="loai_hinh"]:checked').val();
    let trangThaiThanhToan = $('input[name="trang_thai_thanh_toan"]:checked').val();

    if (loaiHinh === undefined) {
        alert('Vui lòng chọn loại hình');
        return;
    }

    if (trangThaiThanhToan === undefined) {
        alert('Vui lòng chọn trạng thái thanh toán');
        return;
    }

    if (dsSanPham.length === 0) {
        alert('Vui lòng chọn ít nhất 1 sản phẩm');
        return;
    }

    let formData = new FormData();

    formData.append('id_khach_hang', $('#id_khach_hang').val());
    formData.append('ten_nguoi_nhan', $('#ten_nguoi_nhan').val());
    formData.append('sdt_nguoi_nhan', $('#sdt_nguoi_nhan').val());
    formData.append('dia_chi_hd', $('#dia_chi_hd').val());
    formData.append('loai_hinh', loaiHinh);
    formData.append('trang_thai_thanh_toan', trangThaiThanhToan);

    formData.append('tong_tien', $('#tong_tien').text().replace(/\./g,''));
    formData.append('giam_gia', $('#giam_gia').text().replace(/\./g,''));
    formData.append('tong_thuc', $('#tong_thuc').text().replace(/\./g,''));

    dsSanPham.forEach((sp, index) => {
        formData.append(`san_pham[${index}][id_san_pham_chi_tiet]`, sp.id_san_pham_chi_tiet);
        formData.append(`san_pham[${index}][so_luong]`, sp.so_luong);
        formData.append(`san_pham[${index}][gia]`, sp.gia);
    });

    ajaxRequest({
        url: '/api/hoa-don',
        type: 'POST',
        showSuccess:false,

        data: formData,

        processData: false,
        contentType: false,

        successCallback:function(res){

            redirectWithToast('/quan-ly/hoa-don',res.message);

        },

        /*error: function(err) {
            //console.log(err.responseText);

            if(err.responseJSON?.message){
                alert(err.responseJSON.message);
            }else{
                alert('Có lỗi xảy ra');
            }
        }*/
    });
}

//Modal thêm sản phẩm
/*let allSPCT = [];

$.get('/api/san-pham-chi-tiet/all', function(res){
    //console.log("SPCT API:", res);
    allSPCT = res;
    renderSPCT(res);
});*/

//Modal thêm sản phẩm
let allSPCT = [];

function modalThemSp(){
    ajaxRequest({

        url:'/api/san-pham-chi-tiet/all',

        loading:false,
        showSuccess:false,

        successCallback:function(res){

            allSPCT = res;

            renderSPCT(res);
        }
    });
}

function renderSPCT(data){
    let html = '';

    data.forEach(sp => {

        let gia = sp.gia_khuyen_mai ?? sp.gia_ban;
        let disabled = sp.so_luong <= 0 ? 'disabled' : '';
        //let rowClass = sp.so_luong <= 0 ? 'style="opacity:0.5"' : '';
        let anh = sp.anh_dai_dien? `/storage/${sp.anh_dai_dien}`: '';

        html += `
            <tr>
                <td>
                    <input type="checkbox" class="chk-sp" value="${sp.id}">
                </td>
                <td>
                    <img src="${anh}" width="50" height="50" 
                        style="object-fit:cover;border-radius:6px;">
                </td>
                <td>${sp.sanpham.ten_san_pham} - ${sp.ten_phu}</td>
                <td>${Number(gia).toLocaleString('vi-VN')}</td>
                <td>${sp.so_luong}</td>
                <td>
                    <input
                        type="number"
                        class="qty"
                        min="1"
                        max="${sp.so_luong}"
                        value="1"
                        ${disabled}>
                </td>
            </tr>
        `;
    });

    $('#ds-spct').html(html);
}

//tìm kiếm realtime
$(document).on('input', '#search_sp', function () {
    let key = $(this).val().toLowerCase().trim();

    let filtered = allSPCT.filter(sp =>
        (sp.sanpham.ten_san_pham + ' ' + sp.ten_phu).toLowerCase().includes(key)
    );

    renderSPCT(filtered);
});

//Thêm nhiều
function addNhieuSanPham(){

$('.chk-sp:checked').each(function(){

    let id = $(this).val();
    let row = $(this).closest('tr');

    let qtyInput = row.find('.qty').val();
    let qty = parseInt(qtyInput);
    if (!qty || qty < 1) {
        qty = 1;
    }

    let sp = allSPCT.find(x => x.id == id);

    if(!sp) return;

    let tonTai = dsSanPham.find(x => x.id_san_pham_chi_tiet == id);

    if(tonTai){

        let tong = tonTai.so_luong + qty;

        if(tong > sp.so_luong){
            showToast(
                `Sản phẩm ${sp.ten_phu} vượt tồn kho`,
                'warning'
            );
            return;
        }

        tonTai.so_luong = tong;

    }else{
        dsSanPham.push({
            id_san_pham_chi_tiet: id,
            ten: sp.sanpham.ten_san_pham + ' - ' + sp.ten_phu,
            gia: sp.gia_khuyen_mai ?? sp.gia_ban,
            so_luong: qty,
            anh: sp.anh_dai_dien
        });
    }
});

renderSanPham();
closeModal();
}

</script>

@endsection