const Event={

    bindGallery(){

        $('.thumb').click(function(){

            $('#mainImage').attr(
                'src',
                $(this).attr('src')
            );

        });

    },

    bindProductAction(){

        // tăng
        $(document)
        .off('click.qtyPlus')
        .on('click.qtyPlus', '.qty-plus', function(){

            const input = $(this).siblings('input');

            const max = Number(input.attr('max'));

            let value = Number(input.val());

            if(value < max){

                input.val(value + 1);

            }

        });

        // giảm
        $(document)
        .off('click.qtyMinus')
        .on('click.qtyMinus', '.qty-minus', function(){

            const input = $(this).siblings('input');

            let value = Number(input.val());

            if(value > 1){

                input.val(value - 1);

            }

        });

        // nhập tay
        $(document)
        .off('input.qty')
        .on('input.qty','.quantity-box input',function(){

            const max = Number($(this).attr('max'));

            let value = Number($(this).val());

            if(isNaN(value) || value < 1)
                value = 1;

            if(value > max)
                value = max;

            $(this).val(value);

        });

        // thêm giỏ
        $(document)
        .off('click.cart')
        .on('click.cart','.btn-cart-detail',function(){
            console.log('click');

            const quantity = Number($('.quantity-box input').val());

            const ma_sp = $('#id_spct').val();

            CartApi.them(ma_sp, quantity);

        });

    },

    bindQuantity() {

        $(document)
            .off('click.qty')
    
            .on('click.qty', '.qty-plus', function () {
    
                const input = $(this).siblings('input');
    
                let value = parseInt(input.val());
    
                const max = parseInt(input.attr('max'));
    
                if (value < max) {
    
                    input.val(value + 1);
    
                }
    
            })
    
            .on('click.qty', '.qty-minus', function () {
    
                const input = $(this).siblings('input');
    
                let value = parseInt(input.val());
    
                if (value > 1) {
    
                    input.val(value - 1);
    
                }
    
            });
    
    },

    bindCart() {

        $(document)
    
            .off('click.cart')
    
            .on('click.cart', '.btn-cart', function () {
    
                ajaxRequest({
    
                    url: '/api/gio-hang/them',
    
                    type: 'POST',
    
                    data: {
    
                        ma_sp: $('#id_spct').val(),
    
                        so_luong: Number($('.quantity-box input').val())
    
                    },
    
                    successCallback: function () {
    
                        Header.loadCartCount();
    
                    }
    
                });
    
            });
    
    },

    bindBuyNow(){

        $(document)
    
        .off('click.buy')
    
        .on('click.buy','.btn-buy',function(){
    
            ajaxRequest({
    
                url:'/api/mua-ngay',
    
                type:'POST',
    
                data:{
    
                    ma_sp:$('#id_spct').val(),
    
                    so_luong:Number($('.qty-input').val())
    
                },
    
                showSuccess:false,
    
                successCallback:function(){
    
                    window.location='/thanh-toan';
    
                }
    
            });
    
        });
    
    }
    

};