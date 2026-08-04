@extends('DanhSach')

@section('title', 'Sửa chức vụ')

@section('content')

<link rel="stylesheet" href="{{ asset('css/ChucVu/ChucVu.css') }}">

<h2>Sửa chức vụ</h2>

<div class="form-group">
    <label>Tên chức vụ</label>
    <input id="ten_chuc_vu">
</div>

<div class="form-group">
    <label>Chức năng</label>
    <div class="permission-box" id="permission-box"></div>
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
    <a href="/quan-ly/chuc-vu" class="btn-back">Quay lại</a>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

const id = {{ $id }};
let selectedIds = [];

$(document).ready(function () {
    loadChucNang();

    $('input').on('input change', function () {
        $(this).removeClass('error');
    });
});

function loadDetail()
{
    ajaxRequest({
        url: '/api/chuc-vu/' + id,
        type: 'GET',
        loading:false,
        showSuccess:false,

        successCallback: function(res){

            $('#ten_chuc_vu').val(res.ten_chuc_vu);

            $('input[name="trang_thai"][value="' + res.trang_thai + '"]').prop('checked', true);

            selectedIds = (res.chuc_nangs || []).map(x => x.id);

            applyChecked();
        }
    });
}

function loadChucNang()
{
    ajaxRequest({
        url: '/api/chuc-nang/all',
        loading:true,
        showSuccess:false,

        successCallback: function(res){

            renderChucNang(res);

            loadDetail();
        }
    });
}

function renderChucNang(data)
{
    let html = '';

    Object.keys(data || {}).forEach(nhom => {

        let list = data[nhom];

        if (!Array.isArray(list)) return;

        html += `
            <div class="permission-group">

                <div class="permission-title">
                    ${formatTitle(nhom)}
                </div>

                <div class="permission-list">
        `;

        list.forEach(cn => {

            html += `
                <label class="permission-item">

                    <input type="checkbox"
                        name="chuc_nang_ids[]"
                        value="${cn.id}">

                    <span>${cn.ten_chuc_nang}</span>

                </label>
            `;
        });

        html += `
                </div>
            </div>
        `;
    });

    $('#permission-box').html(html);

    applyChecked();
}

function formatTitle(nhom)
{
    const titles = {
        'nguoi-dung': 'Người dùng',
        'chuc-vu': 'Chức vụ',
        'dia-chi': 'Địa chỉ',
        'khach-hang': 'Khách hàng',
        'danh-muc': 'Danh mục',
        'san-pham': 'Sản phẩm',
        'san-pham-chi-tiet': 'Sản phẩm chi tiết',
        'hoa-don': 'Hóa đơn'
    };

    return titles?.[nhom] ?? (nhom ? nhom.replaceAll('-', ' ') : 'Khác');
}

function applyChecked()
{
    selectedIds.forEach(id => {

        $(`input[name="chuc_nang_ids[]"][value="${id}"]`).prop('checked', true);

    });
}

function validateField(selector, message)
{
    let value = ($(selector).val() || '').trim();

    if (!value) {
        $(selector).addClass('error');
        showToast(message, 'warning');
        return false;
    }

    return true;
}

function update()
{
    $('.error').removeClass('error');

    if (!validateField('#ten_chuc_vu', 'Chưa nhập tên chức vụ')) return;

    let trangThai = $('input[name="trang_thai"]:checked').val();

    let chucNangIds = [];

    $('input[name="chuc_nang_ids[]"]:checked').each(function () {
        chucNangIds.push($(this).val());
    });

    ajaxRequest({
        url: '/api/chuc-vu/' + id,
        type: 'POST',
        loading:true,
        showSuccess:false,

        data: {
            _method: 'PUT',
            ten_chuc_vu: $('#ten_chuc_vu').val().trim(),
            trang_thai: trangThai,
            chuc_nang_ids: chucNangIds
        },

        successCallback: function(res){
            redirectWithToast('/quan-ly/chuc-vu', res.message);
        }
    });
}

</script>
@endsection