@if(isset($customer))
<hr>
<h3>Danh sách địa chỉ cá nhân</h3>

    @if(!empty($diaChis))
        @foreach($diaChis as $index => $dc)
            <div class="card mb-2">

                <div class="card-body"
                    style="width:65%; min-width:300px;">

                    <div style="display:flex; justify-content:space-between; gap:10px;">

                        {{-- Địa chỉ --}} 
                        <div style="flex:1; word-break: break-word;">
                            <b>Địa chỉ {{ $index + 1 }}:</b>
                            {{ $dc->dia_chi }},
                            {{ $dc->phuong_ten->ward_name }},
                            {{ $dc->huyen_ten->district_name }},
                            {{ $dc->tinh_ten->province_name }}
                        </div>

                        <div style="white-space: nowrap;">
                            <a href="{{ route('customer.dia_chi_edit', $dc->id) }}"
                            class="btn btn-sm btn-warning">Sửa</a>

                            <a href="{{ route('customer.dia_chi_delete', $dc->id) }}"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Xóa địa chỉ này?')">Xóa</a>
                        </div>

                    </div>

                </div>
            </div>
        @endforeach
    @endif
    <br><br>
    @if($showAdd ?? false)
    <hr>
    <h3>Thêm mới địa chỉ</h3>
    <form method="POST" action="{{ route('customer.dia_chi_store') }}">
        @csrf

        <label>Tỉnh</label><br>
        <select id="province" name="tinh" class="form-control chon-diachi" required>
            <option value="">-- Chọn tỉnh --</option>
            @foreach($tinhs as $t)
                <option value="{{ $t->province_id }}">{{ $t->province_name }}</option>
            @endforeach
        </select><br><br>

       <label>Huyện</label><br>
       <select id="district" name ="huyen" class="form-control chon-diachi" required></select><br><br>

       <label>Phường/Xã</label><br>
       <select id="ward" name ="phuong" class="form-control chon-diachi" required></select><br><br>

       <label>Địa chỉ</label><br>
       <input type="text" name="dia_chi" class="form-control mb-2" required><br><br>

        <button class="btn btn-success">Lưu địa chỉ</button>
        <a href="{{ route('quan_ly_thong_tin_customer') }}" class="btn btn-secondary">Hủy</a><br><br>
    </form>
    @endif

    @if($showEdit ?? false)
    <hr>
    <h3>Cập nhật địa chỉ</h3>

    <form method="POST" action="{{ route('customer.dia_chi_update', $diaChiEdit->id) }}">
        @csrf
        @method('PUT')

        <label>Tỉnh</label><br>
        <select id="province" name="tinh" class="form-control" data-selected="{{ $diaChiEdit->tinh }}">
            @foreach($tinhs as $t)
                <option value="{{ $t->province_id }}"
                    {{ $diaChiEdit->tinh == $t->province_id ? 'selected' : '' }}>
                    {{ $t->province_name }}
                </option>
            @endforeach
        </select><br><br>

        <label>Huyện</label><br>
        <select id="district" name="huyen" class="form-control" data-selected="{{ $diaChiEdit->huyen }}">
        </select><br><br>

        <label>Phường/Xã</label><br>
        <select id="ward" name="phuong" class="form-control chon-diachi" data-selected="{{ $diaChiEdit->phuong }}">
        </select><br><br>

        <label>Địa chỉ</label><br>
        <input type="text" name="dia_chi" class="form-control" value="{{ $diaChiEdit->dia_chi }}"><br><br>

        <button class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('quan_ly_thong_tin_customer') }}" class="btn btn-secondary">Hủy</a>
    </form><br><br>
    @endif


@endif

<script>
/*document.addEventListener('change', function (e) {

if (e.target && e.target.id === 'province') {
    let provinceId = e.target.value;

    let district = document.getElementById('district');
    let ward = document.getElementById('ward');

    district.innerHTML = '<option value="">-- Chọn huyện --</option>';
    ward.innerHTML = '<option value="">-- Chọn phường/xã --</option>';

    if (!provinceId) return;

    fetch('/api/dia-chi/huyen/' + provinceId.trim())
        .then(res => res.json())
        .then(data => {

            data.forEach(h => {
                district.innerHTML +=
                    `<option value="${h.district_id}">${h.district_name}</option>`;
            });
        });
}

if (e.target && e.target.id === 'district') {
    let districtId = e.target.value;

    let ward = document.getElementById('ward');
    ward.innerHTML = '<option value="">-- Chọn phường/xã --</option>';

    if (!districtId) return;

    fetch('/api/dia-chi/phuong/' + districtId.trim())
        .then(res => res.json())
        .then(data => {
            data.forEach(w => {
                ward.innerHTML +=
                    `<option value="${w.ward_code}">${w.ward_name}</option>`;
            });
        });
}

});*/
document.addEventListener('DOMContentLoaded', function () {
    const province = document.getElementById('province');
    const district = document.getElementById('district');
    const ward = document.getElementById('ward');

    if (!province || !district || !ward) {
        return;
    }

    const selectedProvince = province.dataset.selected;
    const selectedDistrict = district.dataset.selected;
    const selectedWard = ward.dataset.selected;

    console.log({ selectedProvince, selectedDistrict, selectedWard });

    function loadDistricts(provinceId) {
        district.innerHTML = '<option value="">-- Chọn huyện --</option>';
        ward.innerHTML = '<option value="">-- Chọn phường --</option>';

        fetch('/api/dia-chi/huyen/' + provinceId)
            .then(res => res.json())
            .then(res => {
                const data = res.data ?? res;
                console.log('districts:', data);

                data.forEach(h => {
                    let opt = document.createElement('option');
                    opt.value = h.district_id;
                    opt.text = h.district_name;
                    if (h.district_id == selectedDistrict) opt.selected = true;
                    district.appendChild(opt);
                });

                if (selectedDistrict) loadWards(selectedDistrict);
            });
    }

    function loadWards(districtId) {
        ward.innerHTML = '<option value="">-- Chọn phường --</option>';

        fetch('/api/dia-chi/phuong/' + districtId)
            .then(res => res.json())
            .then(res => {
                const data = res.data ?? res;
                console.log('wards:', data);

                data.forEach(w => {
                    let opt = document.createElement('option');
                    opt.value = w.ward_code;
                    opt.text = w.ward_name;
                    if (w.ward_code == selectedWard) opt.selected = true;
                    ward.appendChild(opt);
                });
            });
    }

    if (selectedProvince) loadDistricts(selectedProvince);

    province.addEventListener('change', e => {
        if (e.target.value) loadDistricts(e.target.value);
    });

    district.addEventListener('change', e => {
        if (e.target.value) loadWards(e.target.value);
    });
});

</script>