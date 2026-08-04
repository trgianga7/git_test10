@extends('DanhSach')

@section('title', 'Thêm chức vụ')

@section('content')

<link rel="stylesheet" href="{{ asset('css/ChucVu/ChucVu.css') }}">

<h2>Thêm chức vụ</h2>

<div class="form-group">
    <label>Tên chức vụ</label>
    <input id="ten_chuc_vu">
</div>

<div class="form-group">
    <label>Chức năng</label>
    <div class="permission-box" id="permission-box">
    </div>
</div>

<div class="div-button">
    <button onclick="save()">Lưu</button>
    <a href="/quan-ly/chuc-vu" class="btn-back">Quay lại</a>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

    loadChucNang();

    $('input').on('input', function () {
        $(this).removeClass('error');
    });
});

function loadChucNang()
{
    ajaxRequest({

        url:'/api/chuc-nang/all',

        loading:true,
        showSuccess:false,

        successCallback:function(res){
            renderChucNang(res);
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

    return titles[nhom] ?? nhom.replaceAll('-', ' ');
}

function validateField(selector, message)
{
    let value = ($(selector).val() || '').trim();

    if(!value){

        $(selector).addClass('error');

        showToast(message, 'warning');

        return false;
    }

    return true;
}

function save()
{
    $('.error').removeClass('error');

    if(!validateField('#ten_chuc_vu', 'Chưa nhập tên chức vụ')) return;

    let chucNangIds = [];

    $('input[name="chuc_nang_ids[]"]:checked').each(function(){
        chucNangIds.push($(this).val());
    });

    ajaxRequest({

        url:'/api/chuc-vu',
        type:'POST',

        loading:true,
        showSuccess:false,

        data:{
            ten_chuc_vu: $('#ten_chuc_vu').val().trim(),
            chuc_nang_ids: chucNangIds
        },

        successCallback:function(res){
            redirectWithToast('/quan-ly/chuc-vu', res.message);
        }
        
    });
}
</script>
@endsection