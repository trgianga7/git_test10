@extends('DanhSach')

@section('title', 'Sửa người dùng')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<h2>Sửa người dùng</h2> 

<div class="form-group">
    <label>Tên người dùng</label>
    <input id="ten_nguoi_dung">
</div>

<div class="form-group">
    <label>Chức vụ</label>
    <select id="id_chuc_vu" class="select2"></select>
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
    <label>SĐT</label>
    <input id="sdt_lien_he">
</div>

<div class="form-group">
    <label>Trạng thái</label>

    <div class="radio-group">
        <label class="radio-item">
            <input type="radio" name="trang_thai" value="1">
            <span>Hoạt động</span>
        </label>

        <label class="radio-item">
            <input type="radio" name="trang_thai" value="0">
            <span>Khóa</span>
        </label>
    </div>
</div>

<div id="mo-khoa-box" style="display:none; margin-top:10px;">
    <button type="button" class="btn-mo-khoa" onclick="moKhoa()">
        🔓 Mở khóa tài khoản
    </button>
</div>

<div class="div-button">
    <button onclick="update()">Cập nhật</button>
    <a href="/quan-ly/nguoi-dung" class="btn-back">Quay lại</a>
</div>

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
const uuid = "{{ $uuid }}";

$(document).ready(function () {

    let oldData = null;

    $('.select2').select2({
        //allowClear: true,
        width: '100%'
    });

    ajaxRequest({

        url:'/api/nguoi-dung/' + uuid,

        loading:false,
        showSuccess:false,

        successCallback:function(res){

            oldData = res;

            $('#ten_nguoi_dung').val(res.ten_nguoi_dung);

            $('#email').val(res.email);

            $('#sdt_lien_he').val(res.sdt_lien_he);

            $('input[name="trang_thai"][value="' + res.trang_thai + '"]').prop('checked', true);

            if (res.thoi_gian_khoa) {
                $('#mo-khoa-box').show();
            } else {
                $('#mo-khoa-box').hide();
            }

            loadChucVu(res.id_chuc_vu);
        }

    });

    function loadChucVu(selectedId){
        ajaxRequest({

            url:'/api/chuc-vu/all',

            loading:false,
            showSuccess:false,

            successCallback:function(res){

                let html = '<option value="">-- Chọn chức vụ --</option>';

                res.forEach(cv => {

                    html += `
                        <option value="${cv.id}"
                            ${cv.id == selectedId ? 'selected': ''}>
                            ${cv.ten_chuc_vu}
                        </option>
                    `;
                });

                $('#id_chuc_vu').html(html);
            }

        });
    }

    $('input').on('input', function () {
        $(this).removeClass('error');
    });

    $('.select2').on('change', function () {
        $(this).next('.select2-container').removeClass('error');
    });
});

function moKhoa(){

    if(!confirm('Bạn có chắc muốn mở khóa tài khoản này?')){
        return;
    }

    ajaxRequest({

        url: '/api/nguoi-dung/mo-khoa/' + uuid,

        type: 'PUT',

        loading: true,

        showSuccess: false,

        successCallback:function(res){

            showToast(
                res.message || 'Đã mở khóa tài khoản',
                'success'
            );

            $('#mo-khoa-box').hide();

            $('input[name="trang_thai"][value="1"]').prop('checked', true);

        }

    });
}

function validateField(selector, message){

    let value = ($(selector).val() || '').trim();

    if(!value){

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

function update() {
    $('.error').removeClass('error');

    if(!validateField('#ten_nguoi_dung', 'Chưa nhập tên người dùng')) return;
    if(!validateField('#id_chuc_vu', 'Chưa chọn chức vụ')) return;
    if(!validateField('#email', 'Chưa nhập email')) return;

    ajaxRequest({
        url: '/api/nguoi-dung/' + uuid,
        type: 'POST',
        showSuccess:false,

        data: {
            _method: 'PUT',
            id_chuc_vu: $('#id_chuc_vu').val(),
            ten_nguoi_dung: $('#ten_nguoi_dung').val().trim(),
            email: $('#email').val().trim(),
            mat_khau: $('#mat_khau').val(),
            sdt_lien_he: $('#sdt_lien_he').val().trim(),
            trang_thai: $('input[name="trang_thai"]:checked').val()
        },
        
        successCallback:function(res){

            redirectWithToast(
                '/quan-ly/nguoi-dung', res.message
            );
        }

    });
}
</script>

@endsection