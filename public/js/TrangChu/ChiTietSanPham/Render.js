const Render = {

    product(sp){

        return `
    
            <div class="detail-top">
    
                <div class="detail-left">
    
                    ${this.gallery(sp)}
    
                </div>
    
                <div class="detail-right">
    
                    ${this.info(sp)}
    
                </div>
    
            </div>
    
            ${this.description(sp)}
    
        `;
    
    },

    info(sp) {

        return `
    
            <h2 class="product-name">
                ${sp.san_pham_goc.ten_san_pham}
            </h2>
    
            ${this.variant(sp)}
    
            <div class="product-price">
                ${this.price(sp)}
            </div>
    
            <div class="product-stock">
                Còn lại <b>${sp.so_luong}</b> sản phẩm
            </div>
    
            ${this.action(sp)}
    
        `;
    
    },

    action(sp){

        return `
    
            <div class="product-action">
    
                <div class="quantity-box">
    
                    <button class="qty-minus">-</button>
    
                    <input
                        class="qty-input"
                        type="number"
                        value="1"
                        min="1"
                        max="${sp.so_luong}"
                    >
    
                    <button class="qty-plus">+</button>
    
                </div>
    
                <button class="btn-cart-detail">
    
                    🛒 Thêm vào giỏ
    
                </button>
    
                <button class="btn-buy">
    
                    Mua ngay
    
                </button>
    
            </div>
    
        `;
    
    },

    price(sp) {

        const giaBan = Number(sp.gia_ban);
    
        const giaKM = Number(sp.gia_khuyen_mai);
    
        if (giaKM && giaKM < giaBan) {
    
            const giam = Math.round((giaBan - giaKM) / giaBan * 100);
    
            return `
    
                <div class="price-row">
    
                    <span class="price-label">
    
                        Giá:
    
                    </span>
    
                    <span class="price-old">
    
                        ${giaBan.toLocaleString()} đ
    
                    </span>
    
                    <span class="price-sale">
    
                        ${giaKM.toLocaleString()} đ
    
                    </span>
    
                    <span class="price-badge">
    
                        -${giam}%
    
                    </span>
    
                </div>
    
            `;
        }
    
        return `
    
            <div class="price-row">
    
                <span class="price-label">
    
                    Giá:
    
                </span>
    
                <span class="price-normal">
    
                    ${giaBan.toLocaleString()} đ
    
                </span>
    
            </div>
    
        `;
    
    },

    gallery(sp) {

        let html = `

            <img id="mainImage" class="main-image"
                src="/storage/${sp.anh_dai_dien}"
            >

            <div class="thumbnail-list">

                <img class="thumb active" src="/storage/${sp.anh_dai_dien}">

        `;

        if (sp.hinh_anhs) {

            sp.hinh_anhs.forEach(item => {

                html += `

                    <img class="thumb" src="/storage/${item.anh}">

                `;

            });

        }

        html += `

            </div>

        `;

        return html;

    },

    variant(sp) {

        let html = `

            <div class="phan-loai-title">

                Phân loại

            </div>

            <div class="phan-loai-list">

        `;

        sp.san_pham_goc.san_pham_chi_tiets.forEach(item => {

            const active = item.ma_sp == sp.ma_sp ? 'active' : '';

            html += `

                <a href="/san-pham/chi-tiet/${item.ma_sp}" class="variant ${active}">

                    ${item.ten_phu}

                </a>

            `;

        });

        html += `

            </div>

        `;

        return html;

    },

    description(sp) {

        let html = `

            <div class="product-description">

                <h3>

                    Mô tả sản phẩm

                </h3>

                <ul>

        `;

        sp.nhieu_mo_ta.forEach(item => {

            html += `

                <li>

                    ${item}

                </li>

            `;

        });

        html += `

                </ul>

            </div>

        `;

        return html;

    },

    reviewList(data) {

        let html = `<h3>Đánh giá sản phẩm</h3>`;
    
        if (data.data.length == 0) {
    
            html += `<p>Chưa có đánh giá</p>`;
    
        } else {
    
            data.data.forEach(item => {
    
                html += this.review(item);
    
            });
    
        }
    
        html += Pagination.render(data);
    
        return html;
    },

    review(item) {

        let imgs = '';

        if (item.dinh_kems) {

            item.dinh_kems.forEach(img => {

                imgs += `

                    <img src="/storage/anh_danh_gia/${img.dinh_kem}">

                `;

            });

        }

        return `

            <div class="item-danh-gia">

                <div class="ten-khach-hang">

                    ${item.khach_hang.ten_khach_hang}

                </div>

                ${this.star(item.danh_gia)}

                <p>

                    ${item.noi_dung ?? ''}

                </p>

                <div class="dinh-kem-list">

                    ${imgs}

                </div>

                <div class="ten-phu">

                    Phân loại: ${item.san_pham_chi_tiet.ten_phu}

                </div>

                <small>

                    ${item.thoi_gian_danh_gia}

                </small>

            </div>

        `;

    },

    reviewForm(ds) {

        if (!ds || ds.length == 0) {

            return '';

        }

        return `

            <form id="formDanhGia" class="form-danh-gia">

                <h3>

                    Đánh giá sản phẩm

                </h3>

                <select id="id_hoa_don_chi_tiet">

                    ${this.orderOption(ds)}

                </select>

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

                <input type="hidden" id="danh_gia" value="5">

                <textarea id="noi_dung" placeholder="Nhập đánh giá...">
                </textarea>

                <input type="file" id="images" multiple>

                <button>

                    Gửi đánh giá

                </button>

            </form>

        `;

    },

    orderOption(ds) {
        //ds.forEach(item => console.log(item));

        let html = '';

        ds.forEach(item => {

            html += `

                <option value="${item.id}">

                    <!--${item.ten_phu}-->
                    ${item.ten_san_pham}

                </option>

            `;

        });

        return html;

    },

    star(rate){

        let html = '<div class="stars">';
    
        rate = Number(rate);
    
        for(let i = 1; i <= 5; i++){
    
            if(rate >= i){
    
                html += '<span class="star full">★</span>';
    
            }else if(rate >= i - 0.5){
    
                html += '<span class="star half">★</span>';
    
            }else{
    
                html += '<span class="star empty">☆</span>';
    
            }
    
        }
    
        //html += `<span class="rating-number">${rate}</span>`;
        html += '</div>';
    
        return html;
    },

    reviewSummary(summary) {

        if(summary.count == 0){
    
            return '';
    
        }
    
        const total = summary.count;
    
        const percent = star => total ? (summary.star[star] / total * 100) : 0;
    
        return `
    
            <div class="review-summary">
    
                <div class="summary-left">
    
                    <div class="summary-score">
    
                        ${summary.average}
    
                    </div>
    
                    ${this.star(summary.average)}
    
                    <div class="summary-count">
    
                        ${summary.count} đánh giá
    
                    </div>
    
                </div>
    
                <div class="summary-right">
    
                    ${this.summaryItem(5, summary.star[5], percent(5))}
                    ${this.summaryItem(4, summary.star[4], percent(4))}
                    ${this.summaryItem(3, summary.star[3], percent(3))}
                    ${this.summaryItem(2, summary.star[2], percent(2))}
                    ${this.summaryItem(1, summary.star[1], percent(1))}
    
                </div>
    
            </div>
    
        `;
    
    },

    summaryItem(star, count, width){

        return `
    
            <div class="summary-item">
    
                <span>${star}★</span>
    
                <div class="summary-bar">
    
                    <div
                        class="summary-fill"
                        style="width:${width}%"
                    ></div>
    
                </div>
    
                <span>${count}</span>
    
            </div>
    
        `;
    
    }

};