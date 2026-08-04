@extends('DanhSach')

@section('title', 'Thêm mã giảm giá')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" >
<link rel="stylesheet" href="{{ asset('css/GiamGia/GiamGia.css') }}">

<h2>Thêm mã giảm giá</h2> 

<div class="form-group">
    <label>Tên giảm giá</label>
    <input id="ten_giam_gia">
</div>

<div class="form-group">
    <label>Loại giảm giá</label>
    <select id="loai_giam_gia">
        <option value="0">Giảm giá cố định</option>
        <option value="1">Giảm giá theo phần trăm</option>
    </select>
</div>

<div class="form-group">
    <label>Mã giảm giá</label>
    <input id="ma_giam_gia">
</div>

<div class="form-group">
    <label>Giá trị</label>
    <input id="gia_tri" type="number">
</div>

<div class="form-group">
    <label>Số lượng</label>
    <input id="so_luong" type="number">
</div>

<div class="form-group">
    <label>Ngày bắt đầu</label>
    <input name="ngay_bat_dau" id="ngay_bat_dau">
</div>

<div class="form-group">
    <label>Ngày hết hạn</label>
    <input name="ngay_het_han" id="ngay_het_han">
</div>

<div class="div-button">
    <button onclick="save()">Lưu</button>
    <a href="/quan-ly/giam-gia" class="btn-back">Quay lại</a>
</div>

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>

<script>

$(document).ready(function () {

    $('input').on('input', function () {
        $(this).removeClass('error');
    });

    $('.select2').on('change', function () {
        $(this).next('.select2-container').removeClass('error');
    });

    flatpickr("#ngay_bat_dau", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        locale: "vn",
        time_24hr: true,
    });
    flatpickr("#ngay_het_han", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        locale: "vn",
        time_24hr: true,
    });
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

function save()
{
    $('.error').removeClass('error');

    if(!validateField('#ten_giam_gia', 'Chưa nhập tên giảm giá')) return;
    if(!validateField('#loai_giam_gia', 'Chưa chọn loại giảm giá')) return;
    if(!validateField('#ma_giam_gia', 'Chưa nhập mã giảm giá')) return;
    if(!validateField('#gia_tri', 'Chưa nhập giá trị giảm giá')) return;
    if(!validateField('#so_luong', 'Chưa nhập số lượng')) return;
    if(!validateField('#ngay_bat_dau', 'Chưa chọn ngày bắt đầu')) return;
    if(!validateField('#ngay_het_han', 'Chưa chọn ngày hết hạn')) return;

    ajaxRequest({

        url:'/api/giam-gia',
        type:'POST',

        showSuccess:false,

        data: {
            ten_giam_gia: $('#ten_giam_gia').val().trim(),
            loai_giam_gia: $('#loai_giam_gia').val(),
            ma_giam_gia: $('#ma_giam_gia').val().trim(),
            gia_tri: $('#gia_tri').val(),
            so_luong: $('#so_luong').val(),
            ngay_bat_dau: $('#ngay_bat_dau').val(),
            ngay_het_han: $('#ngay_het_han').val()
        },

        successCallback:function(res){

            redirectWithToast('/quan-ly/giam-gia',res.message);

        }
    });
}

</script>

@endsection