<link rel="stylesheet" href="{{ asset('css/ThanhToan/GioHang.css') }}">

@extends('thanh_toan.layout')

@section('title', 'Giỏ hàng')

@section('content')

<div class="cart-container">

    <div id="cart-list"></div>

    <div class="cart-summary">

        <div class="summary-row">
            <span>Tổng tiền</span>

            <strong id="tong-tien">
                0đ
            </strong>
        </div>

        <a
            href="{{ route('thanh-toan.index') }}"
            class="btn-checkout"
        >
            Thanh toán
        </a>

    </div>

</div>

@endsection

@section('scripts')

<script>

$(document).ready(function(){

    loadCart();

});

function loadCart()
{
    ajaxRequest({

        url:'/api/gio-hang',

        type:'GET',

        loading:true,

        showSuccess:false,

        successCallback:function(res){

            renderCart(res);
        }
    });
}

function renderCart(cart)
{
    if (!cart || cart.length === 0)
    {
        $('#cart-list').html(`
            <div class="empty-cart">
                Giỏ hàng trống
            </div>
        `);

        $('#tong-tien').text('0đ');

        return;
    }

    let html = '';

    let tongTien = 0;

    cart.forEach(item => {

        const soLuong = Number(item.so_luong);

        //const giaBan = Number(item.gia_ban);
        const giaBan = Number(item.gia_ap_dung);

        const thanhTien = giaBan * soLuong;

        tongTien += thanhTien;

        html += `
            <div class="cart-item">

                <img
                    src="${item.anh ? '/storage/' + item.anh : '/storage/anh_san_pham/no-image.jpg'}" class="cart-image">

                <div class="cart-info">

                    <div class="cart-name">
                        ${item.ten_san_pham}
                    </div>

                    <div class="cart-sub">
                        ${item.ten_phu}
                    </div>

                    <div class="cart-price">
                        ${item.gia_khuyen_mai? 
                            `
                                <span class="price-sale">
                                    ${Number(item.gia_ap_dung).toLocaleString()}đ
                                </span>

                                <span class="price-old">
                                    ${Number(item.gia_goc).toLocaleString()}đ
                                </span>
                            `
                            : `
                                <span class="price-sale">
                                    ${Number(item.gia_ap_dung).toLocaleString()}đ
                                </span>
                            `
                        }
                    </div>

                </div>

                <div class="cart-action">

                    <div class="qty-box">

                        <button onclick="updateQty(${item.id}, ${soLuong - 1})">
                            -
                        </button>

                        <input
                            type="number"
                            min="1"
                            max="${item.ton_kho ?? 9999}"
                            value="${soLuong}"
                            onchange="updateQty(${item.id}, Number(this.value))"
                        >

                        <button onclick="updateQty(${item.id}, ${soLuong + 1})">
                            +
                        </button>

                    </div>

                    <div class="item-total">
                        <span class="item-total-label">
                            Tổng:
                        </span>

                        <span class="item-total-value">
                            ${thanhTien.toLocaleString()}đ
                        </span>
                    </div>

                    <button class="btn-remove" onclick="removeCart(${item.id})">
                        Xóa
                    </button>

                </div>

            </div>
        `;
    });

    $('#cart-list').html(html);

    $('#tong-tien').text(tongTien.toLocaleString() + 'đ');
}

function updateQty(id, soLuong)
{
    soLuong = Number(soLuong);

    if(isNaN(soLuong) || soLuong < 1)
    {
        return;
    }

    ajaxRequest({

        url:'/api/gio-hang/cap-nhat',

        type:'POST',

        data:{
            id:id,
            so_luong:soLuong
        },

        successCallback:function(){

            loadCart();
        }
    });
}

function removeCart(id)
{
    ajaxRequest({

        url:'/api/gio-hang/xoa',

        type:'POST',

        data:{
            id:id
        },

        successCallback:function(){

            loadCart();

            //loadCartCount();
        }
    });
}

</script>

@endsection