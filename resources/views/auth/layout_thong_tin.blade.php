@extends('DanhSach')

@section('title', 'Thông tin cá nhân')

@section('content')

    @section('thong_tin')
        @include('auth.quan_ly_thong_tin')
    @endsection

    @section('dia_chi')
        @include('auth.quan_ly_dia_chi')
    @endsection

@endsection


