function loadSoLuongGioHang()
{
    ajaxRequest({

        url:'/api/gio-hang/so-luong',

        type:'GET',

        loading:false,

        showSuccess:false,

        showError:false,

        successCallback:function(res){

            let badge = $('#cart-count');

            if(!badge.length)
            {
                return;
            }

            badge.text(
                res.so_luong > 0
                ? res.so_luong
                : ''
            );
        }
    });
}