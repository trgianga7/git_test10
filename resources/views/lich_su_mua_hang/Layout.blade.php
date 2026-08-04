<!DOCTYPE html>
<html>
<head>
    <title>Lịch sử mua hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('css/QuanLy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ThongBao.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Load.css') }}">
</head>
</head>
<body>
<div class="container mt-4">

    <h1>Lịch sử mua hàng</h1>

    @yield('bang')
    @yield('back')
    {{--@yield('xem_chi_tiet')  --}}  
    <div id="toast-container"></div>

</div>

<div id="global-loader" class="loader-overlay">
    <div class="spinner"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
/*$(function(){
    $('.select2').select2({
        width:'250px',
        minimumResultsForSearch:0
    });
});*/
</script>

<script src="{{ asset('js/Load.js') }}"></script>
<script src="{{ asset('js/ThongBao.js') }}"></script>
@yield('scripts') 
</body>
</html>