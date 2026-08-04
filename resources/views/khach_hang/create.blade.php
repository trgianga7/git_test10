@extends('DanhSach')

@section('title', 'Thêm khách hàng')

@section('content')

<h2>Thêm khách hàng</h2> 

<div class="form-group">
    <label>Tên khách hàng</label>
    <input id="ten_khach_hang">
</div>

<div class="form-group">
    <label>Loại khách hàng</label>
    <select id="loai_khach_hang">
        <option value="">-- Chọn loại khách hàng --</option>
        <option value="1">Khách thường</option>
        <option value="2">Khách đặc biệt</option>
    </select>
</div>

<div class="form-group">
    <label>Số điện thoại</label>
    <input id="sdt">
</div>

<div class="form-group">
    <label>Mật khẩu</label>
    <input id="mat_khau" type="password">
</div>

<div class="div-button">
    <button onclick="save()">Lưu</button>
    <a href="/quan-ly/khach-hang" class="btn-back">Quay lại</a>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

    $('input').on('input', function () {
        $(this).removeClass('error');
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

    if(!validateField('#ten_khach_hang', 'Chưa nhập tên khách hàng')) return;
    if(!validateField('#loai_khach_hang', 'Chưa chọn loại khách hàng')) return;
    if(!validateField('#sdt', 'Chưa nhập số điện thoại')) return;
    if(!validateField('#mat_khau', 'Chưa nhập mật khẩu')) return;

    ajaxRequest({
        url: '/api/khach-hang',
        type: 'POST',
        showSuccess:false,

        data: {
            loai_khach_hang: $('#loai_khach_hang').val(),
            ten_khach_hang: $('#ten_khach_hang').val(),
            sdt: $('#sdt').val(),
            mat_khau: $('#mat_khau').val()
        },

        successCallback:function(res){

            redirectWithToast('/quan-ly/khach-hang',res.message);

        }
    });
} 
</script>