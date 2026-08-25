@extends('DanhSach')

@section('title', 'Quản lý hóa đơn')

@section('content')

<link rel="stylesheet" href="{{ asset('css/HoaDon/HoaDon.css') }}">

<div class="page-header">
    <h2>Danh sách hóa đơn</h2>

    <a href="/quan-ly/hoa-don/create">
        <button class="btn-add">Thêm mới</button>
    </a>
</div>

<div class="search-wrapper">
    <input id="search" class="search" placeholder="Tìm kiếm...">

    <select id="trang_thai_filter">
        <option value="">Tất cả trạng thái</option>
        <option value="1">Chờ xác nhận</option>
        <option value="2">Đã xác nhận</option>
        <option value="3">Đang giao</option>
        <option value="4">Đã giao</option>
        <option value="5">Hoàn thành</option>
        <option value="6">Đã hủy</option>
    </select>
</div>

<span class="ghi-chu-hoa-don">Ấn vào mã hóa đơn để xem chi tiết*</span>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã hóa đơn</th>
                <th>Tên khách hàng</th>
                <th>Tên người nhận</th>
                <th>Tổng tiền</th>
                <th>Loại hình</th>
                <th>Trạng thái thanh toán</th>
                <th>Ngày tạo</th>
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
function giamDoDai(text, limit = 10) {
    if (!text) return '';
    return text.length > limit
        ? text.substring(0, limit) + '...'
        : text;
}

$(document).ready(function () {

    const baseUrl = "/api/hoa-don";

    function renderTable(list, currentPage, perPage){
        let html = '';

        list.forEach((hd, index) => {
            let stt = (currentPage - 1) * perPage + index + 1;
            const loaiHinh = {0: 'COD', 1: 'Số dư', 2: 'Chuyển khoản'};

            html += `
                <tr>
                    <td>${stt}</td>
                    <td>
                        <a href="/quan-ly/hoa-don/view-info/${hd.ma_hd}" class="link-ma-hd">
                            ${hd.ma_hd}
                        </a>
                    </td>
                    <td>${giamDoDai(hd.khachhang?.ten_khach_hang ?? 'Không có')}</td>
                    <td>${giamDoDai(hd.ten_nguoi_nhan ?? 'Lỗi')}</td>                   
                    <td>${Number(hd.tong_tien_thuc).toLocaleString()}</td>
                    <td>${loaiHinh[hd.loai_hinh] ?? 'Không xác định'}</td>
                    <td>${hd.trang_thai_thanh_toan ? 'Đã thanh toán' : 'Chưa thanh toán'}</td>
                    <td>${formatDate(hd.ngay_tao)}</td>
                    <td>${getTrangThaiBadge(hd.trangthaihd?.trang_thai)}</td>
                    <td>
                        <div class="action-group">
                            <a href="/quan-ly/hoa-don/edit/${hd.ma_hd}" class="btn-edit">Sửa</a>
                            <button onclick='remove(${JSON.stringify(hd.ma_hd)})'>Xóa</button>
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
                search: $('#search').val().trim(),
                trang_thai: $('#trang_thai_filter').val()
            },

            successCallback:function(res){
                renderTable(res.data, res.current_page, res.per_page);
                renderPagination(res);
                $('#tong-ban-ghi').text(`Tổng hóa đơn: ${res.total}`);
            }
        });  
        
    }
    

    window.remove = function(ma_hd){
        if(!confirm(`Bạn muốn xóa hóa đơn "${ma_hd}" ?`)) return;

        ajaxRequest({
            url: baseUrl + '/' + ma_hd,
            type:'POST',
            data:{
                _method:'DELETE',
            },
            
            successCallback:function(){
                loadData();
            }
        });
    }

    function getTrangThaiBadge(trangThai) {

        const map = {
            'Chờ xác nhận': 'badge-warning',
            'Đã xác nhận': 'badge-info',
            'Đang giao': 'badge-primary',
            'Đã giao': 'badge-success',
            'Hoàn thành': 'badge-complete',
            'Đã hủy': 'badge-danger'
        };

        return `
            <span class="badge-status ${map[trangThai] || 'badge-default'}">
                ${trangThai}
            </span>
        `;
    }

    function formatDate(dateString) {

        let d = new Date(dateString);

        let ngay = String(d.getDate()).padStart(2, '0');
        let thang = String(d.getMonth() + 1).padStart(2, '0');
        let nam = d.getFullYear();

        let gio = String(d.getHours()).padStart(2, '0');
        let phut = String(d.getMinutes()).padStart(2, '0');
        let giay = String(d.getSeconds()).padStart(2, '0');

        return `${ngay}/${thang}/${nam} ${gio}:${phut}:${giay}`;
    }

    let timer;

    $('#search').on('input', function () {
        clearTimeout(timer);

        timer = setTimeout(() => loadData(1), 300);
    });

    loadData();

    $('#trang_thai_filter').on('change', function () {
        loadData(1);
    });

    
});
</script>
@endsection