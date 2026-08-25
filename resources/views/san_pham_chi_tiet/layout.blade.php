@extends('DanhSach')

@section('title', 'Quản lý sản phẩm chi tiết')

@section('content')

<link rel="stylesheet" href="{{ asset('css/SanPhamChiTiet/SanPhamChiTiet.css') }}">

<div class="page-header">
    <h2>Danh sách sản phẩm chi tiết</h2>

    <a href="/quan-ly/san-pham-chi-tiet/create">
        <button class="btn-add">Thêm mới</button>
    </a>
</div>

<div class="search-wrapper">
    <input id="search" class="search" placeholder="Tìm kiếm...">
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã sản phẩm</th>
                <th>Ảnh sản phẩm</th>
                <th>Tên sản phẩm</th>
                <th>Tên phụ</th>
                <th>Giá bán</th>
                <th>Giá khuyến mãi</th>
                <th>Số lượng</th>
                <th>Khuyến mãi</th>
                <th>Trạng thái</th>
                <th>Chức năng</th> 
            </tr>
        </thead>

        <tbody id="tbody"></tbody>
    </table>
</div>

<div id="tong-ban-ghi" class="tong-ban-ghi"></div>
<div id="pagination"></div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

function giamDoDai(text, limit = 15) {
    if (!text) return '';
    return text.length > limit
        ? text.substring(0, limit) + '...'
        : text;
}

$(document).ready(function () {

    const baseUrl = "/api/san-pham-chi-tiet";

    function renderTable(list, currentPage, perPage){
        let html = '';

        list.forEach((spct, index) => {

            let stt = (currentPage - 1) * perPage + index + 1;

            let anh = spct.anh_dai_dien
                ? `<img src="/storage/${spct.anh_dai_dien}"
                        width="50"
                        height="50"
                        style="object-fit:cover; border-radius:6px;">`
                : 'Không có';

            html += `
                <tr>
                    <td>${stt}</td>
                    <td>${giamDoDai(spct.ma_sp)}</td>
                    <td>${anh}</td>
                    <td>${giamDoDai(spct.sanpham?.ten_san_pham)}</td>
                    <td>${giamDoDai(spct.ten_phu)}</td>
                    <td>${Number(spct.gia_ban).toLocaleString('vi-VN')}</td>
                    <td>${Number(spct.gia_khuyen_mai).toLocaleString('vi-VN')}</td>
                    <td>${spct.so_luong}</td>
                    <td>${spct.khuyen_mai ?? 'Không có'}</td>
                    <td>${spct.trang_thai ? 'Hoạt động':'Khóa'}</td>
                    <td>
                        <div class="action-group">
                            <a href="/quan-ly/san-pham-chi-tiet/edit/${spct.ma_sp}" class="btn-edit">Sửa</a>
                            <button onclick='remove("${spct.ma_sp}", ${JSON.stringify(spct.sanpham?.ten_san_pham)},${JSON.stringify(spct.ten_phu)})'>Xóa</button>
                        </div>    
                    </td>
                </tr>
            `;
        });

        $('#tbody').html(html);
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

        $('#pagination').html(p);
    }

    window.loadData = function(page = 1){
        ajaxRequest({
            url: baseUrl,
            type: 'GET',

            loading:false,
            showSuccess:false,

            data:{
                page: page,
                search: $('#search').val().trim()
            },

            successCallback:function(res){
                renderTable(res.data, res.current_page, res.per_page);
                renderPagination(res);
                $('#tong-ban-ghi').text(`Tổng sản phẩm: ${res.total}`);
            }
        });
    }
    

    window.remove = function(ma_sp, ten, tenphu){
        if(!confirm(`Bạn muốn xóa phân loại "${tenphu}" của sản phẩm "${ten}" ?`)) return;

        ajaxRequest({
            url: baseUrl + '/' + ma_sp,
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

    $('#search').on('input', function () {
        clearTimeout(timer);

        timer = setTimeout(() => loadData(1), 300);
    });

    loadData();
});
</script>
@endsection