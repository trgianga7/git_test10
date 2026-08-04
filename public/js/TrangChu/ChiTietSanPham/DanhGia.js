const DanhGia = {

    render(data) {

        // Form đánh giá
        FormDanhGia.render(data.da_mua);

        // Danh sách đánh giá
        this.renderReview(data.danh_gia);

    },

    bind() {

        $(document)
            .off('click.page')
            .on(
                'click.page',
                '.page-btn',
                function () {

                    DanhGia.load($(this).data('page'));

                }
            );

    },

    renderReview(review) {

        $('#review-list').html(

            Render.reviewSummary(review.summary) +

            Render.reviewList(review)

        );

        this.bind();

    },

    load(page = 1) {

        const ma = $('#id_spct').val();

        Api.chiTiet(ma, page).done((res) => {

            this.renderReview(res.danh_gia);

        });

    }

};