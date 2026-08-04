<h3>Thông tin cá nhân</h3>

<form method="POST" 
      action="{{ isset($admin) ? route('quan_ly_thong_tin_update_admin') : route('quan_ly_thong_tin_update_customer') }}" 
      enctype="multipart/form-data">
    @csrf

    <div style="display:flex; gap:20px; align-items:flex-start;">
        <div style="flex:2;">
            @if(isset($admin))
                <input type="hidden" name="id" value="{{ $admin->id }}">

                <div class="mb-2">
                    <label>Họ tên:</label><br>
                    <input type="text" name="ten_nguoi_dung" class="form-control" value="{{ $admin->ten_nguoi_dung }}" required>
                </div><br>

                <div class="mb-2">
                    <label>Sdt liên hệ:</label><br>
                    <input type="text" name="sdt_lien_he" class="form-control" value="{{ $admin->sdt_lien_he }}">
                </div><br>

                <div class="mb-2">
                    <label>Chức vụ:</label><br>
                    <input type="text" name="id_chuc_vu" class="form-control" value="{{ $admin->chucvu->ten_chuc_vu }}" disabled>
                </div><br>

                <div class="mb-2">
                    <label>Email:</label><br>
                    <input type="email" name="email" class="form-control" value="{{ $admin->email }}" disabled>
                </div><br>

                <div class="mb-2">
                    <label>Mật khẩu:</label><br>
                    <input type="password" name="mat_khau" class="form-control">
                </div><br>

            @elseif(isset($customer))
                <input type="hidden" name="id" value="{{ $customer->id }}">

                <div class="mb-2">
                    <label>Họ tên:</label><br>
                    <input type="text" name="ten_khach_hang" class="form-control" value="{{ $customer->ten_khach_hang }}" required>
                </div><br>

                <div class="mb-2">
                    <label>Số điện thoại (tài khoản):</label><br>
                    <input type="text" name="sdt" class="form-control" value="{{ $customer->sdt }}" disabled>
                </div><br>

                <div class="mb-2">
                    <label>Số điện thoại liên hệ:</label><br>
                    <input type="text" placeholder="Sdt dùng để liên hệ" name="sdt_moi" class="form-control" value="{{ $customer->sdt_moi ?? '' }}">
                </div><br>

                <div class="mb-2">
                    <label>Mật khẩu:</label><br>
                    <input type="password" name="mat_khau" class="form-control">
                </div><br>
            @endif
            <button type="submit" class="btn btn-primary">Cập nhật</button><br><br>
        </div>

        <div style="flex:1; text-align:center;">
            <label>Ảnh đại diện</label><br>
            @php
                $avatarUrl = isset($admin) 
                                ? ($admin->anh_dai_dien ? asset('storage/' . $admin->anh_dai_dien) : null)
                                : (isset($customer) ? ($customer->anh_dai_dien ? asset('storage/' . $customer->anh_dai_dien) : null) : null);
            @endphp

            @if($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="Avatar" style="width:120px; height:120px; border-radius:50%; object-fit:cover; margin-bottom:10px;">
            @else
                <div style="width:120px; height:120px; line-height:120px; border-radius:50%; background:#ccc; font-size:50px; margin:auto; margin-bottom:10px;">
                    👤
                </div>
            @endif

            <input type="file" name="anh_dai_dien" class="form-control" accept="image/*"><br>
            <div class="preview-text" style="display:none; margin-top:10px;">Ảnh cập nhật mới đã chọn:</div>
            <img class="preview-avatar" style="width:120px; height:120px; border-radius:50%; object-fit:cover; display:none; margin-left:30px; margin-top:5px;">
        </div>

    </div>
</form>

@if(isset($customer))
<a href="{{ route('quan_ly_thong_tin_customer', ['them_dia_chi' => 1]) }}" class="btn btn-success">
    Thêm mới địa chỉ
</a>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.querySelector('input[name="anh_dai_dien"]');
    const previewImg = document.querySelector('.preview-avatar');
    const previewText = document.querySelector('.preview-text');

    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) {
            previewImg.style.display = 'none';
            previewText.style.display = 'none';
            return;
        }

        const url = URL.createObjectURL(file);
        previewImg.src = url;
        previewImg.style.display = 'inline-block';
        previewText.style.display = 'block';

        // Lùi sang phải so với ảnh gốc
        previewImg.style.marginLeft = '20px';
    });
});
</script>