<!DOCTYPE html>
<html>
<head>
    <title>Quản lý đánh giá</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('css/QuanLy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ThongBao.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Load.css') }}">
</head>
<body> 
<div class="container mt-4">

    <h1>Quản lý đánh giá</h1>

    {{-- @yield('scripts') --}}
    @yield('bang')
    @yield('back')
    @yield('add')
    @yield('edit')    
    <div id="toast-container"></div>
</div>
 
<div id="global-loader" class="loader-overlay">
    <div class="spinner"></div>
</div>

<script src="{{ asset('js/Load.js') }}"></script>
<script src="{{ asset('js/ThongBao.js') }}"></script>

@yield('scripts') 
</body>
</html>