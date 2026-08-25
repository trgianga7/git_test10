@extends('DanhSach')

@section('title', 'Thêm sản phẩm chi tiết')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<h2>Thêm sản phẩm chi tiết</h2> 

<div class="create-wrapper">
    <div class="left-form">    
        <div class="form-group">
            <label>Tên sản phẩm</label>
            <select id="id_san_pham" class="select2">
                <option value="">-- Chọn sản phẩm --</option>
            </select>
        </div>

        <div class="form-group">
            <label>Tên phân loại</label>
            <input id="ten_phu">
        </div>

        <div class="form-group">
            <label>Giá bán</label>
            <input type="number" id="gia_ban">
        </div>

        <div class="form-group">
            <label>Số lượng</label>
            <input type="number" id="so_luong">
        </div>

        <div class="form-group">
            <label>Mô tả</label>
            <textarea type="text" id="mo_ta"></textarea>
        </div>

        <div class="form-group">
            <label>Khuyến mãi</label>
            <input id="khuyen_mai">
        </div>
    
        <div class="div-button">
            <button onclick="save()">Lưu</button>
            <a href="/quan-ly/san-pham-chi-tiet" class="btn-back">Quay lại</a>
        </div>
    </div>

    <div class="right-image">

        <div class="image-box">
            <button type="button" onclick="openModal()">
                Thêm ảnh
            </button>
        </div>

        <div id="imageModal" class="modal-spct" style="display:none;">
            <div class="modal-spct-content">

                <h3>Ảnh sản phẩm</h3>

                <div class="form-group">
                    <label>Ảnh đại diện</label>
                    <input type="file" id="anh_dai_dien" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Ảnh phụ</label>
                    <input type="file" id="anh_phu" multiple accept="image/*">
                </div>

                <div class="div-button">
                    <button type="button" onclick="closeModal()">Đóng</button>
                </div>

            </div>
        </div>

    </div>
    
</div>

{{--<div class="div-button">
    <button onclick="save()">Lưu</button>
    <a href="/quan-ly/san-pham-chi-tiet" class="btn-back">Quay lại</a>
</div>--}}

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

function openModal(){
    $('#imageModal').show();
}

function closeModal(){
    $('#imageModal').hide();
}

$(document).ready(function () {

    $('.select2').select2({
        //allowClear: true,
        width: '100%'
    });

    ajaxRequest({

        url:'/api/san-pham/all',

        loading:false,
        showSuccess:false,

        successCallback:function(res){

            let html = '<option value="">-- Chọn sản phẩm --</option>';

            res.forEach(sp => {
                html += `
                    <option value="${sp.id}">
                        ${sp.ten_san_pham}
                    </option>
                `;
            });

            $('#id_san_pham').html(html);
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

    if(!validateField('#id_san_pham', 'Chưa chọn sản phẩm')) return;
    if(!validateField('#ten_phu', 'Chưa nhập tên phân loại')) return;
    if(!validateField('#gia_ban', 'Chưa nhập giá bán')) return;
    if(!validateField('#so_luong', 'Chưa nhập số lượng')) return;

    let formData = new FormData();

    formData.append('id_san_pham', $('#id_san_pham').val());
    formData.append('ten_phu', $('#ten_phu').val());
    formData.append('gia_ban', $('#gia_ban').val());
    formData.append('so_luong', $('#so_luong').val());
    formData.append('mo_ta', $('#mo_ta').val());

    let avatar = $('#anh_dai_dien')[0].files[0];
    if(avatar){
        formData.append('anh_dai_dien', avatar);
    }

    let files = $('#anh_phu')[0].files;

    for(let i=0;i<files.length;i++){
        formData.append('anh[]', files[i]);
    }

    ajaxRequest({

        url:'/api/san-pham-chi-tiet',
        type:'POST',
        showSuccess:false,

        data: formData,

        processData:false,
        contentType:false,

        successCallback:function(res){

            redirectWithToast('/quan-ly/san-pham-chi-tiet',res.message);

        }

        {{--error:function(err){
            console.log(err.responseText);
        }--}}
    });
} 
</script>