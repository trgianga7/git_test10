<link rel="stylesheet" href="{{ asset('css/Login.css') }}">
<link rel="stylesheet" href="{{ asset('css/ThongBao.css') }}">
<link rel="stylesheet" href="{{ asset('css/Load.css') }}">

<meta name="csrf-token" content="{{ csrf_token() }}">

<title>Đăng nhập</title>
<div class="auth-wrapper">

        <h2>Đăng nhập</h2>

        <label>Tài khoản</label>
        <input type="text" name="username" id="username">

        <label>Mật khẩu</label>
        <input type="password" name="password" id="password">

        <button type="button" onclick="login()">Đăng nhập</button>

        <a href="{{ url('/dang-ky') }}" class="auth-link">
            Chưa có tài khoản? Đăng ký
        </a>
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

function login(){

    $('.error').removeClass('error');

    if(!validateField('#username', 'Chưa nhập tài khoản')) return;
    if(!validateField('#password', 'Chưa nhập mật khẩu')) return;

    ajaxRequest({
        url: '/login',
        type: 'POST',

        data: {
            username: $('#username').val(),
            password: $('#password').val()
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
