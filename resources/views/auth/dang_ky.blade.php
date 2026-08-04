<link rel="stylesheet" href="{{ asset('css/Login.css') }}">
<link rel="stylesheet" href="{{ asset('css/ThongBao.css') }}">
<link rel="stylesheet" href="{{ asset('css/Load.css') }}">

<meta name="csrf-token" content="{{ csrf_token() }}">

<title>Đăng ký</title>
<div class="auth-wrapper">
 
        <h2>Đăng ký</h2>

        <label>Số điện thoại</label>
        <input type="text" name="sdt" id="sdt">

        <label>Tên khách hàng</label>
        <input type="text" name="ten_khach_hang" id="ten_khach_hang">

        <label>Mật khẩu</label>
        <input type="password" name="password" id="password">

        <label>Nhập lại mật khẩu</label>
        <input type="password" name="password_confirmation" id="password_confirmation">

        <button type="button" onclick="register()">Đăng ký</button>
        <a href="{{ url('/login') }}" class="auth-link">Đăng nhập</a>

</div>

<div id="toast-container"></div>

<div id="loading-overlay">
    <div class="spinner"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script>

$(document).ready(function () {
    $('input').on('input', function () {
        $(this).removeClass('error');
    });
});

function validateField(selector, message){

    let value = $(selector).val();

    if(!value || !value.trim()){

        if($(selector).hasClass('select2')){
            $(selector).next('.select2-container').addClass('error');
        }else{
            $(selector).addClass('error');
        }

        showToast(message, 'warning');

        return false;
    }

    return true;
}

function register(){

    $('.error').removeClass('error');

    if(!validateField('#sdt', 'Chưa nhập số điện thoại')) return;
    if(!validateField('#ten_khach_hang', 'Chưa nhập tên khách hàng')) return;
    if(!validateField('#password', 'Chưa nhập mật khẩu')) return;
    if(!validateField('#password_confirmation', 'Chưa nhập lại mật khẩu')) return;

    if(
        $('#password').val() !==
        $('#password_confirmation').val()
    ){
        showToast(
            'Mật khẩu xác nhận không đúng',
            'warning'
        );
        return;
    }

    ajaxRequest({
        url: '/dang-ky',
        type: 'POST',

        data: {
            sdt: $('#sdt').val().trim(),
            ten_khach_hang: $('#ten_khach_hang').val().trim(),
            password: $('#password').val(),
            password_confirmation: $('#password_confirmation').val()
        },

        showSuccess: false,

        successCallback: function(res){
            redirectWithToast(
                res.redirect,
                res.message
            );
        }
    });
}

$('input').on('keydown', function(e){
    if(e.key === 'Enter'){
        login();
    }
});

</script>

