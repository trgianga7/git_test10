@extends('DanhSach')

@section('title', 'Thêm sản phẩm')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<h2>Thêm sản phẩm</h2> 

<div class="form-group">
    <label>Tên sản phẩm</label>
    <input id="ten_san_pham">
</div>

<div class="form-group">
    <label>Danh mục</label>
    <select id="id_danh_muc" class="select2">
        <option value="">-- Chọn danh mục --</option>
    </select>
</div>

<div class="div-button">
    <button onclick="save()">Lưu</button>
    <a href="/quan-ly/san-pham" class="btn-back">Quay lại</a>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    $('.select2').select2({
        allowClear: true,
        width: '100%'
    });

    ajaxRequest({

        url:'/api/danh-muc/all',

        loading:false,
        showSuccess:false,

        successCallback:function(res){

            let html = '<option value="">-- Chọn danh mục --</option>';

            res.forEach(dm => {
                html += `
                    <option value="${dm.id}">
                        ${dm.ten_danh_muc}
                    </option>
                `;
            });

            $('#id_danh_muc').html(html);
        }
    });

    $('input').on('input', function () {
        $(this).removeClass('error');
    });

    $('.select2').on('change', function () {
        $(this).next('.select2-container').removeClass('error');
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

    if(!validateField('#ten_san_pham', 'Chưa nhập tên sản phẩm')) return;
    if(!validateField('#id_danh_muc', 'Chưa chọn danh mục')) return;

    ajaxRequest({
        url: '/api/san-pham',
        type: 'POST',
        showSuccess:false,

        data: {
            id_danh_muc: $('#id_danh_muc').val(),
            ten_san_pham: $('#ten_san_pham').val(),
        },

        successCallback:function(res){

            redirectWithToast('/quan-ly/san-pham',res.message);

        }
    });
} 
</script>