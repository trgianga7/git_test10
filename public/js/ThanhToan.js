/*
const toggle = document.getElementById('toggleAddress');
const savedBox = document.getElementById('savedAddressBox');
const newBox = document.getElementById('newAddressBox');

const selectAddress = savedBox.querySelector('select');
const inputAddress = newBox.querySelector('input');

function updateAddressState() {
    if (toggle.checked) {
        savedBox.style.display = 'block';
        newBox.style.display = 'none';

        selectAddress.disabled = false;
        inputAddress.disabled = true;
    } else {
        savedBox.style.display = 'none';
        newBox.style.display = 'block';

        selectAddress.disabled = true;
        inputAddress.disabled = false;
    }
}

toggle.addEventListener('change', updateAddressState);

updateAddressState();



document.getElementById('btnApDung').addEventListener('click', function () {

let ma = document.getElementById('ma_giam_gia').value;
let tongGoc = parseFloat(document.getElementById('tongGoc').dataset.value);

//fetch("{{ route('thanh_toan.kiem_tra_ma') }}", {
fetch(urlKiemTraMa, {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        //"X-CSRF-TOKEN": "{{ csrf_token() }}"
        "X-CSRF-TOKEN": csrfToken
    },
    body: JSON.stringify({
        ma_giam_gia: ma
    })
})
.then(res => res.json())
.then(data => {

    if (!data.success) {
        alert("Mã không hợp lệ hoặc đã hết hạn");
        return;
    }

    let soTienGiam = 0;

    if (data.loai == 0) {
        soTienGiam = data.gia_tri;
    } else {
        soTienGiam = (tongGoc * data.gia_tri) / 100;
    }

    if (soTienGiam > tongGoc) { 
        soTienGiam = tongGoc;
    }

    let tongSauGiam = tongGoc - soTienGiam;

    document.getElementById('soTienGiam').innerText = soTienGiam.toLocaleString('vi-VN');
    document.getElementById('tongSauGiam').innerText = tongSauGiam.toLocaleString('vi-VN');

    checkSoDu();

    document.getElementById('ma_giam_gia_ap_dung').value = ma;
    document.getElementById('ma_giam_gia').readOnly = true;
    document.getElementById('btnApDung').disabled = true;
    document.getElementById('btnApDung').innerText = "Đã áp dụng";

    document.getElementById('giamGiaInfo').innerText = data.loai == 0
            ? "Mã giảm giá đang áp dụng: Giảm " + data.gia_tri.toLocaleString('vi-VN') + " đ"
            : " Mã giảm giá đang áp dụng: Giảm " + data.gia_tri + "%";
});
});

const radios = document.querySelectorAll('input[name="phuong_thuc"]');
const btnSubmit = document.getElementById('btnSubmit');
const btnQR = document.getElementById('btnQR');

radios.forEach(radio => {
    radio.addEventListener('change', function () {
        if (this.value === 'qr') {
            btnSubmit.style.display = 'none';
            btnQR.style.display = 'inline-block';
        } else {
            btnSubmit.style.display = 'inline-block';
            btnQR.style.display = 'none';
        }
    });
});

function checkSoDu() {
    let soDuEl = document.getElementById('so_du');
    let vi = parseInt(soDuEl.dataset.vi);

    let tong = parseInt(
        document.getElementById('tongSauGiam')
            .innerText.replace(/\D/g, '')
    ) || 0;

    let radioPay = document.getElementById('radio-pay');
    let warning = document.getElementById('vi_warning');

    if (vi < tong) {
        soDuEl.classList.remove('so-du-du');
        soDuEl.classList.add('so-du-thieu');

        warning.style.display = 'block';

        radioPay.disabled = true;
    } else {
        soDuEl.classList.remove('so-du-thieu');
        soDuEl.classList.add('so-du-du');

        warning.style.display = 'none';

        radioPay.disabled = false;
    }
}

document.addEventListener("DOMContentLoaded", function () {
    checkSoDu();
});
*/
document.addEventListener("DOMContentLoaded", function () {

    const toggle = document.getElementById('toggleAddress');
    const savedBox = document.getElementById('savedAddressBox');
    const newBox = document.getElementById('newAddressBox');

    const selectAddress = savedBox.querySelector('select');
    const inputAddress = newBox.querySelector('input');

    function updateAddressState() {
        if (toggle.checked) {
            savedBox.style.display = 'block';
            newBox.style.display = 'none';

            selectAddress.disabled = false;
            inputAddress.disabled = true;
        } else {
            savedBox.style.display = 'none';
            newBox.style.display = 'block';

            selectAddress.disabled = true;
            inputAddress.disabled = false;
        }
    }

    toggle.addEventListener('change', updateAddressState);
    updateAddressState();


    document.getElementById('btnApDung').addEventListener('click', function () {

        let ma = document.getElementById('ma_giam_gia').value;
        let tongGoc = parseFloat(document.getElementById('tongGoc').dataset.value);

        fetch(urlKiemTraMa, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ ma_giam_gia: ma })
        })
        .then(res => res.json())
        .then(data => {

            if (!data.success) {
                alert("Mã không hợp lệ hoặc đã hết hạn");
                return;
            }

            let soTienGiam = 0;

            if (data.loai == 0) {
                soTienGiam = data.gia_tri;
            } else {
                soTienGiam = (tongGoc * data.gia_tri) / 100;
            }

            if (soTienGiam > tongGoc) {
                soTienGiam = tongGoc;
            }

            let tongSauGiam = tongGoc - soTienGiam;

            document.getElementById('soTienGiam').innerText = soTienGiam.toLocaleString('vi-VN');
            document.getElementById('tongSauGiam').innerText = tongSauGiam.toLocaleString('vi-VN');

            document.getElementById('ma_giam_gia_ap_dung').value = ma;
            document.getElementById('ma_giam_gia').readOnly = true;

            let btn = document.getElementById('btnApDung');
            btn.disabled = true;
            btn.innerText = "Đã áp dụng";

            document.getElementById('giamGiaInfo').innerText =
                data.loai == 0
                    ? "Mã giảm giá: -" + data.gia_tri.toLocaleString('vi-VN') + " đ"
                    : "Mã giảm giá: -" + data.gia_tri + "%";

            updateSoDuUI();
        });
    });


    const radios = document.querySelectorAll('input[name="phuong_thuc"]');
    const btnSubmit = document.getElementById('btnSubmit');
    //const btnQR = document.getElementById('btnQR');

    radios.forEach(radio => {
        radio.addEventListener('change', function () {

            /*if (this.value === 'qr') {
                btnSubmit.style.display = 'none';
                btnQR.style.display = 'inline-block';
            } else {
                btnSubmit.style.display = 'inline-block';
                btnQR.style.display = 'none';
            }*/

            updateSoDuUI();
        });
    });

    function updateSoDuUI() {

        let soDuEl = document.getElementById('so_du');
        let radioPay = document.getElementById('radio-pay');
        let warning = document.getElementById('vi_warning');

        if (!soDuEl || !radioPay) return;

        if (!radioPay.checked) {
            soDuEl.classList.remove('so-du-thieu');
            soDuEl.classList.add('so-du-du');

            if (warning) warning.style.display = 'none';
            return;
        }

        let vi = parseInt(soDuEl.dataset.vi) || 0;

        let tong = parseInt(
            document.getElementById('tongSauGiam')
                .innerText.replace(/\D/g, '')
        ) || 0;

        if (vi < tong) {
            soDuEl.classList.remove('so-du-du');
            soDuEl.classList.add('so-du-thieu');

            if (warning) warning.style.display = 'block';
        } else {
            soDuEl.classList.remove('so-du-thieu');
            soDuEl.classList.add('so-du-du');

            if (warning) warning.style.display = 'none';
        }
    }

    updateSoDuUI();
});