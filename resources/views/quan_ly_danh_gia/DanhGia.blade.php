@extends('quan_ly_danh_gia.Layout')

@include('auth.detail_login')
@include('partials.sidebar')

@section('bang')
    <form method="GET" action="{{ route('quan-ly-danh-gia.index') }}" class="mb-3">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nhập mã đánh giá hoặc nội dung">
        <button class="btn btn-secondary" type="submit">Tìm kiếm</button>
    </form>
    @include('quan_ly_danh_gia.BangDanhGia')
@endsection

@section('edit')
    @if($showEdit ?? false)
        <hr>
        @include('quan_ly_danh_gia.UpdDanhGia')
    @endif
@endsection

@section('back')
    <br>
    <a href="{{ url('/') }}">Về danh sách</a>
@endsection

@section('scripts')
<script>
window.toastSuccess = @json(session('success'));
window.validationErrors = @json($errors->all());
</script>
@endsection
