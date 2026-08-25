@extends('DanhSach')

@section('title', 'Sửa sản phẩm')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<h2>Sửa sản phẩm</h2> 

<div class="form-group">
    <label>Tên sản phẩm</label>
    <input id="ten_san_pham">
</div>

<div class="form-group">
    <label>Danh mục</label>
    <select id="id_danh_muc" class="select2"></select>
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
    <a href="/quan-ly/san-pham" class="btn-back">Quay lại</a>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
const key_sp = "{{ $key_sp }}";

$(document).ready(function () {

    let oldData = null;

    $('.select2').select2({
        //allowClear: true,
        width: '100%'
    });

    ajaxRequest({
        url:'/api/san-pham/' + key_sp,

        loading:false,
        showSuccess:false,

        successCallback:function(res){
            oldData = res;

            $('#ten_san_pham').val(res.ten_san_pham);
            $('input[name="trang_thai"][value="' + res.trang_thai + '"]').prop('checked', true);

            loadDanhMuc(res.id_danh_muc);
        }
    });

    function loadDanhMuc(selectedId){
        ajaxRequest({
            url:'/api/danh-muc/all',

            loading:false,
            showSuccess:false,

            successCallback:function(res){

                let html = '<option value="">-- Chọn danh mục --</option>';

                res.forEach(dm => {
                    html += `
                        <option value="${dm.id}"
                            ${dm.id == selectedId ? 'selected' : ''}>
                            ${dm.ten_danh_muc}
                        </option>
                    `;
                });

                $('#id_danh_muc').html(html);
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

    if(!validateField('#ten_san_pham', 'Chưa nhập tên sản phẩm')) return;
    if(!validateField('#id_danh_muc', 'Chưa chọn danh mục')) return;

    ajaxRequest({
        url: '/api/san-pham/' + key_sp,
        type: 'POST',
        showSuccess:false,

        data: {
            _method: 'PUT',
            id_danh_muc: $('#id_danh_muc').val(),
            ten_san_pham: $('#ten_san_pham').val(),
            trang_thai: $('input[name="trang_thai"]:checked').val()
        },

        successCallback:function(res){

            redirectWithToast(
                '/quan-ly/san-pham', res.message
            );
        }

        {{--error: function(err) {
            console.log(err.responseText);
        }--}}
    });
}
</script>