<!DOCTYPE html>
<html>
<head>
    <title>Quản lý số dư</title>
    <link rel="stylesheet" href="{{ asset('css/QuanLy.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ThongBao.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Load.css') }}">
</head>
<body>
<div class="container mt-4">

    <h1>Quản lý số dư</h1>

    <div class="mb-2">
        <label>Số dư còn lại: </label><br>
        <input type="text" class="form-control" value="{{ $khachHang->vi }}" disabled><br>
    </div><br>

    <div class="mb-2">
        <label>Điểm: </label><br>
        <input type="text" class="form-control" value="{{ $khachHang->diem }}" disabled><br>
    </div><br>

    <a href="{{ url('/trang-chu') }}">Về trang chủ</a>

</div>

<div id="global-loader" class="loader-overlay">
    <div class="spinner"></div>
</div>

<script>
window.toastSuccess = @json(session('success'));
window.validationErrors = @json($errors->all());
</script>

<script src="{{ asset('js/Load.js') }}"></script>
<script src="{{ asset('js/ThongBao.js') }}"></script>
@yield('scripts') 
</body>
</html>