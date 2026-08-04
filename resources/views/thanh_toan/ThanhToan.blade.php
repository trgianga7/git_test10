@extends('thanh_toan.layout')

@section('title', 'Thanh toán')

@section('content')

<link rel="stylesheet" href="{{ asset('css/ThanhToan/ThanhToan.css') }}">

<div class="checkout-container">

    <div class="checkout-form">

        <form id="checkoutForm">

            <label>Tên người mua</label>
            <input type="text" id="ten_nguoi_nhan">

            <label>Số điện thoại</label>
            <input type="text" id="sdt_nguoi_nhan">

            <label class="switch-label">
                <span>Lấy địa chỉ đã lưu</span>
                <input type="checkbox" id="toggleAddress">
            </label>

            <div id="savedAddressBox" style="display:none;">
                <label>Chọn địa chỉ</label>

                <select
                    id="dia_chi_co_san"
                    class="select-address">
                </select>
            </div>

            <div id="newAddressBox">
                <label>Nhập địa chỉ mới</label>

                <input
                    type="text"
                    id="dia_chi_moi"
                    placeholder="Nhập địa chỉ">
            </div>

            <label>Mã giảm giá</label>

            <input
                type="hidden"
                id="ma_giam_gia_ap_dung">

            <div class="giam-gia-group">

                <input
                    type="text"
                    id="ma_giam_gia"
                    placeholder="Nhập mã">

                <button
                    type="button"
                    id="btnApDung">

                    Áp dụng

                </button>

            </div>

            <p
                id="giamGiaInfo"
                style="color:green">
            </p>

            <label>Hình thức thanh toán</label>

            <div class="payment-method">

                <label class="payment-option">
                    <input
                        type="radio"
                        name="phuong_thuc"
                        value="cod"
                        checked>

                    COD
                </label>

                <label class="payment-option">

                    <input
                        type="radio"
                        name="phuong_thuc"
                        value="pay">

                    Thanh toán số dư
                    (
                    <span id="so_du">0 đ</span>
                    )

                </label>

                <label class="payment-option">

                    <input
                        type="radio"
                        name="phuong_thuc"
                        value="qr">

                    QR

                </label>

            </div>

            <button
                type="button"
                id="btnSubmit">

                Xác nhận đặt hàng

            </button>

        </form>

    </div>

    <div class="checkout-cart">

        <h3 style="color:green">
            Sản phẩm đã chọn
        </h3>

        <div id="checkout-cart-list"></div>

        <div class="total">

            <p class="tong-goc">

                Tổng tiền gốc:
                <span id="tongGoc">0</span> đ

            </p>

            <p class="giam-gia">

                Giảm giá:
                <span id="soTienGiam">0</span> đ

            </p>

            <h3 class="tong-thuc">

                Tổng thanh toán:
                <span id="tongSauGiam">0</span> đ

            </h3>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script>
$(document).ready(function () {

    loadCheckout();

    $('#toggleAddress').on('change', function () {

        if ($(this).is(':checked')) {

            $('#savedAddressBox').show();
            $('#newAddressBox').hide();

        } else {

            $('#savedAddressBox').hide();
            $('#newAddressBox').show();
        }
    });

    $('#btnSubmit').on('click', function () {
        datHang();
    });

    $('#btnApDung').on('click', function () {
        apDungMaGiamGia();
    });

});

function loadCheckout()
{
    ajaxRequest({

        url: '/api/thanh-toan',

        type: 'GET',

        loading: true,

        showSuccess: false,

        successCallback: function (res) {

            renderThongTinNguoiDung(res);

            renderDiaChi(res.dia_chi);

            renderGioHang(res.gio_hang);

            renderTongTien(res.tong_tien);
        }
    });
}

function renderThongTinNguoiDung(res)
{
    $('#ten_nguoi_nhan')
        .val(res.ten_nguoi_nhan ?? '');

    $('#sdt_nguoi_nhan')
        .val(res.sdt ?? '');

    $('#so_du')
        .text(
            Number(res.so_du)
                .toLocaleString() + ' đ'
        );
}

function renderDiaChi(diaChi)
{
    //console.log(diaChi);
    let html = '';

    diaChi.forEach(dc => {

        html += `
            <option value="${dc.ket_hop_dia_chi}">
                ${dc.ket_hop_dia_chi}
            </option>
        `;
    });

    $('#dia_chi_co_san').html(html);
}

function renderGioHang(cart)
{
    let html = '';

    cart.forEach(item => {

        const gia =
            Number(item.gia_ap_dung);

        const thanhTien =
            gia * Number(item.so_luong);

        html += `
            <div class="cart-item">

                <div class="cart-left">

                    <img
                        src="${
                            item.anh
                            ? '/storage/' + item.anh
                            : '/storage/anh_san_pham/no-image.jpg'
                        }"
                    >

                    <div>

                        <strong>
                            ${item.ten_san_pham}
                        </strong>

                        <br>

                        ${item.ten_phu}

                        <br>

                        SL:
                        ${item.so_luong}

                    </div>

                </div>

                <div class="cart-price">

                    ${thanhTien.toLocaleString()} đ

                </div>

            </div>
        `;
    });

    $('#checkout-cart-list')
        .html(html);
}

function renderTongTien(tongTien)
{
    $('#tongGoc')
        .text(
            Number(tongTien)
                .toLocaleString()
        );

    $('#tongSauGiam')
        .text(
            Number(tongTien)
                .toLocaleString()
        );
}

function datHang()
{
    let diaChi = '';

    if ($('#toggleAddress').is(':checked')) {

        diaChi =
            $('#dia_chi_co_san').val();

    } else {

        diaChi =
            $('#dia_chi_moi').val();
    }

    ajaxRequest({

        url: '/api/thanh-toan',

        type: 'POST',

        data: {

            ten_nguoi_nhan: $('#ten_nguoi_nhan').val(),

            sdt_nguoi_nhan: $('#sdt_nguoi_nhan').val(),

            dia_chi: diaChi,

            phuong_thuc: $('input[name="phuong_thuc"]:checked').val(),

            ma_giam_gia: $('#ma_giam_gia_ap_dung').val()
        },

        successCallback: function () {

           
        }
    });
}

//Áp dụng giảm giá
function apDungMaGiamGia()
{
    const maGiamGia = $('#ma_giam_gia').val().trim();

    if (!maGiamGia) {

        showToast(
            'Vui lòng nhập mã giảm giá',
            'warning'
        );

        return;
    }

    ajaxRequest({

        url: '/api/thanh-toan/kiem-tra-ma',

        type: 'POST',

        data: {
            ma_giam_gia: maGiamGia
        },

        successCallback: function(res) {

            if (!res.success) {

                showToast(
                    'Mã giảm giá không hợp lệ',
                    'warning'
                );

                return;
            }

            $('#ma_giam_gia_ap_dung')
                .val(maGiamGia);

            const tongGoc =
                Number(
                    $('#tongGoc')
                        .text()
                        .replace(/\./g, '')
                        .replace(/,/g, '')
                );

            let soTienGiam = 0;

            if (res.loai == 0) {

                soTienGiam =
                    Number(res.gia_tri);

            } else {

                soTienGiam =
                    tongGoc *
                    Number(res.gia_tri) / 100;
            }

            soTienGiam =
                Math.min(
                    soTienGiam,
                    tongGoc
                );

            const tongSauGiam =
                tongGoc - soTienGiam;

            $('#soTienGiam')
                .text(
                    Math.round(soTienGiam)
                        .toLocaleString()
                );

            $('#tongSauGiam')
                .text(
                    Math.round(tongSauGiam)
                        .toLocaleString()
                );

            $('#giamGiaInfo').text(

                res.loai == 0

                ? `Đã áp dụng giảm ${Number(res.gia_tri).toLocaleString()}đ`

                : `Đã áp dụng giảm ${res.gia_tri}%`
            );
        }
    });
}

</script>

@endsection