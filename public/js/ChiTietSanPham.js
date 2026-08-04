let page = 2;

document.getElementById('btnLoadMore')?.addEventListener('click', function () {

    fetch(window.location.href + '?page=' + page)
        .then(res => res.text())
        .then(html => {

            let parser = new DOMParser();
            let doc = parser.parseFromString(html, 'text/html');

            let newItems = doc.querySelectorAll('.item-danh-gia');

            let container = document.getElementById('danhGiaList');

            newItems.forEach(el => container.appendChild(el));

            if (!doc.querySelector('#btnLoadMore')) {
                document.getElementById('btnLoadMore').style.display = 'none';
            }

            page++;
        });
});

const box = document.getElementById("ratingBox");
const fill = document.getElementById("starsFill");
const input = document.getElementById("ratingValue");

box.addEventListener("mousemove", function (e) {
    const rect = box.getBoundingClientRect();
    let percent = (e.clientX - rect.left) / rect.width;

    percent = Math.max(0, Math.min(1, percent));

    // làm tròn 0.5
    let rating = Math.round(percent * 10) / 2;

    fill.style.width = (rating / 5 * 100) + "%";
});

box.addEventListener("click", function (e) {
    const rect = box.getBoundingClientRect();
    let percent = (e.clientX - rect.left) / rect.width;

    percent = Math.max(0, Math.min(1, percent));

    let rating = Math.round(percent * 10) / 2;

    fill.style.width = (rating / 5 * 100) + "%";
    input.value = rating;
});