@extends('trang_chu.layout_san_pham')

@section('title', 'Danh sách sản phẩm')

@section('content')
<div id="product-grid" class="product-grid"></div>

<div id="pagination"></div>

@endsection

@section('scripts')

<script>

$(document).ready(function(){

    loadDanhMuc();

    loadProducts();

    //loadSoLuongGioHang();

    $('#filter-form').on('submit', function(e){

        e.preventDefault();

        loadProducts(1);
    });

});

function loadDanhMuc()
{
    ajaxRequest({

        url:'/api/trang-chu/san-pham/danh-muc',

        type:'GET',

        loading:false,

        showSuccess:false,

        successCallback:function(res){

            let html = `<option value=""> Tất cả </option>`;

            res.data.forEach(dm => {

                html += `
                    <option value="${dm.id}">
                        ${dm.ten_danh_muc}
                    </option>
                `;
            });
            $('#id_danh_muc').select2('destroy');
            $('#id_danh_muc').html(html);
            //$('#id_danh_muc').html(html).trigger('change');
            //$('#id_danh_muc').html(html).trigger('change.select2');
            $('#id_danh_muc').html(html).val('').trigger('change.select2');
            

        }
    });
}

function loadProducts(page = 1)
{
    ajaxRequest({

        url:'/api/trang-chu/san-pham',

        type:'GET',

        loading:true,

        showSuccess:false,

        data:{
            page:page,
            id_danh_muc:$('#id_danh_muc').val(),
            gia_tu:$('#gia_tu').val(),
            gia_den:$('#gia_den').val()
        },

        successCallback:function(res){

            renderProducts(res.data);

            renderPagination(res);

        }
    });
}

function renderProducts(products)
{
    let html = '';

    products.forEach(sp => {

        let giaBan = Number(sp.gia_ban).toLocaleString();

        let giaKm = sp.gia_khuyen_mai ? Number(sp.gia_khuyen_mai).toLocaleString() : null;

            html += `
                <div class="product-item">

                    <a href="/san-pham/chi-tiet/${sp.ma_sp}">
                        <img
                            src="${
                                sp.anh_dai_dien
                                ? '/storage/' + sp.anh_dai_dien
                                : '/storage/anh_san_pham/no-image.jpg'
                            }"
                            class="product-image"
                        >
                    </a>

                    <div class="product-info">

                        <a
                            href="/san-pham/chi-tiet/${sp.ma_sp}"
                            class="product-name-link"
                        >
                            <div class="product-name">
                                ${sp.ten_san_pham} - ${sp.ten_phu}
                            </div>
                        </a>

                        <div>

                            <span class="product-price">
                                ${
                                    giaKm
                                    ? giaKm + ' đ'
                                    : giaBan + ' đ'
                                }
                            </span>

                            ${
                                giaKm
                                ? `
                                <span class="product-old-price">
                                    ${giaBan} đ
                                </span>
                                `
                                : ''
                            }

                        </div>

                        <button
                            class="btn-cart"
                            onclick="addToCart('${sp.ma_sp}')"
                        >
                            Thêm vào giỏ hàng
                        </button>

                    </div>

                </div>
            `;
    });

    $('#product-grid').html(html);
}

function renderPagination(res)
{
    let html = '';

    if(res.last_page <= 1)
    {
        $('#pagination').html('');
        return;
    }

    html += `
        <button
            ${res.current_page == 1 ? 'disabled' : ''}
            onclick="loadProducts(${res.current_page - 1})">
            Trước
        </button>
    `;

    for(let i = 1; i <= res.last_page; i++)
    {
        html += `
            <button
                class="${
                    res.current_page == i
                    ? 'active-page'
                    : ''
                }"
                onclick="loadProducts(${i})">

                ${i}

            </button>
        `;
    }

    html += `
        <button
            ${ res.current_page == res.last_page ? 'disabled' : ''}
            onclick="loadProducts(${res.current_page + 1})">
            Sau
        </button>
    `;

    $('#pagination').html(html);
}


//Giỏ hàng
function addToCart(ma_sp)
{
    ajaxRequest({

        url:'/api/gio-hang/them',

        type:'POST',

        data:{
            ma_sp: ma_sp
        },

        successCallback:function(){

            loadSoLuongGioHang();
        }
    });
}

</script>

@endsection