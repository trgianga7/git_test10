@extends('DanhSach')

@section('title', 'Thêm người dùng')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<h2>Thêm người dùng</h2> 

<div class="form-group">
    <label>Tên người dùng</label>
    <input id="ten_nguoi_dung">
</div>

<div class="form-group">
    <label>Chức vụ</label>
    <select id="id_chuc_vu" class="select2">
        <option value="">-- Chọn chức vụ --</option>
    </select>
</div>

<div class="form-group">
    <label>Email</label>
    <input id="email">
</div>

<div class="form-group">
    <label>Mật khẩu</label>
    <input id="mat_khau" type="password">
</div>

<div class="form-group">
    <label>SĐT (nếu có)</label>
    <input id="sdt_lien_he">
</div>

<div class="div-button">
    <button onclick="save()">Lưu</button>
    <a href="/quan-ly/nguoi-dung" class="btn-back">Quay lại</a>
</div>

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

$(document).ready(function () {

    $('.select2').select2({
        //allowClear: true,
        placeholder: '-- Chọn chức vụ --',
        width: '100%'
    });

    ajaxRequest({

        url:'/api/chuc-vu/all',

        loading:false,
        showSuccess:false,

        successCallback:function(res){

            let html = '<option value="">-- Chọn chức vụ --</option>';

            res.forEach(cv => {

                html += `
                    <option value="${cv.id}">
                        ${cv.ten_chuc_vu}
                    </option>
                `;
            });

            $('#id_chuc_vu').html(html);

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

    if(!validateField('#ten_nguoi_dung', 'Chưa nhập tên người dùng')) return;
    if(!validateField('#id_chuc_vu', 'Chưa chọn chức vụ')) return;
    if(!validateField('#email', 'Chưa nhập email')) return;
    if(!validateField('#mat_khau', 'Chưa nhập mật khẩu')) return;

    ajaxRequest({

        url:'/api/nguoi-dung',
        type:'POST',

        showSuccess:false,

        data: {
            id_chuc_vu: $('#id_chuc_vu').val(),
            ten_nguoi_dung: $('#ten_nguoi_dung').val().trim(),
            email: $('#email').val().trim(),
            mat_khau: $('#mat_khau').val(),
            sdt_lien_he: $('#sdt_lien_he').val().trim()
        },

        successCallback:function(res){

            redirectWithToast('/quan-ly/nguoi-dung',res.message);

        }
    });
}

</script>

@endsection