@extends('DanhSach')

@section('title', 'Menu cá nhân')

@section('content')

<link rel="stylesheet" href="{{ asset('css/MenuCaNhan.css') }}">

<h3>Menu cá nhân</h3>

<div class="menu-ca-nhan">

    @auth('admin')
        <a class="menu-item" href="{{ route('quan_ly_thong_tin') }}">
            Thông tin cá nhân
        </a>

    @endauth

    @auth('customer')
        <a class="menu-item" href="{{ route('quan_ly_thong_tin_customer') }}">
            Thông tin cá nhân
        </a>

        <a class="menu-item" href="{{ route('lich-su-mua-hang.index') }}">
            Lịch sử mua hàng
        </a>

        <a class="menu-item" href="{{ route('quan-ly-so-du.xemSoDu') }}">
            Số dư
        </a>
    @endauth

    @auth('admin')
        <a class="menu-back" href="{{ url('/') }}">Quay lại</a>
    @endauth

    @auth('customer')
        <a class="menu-back" href="{{ url('/trang-chu') }}">Quay lại</a>
    @endauth

</div>

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){

    $('.menu-item, .menu-back').on('click', function(){
        showLoading();
    });

});
</script>
@endsection