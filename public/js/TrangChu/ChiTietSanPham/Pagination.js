const Pagination = {

    render(data) {

        if (data.last_page <= 1)
            return '';

        let html = '<div class="pagination">';

        for (let i = 1; i <= data.last_page; i++) {

            html += `
                <button
                    class="page-btn ${i == data.current_page ? 'active' : ''}"
                    data-page="${i}">
                    ${i}
                </button>
            `;
        }

        html += '</div>';

        return html;
    }

};