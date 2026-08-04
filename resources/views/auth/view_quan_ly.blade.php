<!DOCTYPE html>
<html>
<head>
    <title>Quản lý thông tin</title>
    <!--<link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="{{ asset('css/QuanLy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ThongBao.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Load.css') }}">
</head>
<body>
<div class="container mt-4">
    @yield('thong_tin')

    @yield('dia_chi')
    
    @yield('back')
    <div id="toast-container"></div>
</div>

<div id="global-loader" class="loader-overlay">
    <div class="spinner"></div>
</div>
<script src="{{ asset('js/Load.js') }}"></script>
<script src="{{ asset('js/ThongBao.js') }}"></script>

</body>
</html>
