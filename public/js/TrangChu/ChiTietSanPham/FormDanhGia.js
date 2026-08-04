const FormDanhGia = {

    render(dsDaMua) {

        if (!dsDaMua.length) {

            $('#review-form').html('');

            return;

        }

        let products = '';

        dsDaMua.forEach((item, index) => {

            products += `

                <label class="product-card">

                    <input
                        type="radio"
                        name="id_hoa_don_chi_tiet"
                        value="${item.id}"
                        ${index == 0 ? 'checked' : ''}
                    >

                    <div class="product-card-body">

                        <div class="product-name-danh-gia">

                            ${item.ten_san_pham}

                        </div>

                        <!--<div class="product-type">

                            ${item.ten_phu}

                        </div>-->
                        <div class="product-type">
                            Số lượng đã mua: ${item.so_luong}
                        </div>

                    </div>

                </label>

            `;

        });

        $('#review-form').html(`

            <div class="form-danh-gia">

                <h3>

                    Đánh giá sản phẩm

                </h3>

                <form id="formDanhGia">

                <div class="product-select">${products}</div>

                <div class="rating-wrapper">

                    <label>
                
                        Đánh giá của bạn
                
                    </label>
                
                    <div class="rating-stars">

                        <span class="star">                            
                            <span class="half left"></span>
                            <span class="half right"></span>
                            ★
                        </span>

                        <span class="star">                            
                            <span class="half left"></span>
                            <span class="half right"></span>
                            ★
                        </span>

                        <span class="star">                           
                            <span class="half left"></span>
                            <span class="half right"></span>
                            ★
                        </span>

                        <span class="star">                          
                            <span class="half left"></span>
                            <span class="half right"></span>
                            ★
                        </span>

                        <span class="star">                            
                            <span class="half left"></span>
                            <span class="half right"></span>
                            ★
                        </span>

                    </div>
                
                    <div class="rating-text">
                
                        Chưa đánh giá
                
                    </div>
                
                    <input type="hidden" id="rating-value" name="danh_gia" value="0">
                
                </div>

                <textarea id="noi_dung" name="noi_dung" maxlength="1000"></textarea>
            
                <div class="text-counter">
                    <span id="currentLength">
                        0
                    </span>
                    /1000
                </div>

                    <input id="images" type="file" name="images[]" multiple hidden>

                    <label class="upload-btn" for="images">Thêm ảnh</label>

                    <div id="previewImages"></div>

                    <button type="button">

                        Gửi đánh giá

                    </button>

                </form>

            </div>

        `);

        $('#noi_dung').on('input', function(){

            $('#currentLength').text(
        
                $(this).val().length
        
            );
        
        });

        $('#images').on('change', function(){

            $('#previewImages').html('');
        
            [...this.files].forEach(file=>{
        
                const url = URL.createObjectURL(file);
        
                $('#previewImages').append(
        
                    `<img src="${url}">`
        
                );
        
            });
        
        });

        this.bindRating();

        this.bindSubmit();

    },

    bindRating() {

        const texts = {
            0: 'Chưa đánh giá',

            0.5: 'Rất tệ',
            1: 'Rất tệ',
    
            1.5: 'Không hài lòng',
            2: 'Không hài lòng',
    
            2.5: 'Bình thường',
            3: 'Bình thường',
    
            3.5: 'Hài lòng',
            4: 'Hài lòng',
    
            4.5: 'Tuyệt vời',
            5: 'Tuyệt vời'
        };
    
        let current = Number($('#rating-value').val()) || 0;
    
        function paint(rate) {
    
            $('.rating-stars .star').each(function(index) {
    
                const value = index + 1;
    
                $(this)
                    .removeClass('active half-active');
    
                if (rate >= value) {
    
                    $(this).addClass('active');
    
                } else if (rate === value - 0.5) {
    
                    $(this).addClass('half-active');
    
                }
    
            });
    
        }
    
        function updateText(rate) {
    
            $('.rating-text').text(
                `${rate} ★ - ${texts[rate]}`
            );
    
        }
    
        paint(current);
    
        updateText(current);
    
        $(document).off('.rating');
    
        // Hover nửa trái
        $(document).on(
            'mouseenter.rating',
            '.rating-stars .half.left',
            function() {
    
                const rate = $('.rating-stars .star').index($(this).parent()) + 0.5;
    
                paint(rate);
    
                updateText(rate);
    
            }
        );
    
        // Hover nửa phải
        $(document).on(
            'mouseenter.rating',
            '.rating-stars .half.right',
            function() {
    
                const rate = $('.rating-stars .star').index($(this).parent()) + 1;
    
                paint(rate);
    
                updateText(rate);
    
            }
        );
    
        // Click nửa trái
        $(document).on(
            'click.rating',
            '.rating-stars .half.left',
            function() {
    
                current =
                    $(this).parent().index() + 0.5;
    
                $('#rating-value').val(current);
    
                paint(current);
    
                updateText(current);
    
            }
        );
    
        // Click nửa phải
        $(document).on(
            'click.rating',
            '.rating-stars .half.right',
            function() {
    
                current =
                    $(this).parent().index() + 1;
    
                $('#rating-value').val(current);
    
                paint(current);
    
                updateText(current);
    
            }
        );
    
        // Rời chuột
        $(document).on(
            'mouseleave.rating',
            '.rating-stars',
            function() {
    
                paint(current);
    
                updateText(current);
    
            }
        );
    
    },

    bindSubmit() {

        $(document)
            .off('click.submitReview')
            .on(
                'click.submitReview',
                '#formDanhGia button',
                function () {
    
                    const formData = new FormData();
    
                    formData.append('id_hoa_don_chi_tiet',
                        $('input[name=id_hoa_don_chi_tiet]:checked').val()
                    );
    
                    formData.append('danh_gia',$('#rating-value').val());
    
                    formData.append('noi_dung',$('#noi_dung').val());
    
                    const files = $('#images')[0].files;
    
                    [...files].forEach(file => {
    
                        formData.append('images[]', file);
    
                    });
    
                    Api.danhGia(formData).done(function(res){

                        if(res.message){

                            showToast(
                                res.message,
                                'success'
                            );

                        }

                        SanPham.load();

                    })
                    .fail(function(err){

                        if(err.status === 422){

                            const firstError =
                                Object.values(err.responseJSON.errors)[0][0];

                            showToast(
                                firstError,
                                'warning'
                            );

                        }else{

                            showToast(
                                err.responseJSON?.message ?? 'Có lỗi xảy ra',
                                'error'
                            );

                        }

                    });
    
                }
            );
    
    }


    
};