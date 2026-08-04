<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">

    <title>@yield('title', 'Quản lý')</title>

    <link rel="stylesheet" href="{{ asset('css/GiaoDienQuanLy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/IconLogin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ThongBao.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Load.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <header class="header">
        <div class="header-left">
            123
        </div>

        <div class="header-right">
            @include('partials.icon_login')
        </div>
    </header>

    <div class="main">

        <aside class="sidebar">

            <h3>Chức năng</h3>

            <ul>
                <li><a href="/quan-ly/nguoi-dung">Người dùng</a></li>
                <li><a href="/quan-ly/chuc-vu">Chức vụ</a></li>
                <li><a href="/quan-ly/dia-chi">Địa chỉ</a></li>
                <li><a href="/quan-ly/khach-hang">Khách hàng</a></li>
                <li><a href="/quan-ly/danh-muc">Danh mục</a></li>
                <li><a href="/quan-ly/san-pham">Sản phẩm</a></li>
                <li><a href="/quan-ly/san-pham-chi-tiet">SP chi tiết</a></li>
                <li><a href="/quan-ly/hoa-don">Hóa đơn</a></li>
                <li><a href="/quan-ly/giam-gia">Giảm giá</a></li>
                <li><a href="/quan-ly/thong-ke">Thống kê</a></li>
            </ul>

        </aside>

        <main class="content">

            <div class="card">
                @yield('content')
            </div>

        </main>

    </div>

    <footer class="footer">
        123
    </footer>
    
    @yield('scripts')
</body>

<div id="toast-container"></div>

<div id="loading-overlay">
    <div class="spinner"></div>
</div>

<script src="{{ asset('js/app.js') }}"></script>

</html>