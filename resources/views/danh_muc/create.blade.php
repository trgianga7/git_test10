@extends('DanhSach')

@section('title', 'Thêm danh mục')

@section('content')

<h2>Thêm danh mục</h2>

<div class="form-group">
    <label>Tên danh mục</label>
    <input id="ten_danh_muc">
</div>

<div class="div-button">
    <button onclick="save()">Lưu</button>
    <a href="/quan-ly/danh-muc" class="btn-back">Quay lại</a>
</div>

@endsection

@section('scripts')
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

function save(){

    $('.error').removeClass('error');

    if(!validateField('#ten_danh_muc', 'Chưa nhập tên danh mục')) return;

    ajaxRequest({

        url:'/api/danh-muc',
        type:'POST',
        showSuccess:false,

        data:{
            ten_danh_muc: $('#ten_danh_muc').val(),
        },
        
        successCallback:function(res){

            redirectWithToast('/quan-ly/danh-muc',res.message);

        }
    });
}
</script>
@endsection