@extends('DanhSach')

@section('title', 'Quản lý giảm giá')

@section('content')

<link rel="stylesheet" href="{{ asset('css/GiamGia/GiamGia.css') }}">

<button class="tab-item active" onclick="showTab('voucher', this)">
    Mã giảm giá
</button>

<button class="tab-item" onclick="showTab('campaign', this)">
    Sản phẩm giảm giá
</button>

<div id="voucher-tab">
 
    <div class="page-header">
        <h2>Danh sách mã giảm giá</h2>

        <a href="/quan-ly/giam-gia/create">
            <button class="btn-add">Thêm mới mã giảm giá</button>
        </a>
    </div>

    <div class="search-wrapper">
        <input id="voucher-search" class="search" placeholder="Tìm kiếm...">
    </div>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên giảm giá</th>
                <th>Loại giảm giá</th>
                <th>Mã giảm giá</th>
                <th>Giá trị</th>
                <th>Số lượng</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Trạng thái</th>
                <th>Chức năng</th> 
            </tr>
        </thead>

        <tbody id="voucher-tbody"></tbody>
    </table>

    <div id="voucher-pagination"></div>

</div>

<div id="campaign-tab" style="display:none">
    <div class="page-header">
        <h2>Danh sách sản phẩm giảm giá</h2>

        <a href="/quan-ly/giam-gia/create-sp-giam-gia">
            <button class="btn-add">Thêm mới sản phẩm giảm giá</button>
        </a>
    </div>

    <div class="search-wrapper">
        <input id="campaign-search" class="search" placeholder="Tìm kiếm...">
    </div>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Tên phân loại</th>
                <th>Giá bán</th>
                <th>Giá khuyến mãi</th>
                <th>Phần trăm giảm</th>
                <!--<th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Trạng thái</th>-->
                <th>Chức năng</th> 
            </tr>
        </thead>

        <tbody id="campaign-tbody"></tbody>
    </table>

    <div id="campaign-pagination"></div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$(document).ready(function () {
    
    //Mã giảm giá
    const voucherUrl = "/api/giam-gia";
    const campaignUrl = '/api/giam-gia-san-pham';

    function renderTable(list, currentPage, perPage){
        let html = '';

        list.forEach((gg, index) => {
            let stt = (currentPage - 1) * perPage + index + 1;

            html += `
                <tr>
                    <td>${stt}</td>
                    <td>${gg.ten_giam_gia ?? ''}</td>
                    <td>${gg.loai_giam_gia ? 'Phần trăm' : 'Cố định'}</td>
                    <td>${gg.ma_giam_gia}</td>
                    <td>${gg.gia_tri}</td>
                    <td>${gg.so_luong}</td>
                    <td>${gg.ngay_bat_dau}</td>
                    <td>${gg.ngay_het_han}</td>
                    <td>${gg.trang_thai ? 'Hoạt động':'Khóa'}</td>
                    <td>
                        <div class="action-group">
                            <a href="/quan-ly/giam-gia/edit/${gg.id}" class="btn-edit">Sửa</a>
                            <button onclick='remove(${gg.id}, ${JSON.stringify(gg.ten_giam_gia)})'>Xóa</button>
                        </div>
                    </td>
                </tr>
            `;
        });

        $('#voucher-tbody').html(html);
    }

    function renderPagination(res){
        let p = '';
        let total = res.last_page;

        p += `
            <button
                ${res.current_page == 1 ? 'disabled' : ''}
                onclick="loadData(${res.current_page - 1})">
                Trước
            </button>
        `;

        let maxShow = Math.min(10, total);

        for(let i=1;i<=maxShow;i++){
            let active = res.current_page == i ? 'active-page' : '';

            p += `
                <button class="${active}"
                        onclick="loadData(${i})">
                    ${i}
                </button>
            `;
        }

        if(total > 12){
            p += `<button disabled>...</button>`;

            for(let i=total-1;i<=total;i++){
                let active = res.current_page == i ? 'active-page' : '';

                p += `
                    <button class="${active}"
                            onclick="loadData(${i})">
                        ${i}
                    </button>
                `;
            }
        }

        p += `
            <button
                ${res.current_page == total ? 'disabled' : ''}
                onclick="loadData(${res.current_page + 1})">
                Sau
            </button>
        `;

        $('#voucher-pagination').html(p);
    }

    window.loadData = function(page = 1){

        ajaxRequest({
            url: voucherUrl,
            type: 'GET',

            loading:false,
            showSuccess:false,

            data:{
                page: page,
                search: $('#voucher-search').val().trim()
            },

            successCallback:function(res){
                renderTable(res.data, res.current_page, res.per_page);
                renderPagination(res);
            }
        });
    }

    window.remove = function(id, ten){

        if(!confirm(`Bạn muốn xóa giảm giá "${ten}" ?`)) return;

        ajaxRequest({
            url: voucherUrl + '/' + id,
            type: 'POST',
            data:{
                _method:'DELETE'
            },

            successCallback:function(){
                loadData();
            }
            
        });
    }

    let timer;

    $('#voucher-search').on('input', function () {
        clearTimeout(timer);

        timer = setTimeout(() => loadData(1), 300);
    });

    loadData();

    //Sp giảm giá

    function renderCampaignTable(list,currentPage,perPage)
    {

        let html = '';

        list.forEach((sp,index)=>{

            let stt =
                (currentPage - 1)
                * perPage
                + index + 1;

            let phanTram = 0;
            if(sp.gia_ban > 0){
                phanTram = Math.round(
                    ((sp.gia_ban - sp.gia_khuyen_mai)
                    / sp.gia_ban) * 100
                );
            }

            html += `
                <tr>
                    <td>${stt}</td>
                    <td>${sp.sanpham?.ten_san_pham ?? ''}</td>
                    <td>${sp.ten_phu ?? ''}</td>
                    <td>${Number(sp.gia_ban).toLocaleString()}</td>
                    <td>${Number(sp.gia_khuyen_mai).toLocaleString()}</td>
                    <td>
                        <span class="discount-badge">
                            ${phanTram}%
                        </span>
                    </td>
                    <td>
                        <button onclick="huyKhuyenMai(${sp.id})">
                            Hủy KM
                        </button>
                    </td>
                </tr>
            `;
        });

        $('#campaign-tbody').html(html);
    }

    window.loadCampaignData = function(page = 1){

        ajaxRequest({

            url: campaignUrl,
            type: 'GET',

            loading:false,
            showSuccess:false,

            data:{
                page: page,
                search: $('#campaign-search').val().trim()
            },

            successCallback:function(res){

                renderCampaignTable(
                    res.data,
                    res.current_page,
                    res.per_page
                );

                renderCampaignPagination(res);
            }
        });
    }

    function renderCampaignPagination(res){

        let p = '';

        p += `
            <button
                ${res.current_page == 1 ? 'disabled' : ''}
                onclick="loadCampaignData(${res.current_page - 1})">
                Trước
            </button>
        `;

        for(let i=1;i<=res.last_page;i++){

            p += `
                <button
                    class="${
                        res.current_page == i
                        ? 'active-page'
                        : ''
                    }"
                    onclick="loadCampaignData(${i})">

                    ${i}

                </button>
            `;
        }

        p += `
            <button
                ${
                    res.current_page == res.last_page
                    ? 'disabled'
                    : ''
                }
                onclick="loadCampaignData(${res.current_page + 1})">

                Sau

            </button>
        `;

        $('#campaign-pagination').html(p);
    }

    let campaignTimer;
    $('#campaign-search').on('input', function(){

        clearTimeout(campaignTimer);

        campaignTimer = setTimeout(() => {

            loadCampaignData(1);

        },300);
    });

    loadCampaignData();

    window.huyKhuyenMai = function(id){

        if(!confirm('Bạn muốn hủy khuyến mãi sản phẩm này?')){
            return;
        }

        ajaxRequest({

            url:'/api/giam-gia-san-pham/' + id,

            type:'POST',

            data:{
                _method:'PUT',
                gia_khuyen_mai:null
            },

            successCallback:function(){

                loadCampaignData();
            }
        });
    }

});

//Chuyển tab
function showTab(tab, btn){

    $('#voucher-tab').hide();
    $('#campaign-tab').hide();

    $('.tab-item').removeClass('active');

    if(tab === 'voucher'){
        $('#voucher-tab').show();
    }else{
        $('#campaign-tab').show();
    }

    $(btn).addClass('active');
}



</script>
@endsection
