<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">

    <title>@yield('title', 'Trang chủ')</title>

    <link rel="stylesheet" href="{{ asset('css/TrangChu/TrangChu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/TrangChu/Select2-TrangChu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/IconLogin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ThongBao.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Load.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <header class="header">
        <div class="header-left">
            123
        </div>

        <div class="header-center">
            <a href="{{ route('trang-chu.Home') }}">Trang chủ</a>

            <a href="#">Danh mục</a>

            <a href="{{ route('trang-chu.SanPham') }}">Sản phẩm</a>

            <a href="{{ route('gio-hang.index') }}" class="cart-link">
                Giỏ hàng
                <span id="cart-count" class="cart-badge">0</span>
            </a>
        </div>

        <div class="header-right">
            @include('partials.icon_login')
        </div>
    </header>

    

    <div class="detail-page">

        

    </div>

    <footer class="footer">
        123
    </footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/TrangChu/BadgeSoLuongGioHang.js') }}"></script>

<script>


</script>

<div id="toast-container"></div>

<div id="loading-overlay">
    <div class="spinner"></div>
</div>

</body>

</html>
