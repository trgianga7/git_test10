@extends('DanhSach')

@section('title', 'Sửa địa chỉ')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<h2>Sửa địa chỉ</h2>

<div class="form-group">
    <label>Khách hàng</label>
    <select id="id_khach_hang" class="select2" disabled>
        <option value=""></option>
    </select>
</div>

<div class="form-group">
    <label>Tỉnh</label>
    <select id="province_id" class="select2">
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
    <a href="/quan-ly/dia-chi" class="btn-back">Quay lại</a>
</div>

@endsection


@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

const id = {{ $id }};

$(document).ready(function () {

    $('.select2').select2({
        allowClear:true,
        width:'100%'
    });

    loadDetail();

    $('input').on('input', function () {
        $(this).removeClass('error');
    });

    $('.select2').on('change', function () {

        $(this)
            .next('.select2-container')
            .removeClass('error');
    });

    $('#province_id').on('change', function () {

        let provinceId = $(this).val();

        $('#district_id')
            .html('<option value=""></option>')
            .trigger('change');

        $('#ward_code')
            .html('<option value=""></option>')
            .trigger('change');

        if(!provinceId) return;

        loadHuyen(provinceId);
    });

    $('#district_id').on('change', function () {

        let districtId = $(this).val();

        $('#ward_code')
            .html('<option value=""></option>')
            .trigger('change');

        if(!districtId) return;

        loadPhuong(districtId);
    });
});

function loadDetail()
{
    ajaxRequest({

        url:'/api/dia-chi/' + id,

        loading:true,
        showSuccess:false,

        successCallback:function(res){

            $('#dia_chi')
                .val(res.dia_chi);

            $('input[name="trang_thai"][value="' + res.trang_thai + '"]')
                .prop('checked', true);

            loadKhachHang(res.id_khach_hang);

            loadTinh(res.tinh);

            loadHuyen(res.tinh, res.huyen);

            loadPhuong(res.huyen, res.phuong);
        }
    });
}

function loadKhachHang(selectedId = '')
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
                    <option value="${kh.id}"
                        ${kh.id == selectedId ? 'selected' : ''}>
                        ${kh.ten_khach_hang}
                    </option>
                `;
            });

            $('#id_khach_hang').html(html).trigger('change');
        }
    });
}

function loadTinh(selectedId = '')
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
                    <option value="${t.province_id}"
                        ${t.province_id == selectedId ? 'selected' : ''}>
                        ${t.province_name}
                    </option>
                `;
            });

            $('#province_id').html(html);

            if(selectedId){
                $('#province_id').val(selectedId);
            }

            $('#province_id').trigger('change.select2');
        }
    });
}

function loadHuyen(provinceId, selectedId = '')
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
                    <option value="${h.district_id}"
                        ${h.district_id == selectedId ? 'selected' : ''}>
                        ${h.district_name}
                    </option>
                `;
            });

            $('#district_id').html(html);

            if(selectedId){
                $('#district_id').val(selectedId);
            }

            $('#district_id').trigger('change.select2');
        }
    });
}

function loadPhuong(districtId, selectedId = '')
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
                    <option value="${p.ward_code}"
                        ${p.ward_code == selectedId ? 'selected' : ''}>
                        ${p.ward_name}
                    </option>
                `;
            });

            $('#ward_code').html(html);

            if(selectedId){
                $('#ward_code').val(selectedId);
            }

            $('#ward_code').trigger('change.select2');
        }
    });
}

function validateField(selector, message)
{
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

function update()
{
    $('.error').removeClass('error');

    if(!validateField('#province_id', 'Chưa chọn tỉnh')) return;
    if(!validateField('#district_id', 'Chưa chọn huyện')) return;
    if(!validateField('#ward_code', 'Chưa chọn phường')) return;
    if(!validateField('#dia_chi', 'Chưa nhập địa chỉ')) return;

    ajaxRequest({

        url:'/api/dia-chi/' + id,

        type:'POST',

        loading:true,
        showSuccess:false,

        data:{
            _method:'PUT',

            tinh: $('#province_id').val(),

            huyen: $('#district_id').val(),

            phuong: $('#ward_code').val(),

            dia_chi: $('#dia_chi').val().trim(),

            trang_thai: $('input[name="trang_thai"]:checked').val()
        },

        successCallback:function(res){
            redirectWithToast('/quan-ly/dia-chi', res.message);
        }
    });
}

</script>
@endsection