@extends('DanhSach')

@section('title', 'Quản lý danh mục')

@section('content')

<div class="page-header">
    <h2>Danh sách danh mục</h2>

    <a href="/quan-ly/danh-muc/create">
        <button class="btn-add">Thêm mới</button>
    </a>
</div>

<div class="search-wrapper">
    <input id="search" class="search" placeholder="Tìm kiếm...">
</div>

<table>
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên danh mục</th>
            <th>Trạng thái</th>
            <th>Chức năng</th>
        </tr>
    </thead>

    <tbody id="tbody"></tbody>
</table>

<div id="pagination"></div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

    const baseUrl = "/api/danh-muc";

    function renderTable(list, currentPage, perPage){
        let html = '';

        list.forEach((dm, index) => {
            let stt = (currentPage - 1) * perPage + index + 1;

            html += `
                <tr>
                    <td>${stt}</td>
                    <td>${dm.ten_danh_muc ?? ''}</td>
                    <td>${dm.trang_thai ? 'Hoạt động':'Khóa'}</td>
                    <td>
                        <div class="action-group">
                            <a href="/quan-ly/danh-muc/edit/${dm.id}" class="btn-edit">Sửa</a>
                            <button onclick='remove(${dm.id}, ${JSON.stringify(dm.ten_danh_muc ?? '')})'>Xóa</button>
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
            }
        });
    }

    window.remove = function(id, ten){
        if(!confirm(`Bạn muốn xóa địa chỉ của "${ten}" ?`)) return;

        ajaxRequest({
            url: baseUrl + '/' + id,
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