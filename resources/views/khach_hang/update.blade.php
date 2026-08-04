@extends('DanhSach')

@section('title', 'Sửa khách hàng')

@section('content')

<h2>Sửa khách hàng</h2> 

<div class="form-group">
    <label>Tên khách hàng</label>
    <input id="ten_khach_hang">
</div>

<div class="form-group">
    <label>Loại khách hàng</label>
    <select id="loai_khach_hang">
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
    <input id="mat_khau">
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

<div class="div-button">
    <button onclick="update()">Cập nhật</button>
    <a href="/quan-ly/khach-hang" class="btn-back">Quay lại</a>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
const uuid = "{{ $uuid }}";

$(document).ready(function () {

    let oldData = null;

    ajaxRequest({

        url:'/api/khach-hang/' + uuid,

        loading:false,
        showSuccess:false,

        successCallback:function(res){
            oldData = res;

            $('#loai_khach_hang').val(res.loai_khach_hang);
            $('#ten_khach_hang').val(res.ten_khach_hang);
            $('#sdt').val(res.sdt);
            $('input[name="trang_thai"][value="' + res.trang_thai + '"]').prop('checked', true);
        }
    });
 
    $('input').on('input', function () {
        $(this).removeClass('error');
    });

});

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

    if(!validateField('#ten_khach_hang', 'Chưa nhập tên khách hàng')) return;
    if(!validateField('#loai_khach_hang', 'Chưa chọn loại khách hàng')) return;
    if(!validateField('#sdt', 'Chưa nhập số điện thoại')) return;


    ajaxRequest({
        url: '/api/khach-hang/' + uuid,
        type: 'POST',
        showSuccess:false,

        data: {
            _method: 'PUT',
            loai_khach_hang: $('#loai_khach_hang').val(),
            ten_khach_hang: $('#ten_khach_hang').val(),
            sdt: $('#sdt').val(),
            mat_khau: $('#mat_khau').val(),
            trang_thai: $('input[name="trang_thai"]:checked').val()
        },

        successCallback:function(res){

            redirectWithToast('/quan-ly/khach-hang', res.message);
        }
    });
}
</script>

@endsection