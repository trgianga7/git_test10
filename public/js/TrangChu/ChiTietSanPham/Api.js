/*const Api = {

    chiTiet(ma_sp) {

        return $.get('/api/san-pham-view/' + ma_sp);

    }

};*/
const Api = {

    chiTiet(ma, page = 1) {

        return $.get('/api/san-pham-view/' + ma, {
            page: page
        });

    },

    danhGia(formData) {

        return $.ajax({

            url: '/api/danh-gia',

            type: 'POST',

            data: formData,

            processData: false,

            contentType: false,

            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            }

        });

    }
    
};

const CartApi = {

    them(ma_sp, so_luong){

        return ajaxRequest({

            url: '/api/gio-hang/them',

            type: 'POST',

            data: {
                ma_sp,
                so_luong
            }

        });

    }

};

