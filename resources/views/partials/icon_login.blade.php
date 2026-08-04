    @auth('admin')
        @php
            $admin = Auth::guard('admin')->user();
            $tenChucVu = $admin->chucvu->ten_chuc_vu ?? 'Chưa xác định';
            $avatar = $admin->anh_dai_dien
                ? asset('storage/' . $admin->anh_dai_dien)
                : null;
        @endphp

        <div class="user-box">
            <a href="{{ route('danh_sach_ca_nhan') }}" class="user-info">
                @if($avatar)
                    <img src="{{ $avatar }}" class="avatar">
                @else
                    <span class="avatar">👤</span>
                @endif

                <div>
                    <div class="name">{{ $admin->ten_nguoi_dung }}</div>
                    <div class="role">{{ $tenChucVu }}</div>
                </div>
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    Đăng xuất
                </button>
            </form>
        </div> 
    @endauth

    @auth('customer')
        @php
            $customer = Auth::guard('customer')->user();
        @endphp

        <div class="user-box">

            <a href="#" class="user-info">

                <span class="avatar">👤</span>

                <div>
                    <div class="name">
                        {{ $customer->ten_khach_hang }}
                    </div>

                    <div class="role">
                        Khách hàng
                    </div>
                </div>

            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="logout-btn">
                    Đăng xuất
                </button>
            </form>

        </div>
    @endauth

    @if(!Auth::guard('admin')->check() &&
        !Auth::guard('customer')->check())

        <a href="{{ route('dang-nhap') }}"
        class="login-link">
            Đăng nhập
        </a>

    @endif