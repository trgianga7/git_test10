const SanPham = {

    data: null,

    load(page = 1) {

        const ma = $('#id_spct').val();

        Api.chiTiet(ma, page).done(res => {

            this.data = res;

            this.render();

        });

    },

    render() {

        const sp = this.data.san_pham;
    
        $('#product-detail').html(
            Render.product(sp)
        );
    
        Event.bindGallery();
    
        Event.bindProductAction();
        Event.bindBuyNow();
    
        DanhGia.render(this.data);
    
    }
};