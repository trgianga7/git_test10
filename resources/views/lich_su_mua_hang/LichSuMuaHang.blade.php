@extends('lich_su_mua_hang.Layout')

@section('bang')
    <form method="GET" action="{{ route('lich-su-mua-hang.index') }}" class="mb-3">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nhập mã hóa đơn">
        <button class="btn btn-secondary" type="submit">Tìm kiếm</button>
    </form>
    @include('lich_su_mua_hang.BangHoaDonCaNhan')
@endsection

@section('back')
    <br>
    <a href="{{ url('/danh-sach-ca-nhan') }}">Về menu cá nhân</a>
@endsection

@section('scripts')
<script>
window.toastSuccess = @json(session('success'));
window.validationErrors = @json($errors->all());
</script>
@endsection