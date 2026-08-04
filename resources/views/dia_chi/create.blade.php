@extends('DanhSach')

@section('title', 'Thêm địa chỉ')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<h2>Thêm địa chỉ</h2>

<div class="form-group">
    <label>Khách hàng</label>
    <select name="id_khach_hang" id="id_khach_hang" class="select2">
        <option value=""></option>
    </select>
</div>

<div class="form-group">
    <label>Tỉnh</label>
    <select name="province_id" id="province_id" class="select2">
        <option value=""></option>
    </select>
</div>

<div class="form-group">
    <label>Huyện</label>
    <select id="district_id" class="select2">
        <option value=""></option>
    </select>
</div>

<div class="form-group">
    <label>Phường</label>
    <select id="ward_code" class="select2">
        <option value=""></option>
    </select>
</div>

<div class="form-group">
    <label>Địa chỉ</label>
    <input id="dia_chi">
</div>

<div class="div-button">
    <button onclick="save()">Lưu</button>
    <a href="/quan-ly/dia-chi" class="btn-back">Quay lại</a>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {

    $('.select2').select2({
        allowClear: true,
        width: '100%'
    });

    // load khách hàng
    loadKhachHang();

    // load tỉnh
    loadTinh();

    $('#province_id').on('change', function () {

        let provinceId = $(this).val();

        resetDistrict();
        resetWard();

        if(!provinceId) return;

        loadHuyen(provinceId);
    });

    $('#district_id').on('change', function () {

        let districtId = $(this).val();

        resetWard();

        if(!districtId) return;

        loadPhuong(districtId);
    });

    $('input').on('input', function () {
        $(this).removeClass('error');
    });

    $('.select2').on('change', function () {
        $(this).next('.select2-container').removeClass('error');
    });

});

function loadKhachHang()
{
    ajaxRequest({

        url:'/api/khach-hang/all',

        loading:false,
        showSuccess:false,

        successCallback:function(res){

            let html =
                '<option value="">-- Chọn khách hàng --</option>';

            (res || []).forEach(kh => {

                html += `
                    <option value="${kh.id}">
                        ${kh.ten_khach_hang}
                    </option>
                `;
            });

            $('#id_khach_hang').html(html);
        }
    });
}

function loadTinh()
{
    ajaxRequest({

        url:'/api/dia-chi/tinh',

        loading:false,
        showSuccess:false,

        successCallback:function(res){

            let html =
                '<option value="">-- Chọn tỉnh --</option>';

            (res || []).forEach(t => {

                html += `
                    <option value="${t.province_id}">
                        ${t.province_name}
                    </option>
                `;
            });

            $('#province_id').html(html);
        }
    });
}

function loadHuyen(provinceId)
{
    ajaxRequest({

        url:'/api/dia-chi/huyen/' + provinceId,

        loading:false,
        showSuccess:false,

        successCallback:function(res){

            let html =
                '<option value="">-- Chọn huyện --</option>';

            (res || []).forEach(h => {

                html += `
                    <option value="${h.district_id}">
                        ${h.district_name}
                    </option>
                `;
            });

            $('#district_id').html(html).trigger('change');
        }
    });
}

function loadPhuong(districtId)
{
    ajaxRequest({

        url:'/api/dia-chi/phuong/' + districtId,

        loading:false,
        showSuccess:false,

        successCallback:function(res){

            let html =
                '<option value="">-- Chọn phường --</option>';

            (res || []).forEach(p => {

                html += `
                    <option value="${p.ward_code}">
                        ${p.ward_name}
                    </option>
                `;
            });

            $('#ward_code').html(html).trigger('change');
        }
    });
}

function resetDistrict()
{
    $('#district_id').html('<option value=""></option>').trigger('change');
}

function resetWard()
{
    $('#ward_code').html('<option value=""></option>').trigger('change');
}

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

    if(!validateField('#id_khach_hang', 'Chưa chọn khách hàng')) return;
    if(!validateField('#province_id', 'Chưa chọn tỉnh')) return;
    if(!validateField('#district_id', 'Chưa chọn huyện')) return;
    if(!validateField('#ward_code', 'Chưa chọn phường')) return;
    if(!validateField('#dia_chi', 'Chưa nhập địa chỉ')) return;

    ajaxRequest({

        url:'/api/dia-chi',

        type:'POST',

        loading:true,
        showSuccess:false,

        data:{
            id_khach_hang: $('#id_khach_hang').val(),

            tinh: $('#province_id').val(),

            huyen: $('#district_id').val(),

            phuong: $('#ward_code').val(),

            dia_chi: $('#dia_chi').val().trim()
        },

        successCallback:function(res){
            redirectWithToast('/quan-ly/dia-chi', res.message);
        }
    });
}
</script>
@endsection