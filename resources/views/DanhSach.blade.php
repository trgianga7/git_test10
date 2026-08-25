@php
    use App\Http\Controllers\AuthController\HienThiChucNangController;

    $menu = HienThiChucNangController::getMenu();

@endphp

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
            <a class="logo-trang-chu" href="/quan-ly">123</a>
        </div>

        <div class="header-right">
            @include('partials.icon_login')
        </div>
    </header>

    <div class="main">

        <aside class="sidebar">

            <h3>Chức năng</h3>

            <!--<ul>
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
            </ul>-->

            <ul>
                @if($menu['nguoi_dung'])
                    <li><a href="/quan-ly/nguoi-dung">Người dùng</a></li>
                @endif

                @if($menu['chuc_vu'])
                    <li><a href="/quan-ly/chuc-vu">Chức vụ</a></li>
                @endif

                @if($menu['dia_chi'])
                    <li><a href="/quan-ly/dia-chi">Địa chỉ</a></li>
                @endif

                @if($menu['khach_hang'])
                    <li><a href="/quan-ly/khach-hang">Khách hàng</a></li>
                @endif

                @if($menu['danh_muc'])
                    <li><a href="/quan-ly/danh-muc">Danh mục</a></li>
                @endif

                @if($menu['san_pham'])
                    <li><a href="/quan-ly/san-pham">Sản phẩm</a></li>
                @endif

                @if($menu['san_pham_chi_tiet'])
                    <li><a href="/quan-ly/san-pham-chi-tiet">Sản phẩm chi tiết</a></li>
                @endif

                @if($menu['hoa_don'])
                    <li><a href="/quan-ly/hoa-don">Hóa đơn</a></li>
                @endif

                @if($menu['giam_gia'])
                    <li><a href="/quan-ly/giam-gia">Giảm giá</a></li>
                @endif

                @if($menu['thong_ke'])
                    <li><a href="/quan-ly/thong-ke">Thống kê</a></li>
                @endif

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

<div id="toast-container"></div>

<div id="loading-overlay">
    <div class="spinner"></div>
</div>

<script src="{{ asset('js/app.js') }}"></script>

</body>

</html>