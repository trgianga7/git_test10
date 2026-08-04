@extends('DanhSach')

@section('title', 'Sửa danh mục')

@section('content')

<h2>Sửa danh mục</h2> 

<div class="form-group">
    <label>Tên danh mục</label>
    <input id="ten_danh_muc">
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
    <a href="/quan-ly/danh-muc" class="btn-back">Quay lại</a>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
const id = {{ $id }};

$(document).ready(function () {

    let oldData = null;

    ajaxRequest({

        url:'/api/danh-muc/' + id,

        loading:false,
        showSuccess:false,

        successCallback:function(res){

            oldData = res;

            $('#ten_danh_muc').val(res.ten_danh_muc);
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

    if(!validateField('#ten_danh_muc', 'Chưa nhập tên danh mục')) return;

    ajaxRequest({
        url: '/api/danh-muc/' + id,
        type: 'POST',
        showSuccess:false,

        data: {
            _method: 'PUT',
            ten_danh_muc: $('#ten_danh_muc').val(),
            trang_thai: $('input[name="trang_thai"]:checked').val()
        },

        successCallback:function(res){

            redirectWithToast(
                '/quan-ly/danh-muc', res.message
            );
        }
    });
}
</script>