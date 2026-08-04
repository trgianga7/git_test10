@extends('DanhSach')

@section('title', 'Thêm sản phẩm giảm giá')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<h2>Thêm sản phẩm giảm giá</h2> 

<div class="form-group">
    <label>Chọn sản phẩm</label>
    <select id="spct_id" class="select2">
        <option value="">-- Chọn sản phẩm --</option>
    </select>
</div>

<div class="form-group">
    <label>Giá bán</label>
    <input id="gia_ban" readonly>
</div>

<div class="form-group">
    <label>Giá khuyến mãi</label>
    <input id="gia_khuyen_mai">
</div>

<div class="form-group">
    <label>Phần trăm giảm ước tính: </label>

    <span id="phan_tram" style="font-weight:bold; color:red;">
        0%
    </span>
</div>

<div class="form-group">
    <button id="btn-save">
        Lưu
    </button>
</div>

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

$(document).ready(function(){

    $('.select2').select2({
        allowClear: true,
        width: '100%'
    });

    $('.select2').on('change', function () {
        $(this).next('.select2-container').removeClass('error');
    });

    loadSanPhamChiTiet();

    $('#spct_id').change(function(){

        let giaBan = $(this).find(':selected').data('gia');

        $('#gia_ban').val(Number(giaBan || 0).toLocaleString('vi-VN'));

        tinhPhanTram();
    });

    $('#gia_khuyen_mai').on('input', function(){

        tinhPhanTram();
    });

    $('#btn-save').click(function(){

        let spctId = $('#spct_id').val();

        let giaKm = $('#gia_khuyen_mai').val();

        if(!spctId){
            showToast('Vui lòng chọn sản phẩm', 'warning');
            return;
        }

        if(!giaKm){
            showToast('Vui lòng nhập giá khuyến mãi', 'warning');
            return;
        }

        ajaxRequest({

            url:'/api/giam-gia-san-pham',

            type:'POST',

            data:{
                spct_id: spctId,
                gia_khuyen_mai: giaKm
            },

            successCallback:function(){

                window.location.href =
                    '/quan-ly/giam-gia';
            }
        });
    });

});

function loadSanPhamChiTiet(){

    ajaxRequest({

        url:'/api/san-pham-chi-tiet/all',

        type:'GET',

        loading:false,
        showSuccess:false,

        successCallback:function(res){

            let html =
                '<option value="">Chọn phân loại</option>';

            res.forEach(spct => {

                html += `
                    <option value="${spct.id}" data-gia="${spct.gia_ban}">
                        ${spct.sanpham?.ten_san_pham ?? ''}
                        - 
                        ${spct.ten_phu}
                    </option>
                `;
            });

            $('#spct_id').html(html);
        }
    });
}

function tinhPhanTram(){

    let giaBan = Number($('#spct_id').find(':selected').data('gia'));

    let giaKm = Number($('#gia_khuyen_mai').val());

    if(
        !giaBan ||
        !giaKm ||
        giaKm >= giaBan
    ){
        $('#phan_tram').text('0%');
        return;
    }

    let phanTram = Math.round(
        (
            (giaBan - giaKm)
            / giaBan
        ) * 100
    );

    $('#phan_tram').text(
        phanTram + '%'
    );
}

</script>

@endsection