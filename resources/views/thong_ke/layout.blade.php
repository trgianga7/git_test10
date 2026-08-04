@extends('DanhSach')

@section('title', 'Thống kê')

@section('content')

<link rel="stylesheet" href="{{ asset('css/ThongKe/ThongKe.css') }}">

<h2>Thống kê doanh thu</h2>

<div class="stat-grid">

    <div class="stat-card bg-success">
        <h4>Tổng doanh thu</h4>
        <h2 id="tongDoanhThu">0 đ</h2>
    </div>

    <div class="stat-card bg-primary">
        <h4>Số đơn hoàn thành</h4>
        <h2 id="tongDonHang">0</h2>
    </div>

    <div class="stat-card bg-warning">
        <h4>Số sản phẩm đã bán</h4>
        <h2 id="tongSanPham">0</h2>
    </div>

    <div class="stat-card bg-danger">
        <h4>Sản phẩm bán chạy nhất</h4>
        <h2 id="sanPhamBanChay">Chưa có</h2>
    </div>

    <div class="stat-card bg-secondary">
        <h4>Tổng chi phí</h4>
        <h2 id="chiPhi">0 đ</h2>
    </div>

    <div class="stat-card bg-danger">
        <h4>Tổng lợi nhuận</h4>
        <h2 id="loiNhuan">0 đ</h2>
    </div>

</div>

<div class="button-group">
    <button class="btn btn-danger" onclick="showChart('top10')">Top 10 sản phẩm bán chạy</button>
    <button class="btn btn-primary" onclick="showChart('day')">Biểu đồ doanh thu theo ngày</button>
    <button class="btn btn-success" onclick="showChart('month')">Biểu đồ doanh thu theo tháng</button>
    <button class="btn btn-warning" onclick="showChart('year')">Biểu đồ doanh thu theo năm</button>
</div>

<div id="chart-top10" class="chart-section">
    <h3>Top 10 sản phẩm bán chạy</h3>
    <canvas id="chartTop"></canvas>
</div>

<div id="chart-day" class="chart-section">
    <h3>Doanh thu theo ngày</h3>
    <canvas id="chartDay"></canvas>
</div>

<div id="chart-month" class="chart-section">
    <h3>Doanh thu theo tháng</h3>
    <canvas id="chartMonth"></canvas>
</div>

<div id="chart-year" class="chart-section">
    <h3>Doanh thu theo năm</h3>
    <canvas id="chartYear"></canvas>
</div>

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let chartTop = null;
    let chartDay = null;
    let chartMonth = null;
    let chartYear = null;
    
    function showChart(type) {
        document.getElementById('chart-top10').style.display = 'none';
        document.getElementById('chart-day').style.display = 'none';
        document.getElementById('chart-month').style.display = 'none';
        document.getElementById('chart-year').style.display = 'none';

        document.getElementById('chart-' + type).style.display = 'block';
    }

    function renderThongKe(res){

        $('#tongDoanhThu').text(
            Number(res.tongDoanhThu).toLocaleString() + ' đ'
        );

        $('#tongDonHang').text(
            res.tongDonHang
        );

        $('#tongSanPham').text(
            res.tongSanPham
        );

        $('#sanPhamBanChay').text(
            res.sanPhamBanChay?.ten_san_pham ??
            'Chưa bán sản phẩm nào'
        );

        $('#chiPhi').text(
            Number(res.chiPhi).toLocaleString() + ' đ'
        );

        $('#loiNhuan').text(
            Number(res.loiNhuan).toLocaleString() + ' đ'
        );
    }

    function renderChartTop10(data){

        if(chartTop){
            chartTop.destroy();
        }

        chartTop = new Chart(
            document.getElementById('chartTop'),
            {
                type:'bar',

                data:{
                    labels:data.map(x => x.ten_san_pham),

                    datasets:[{
                        label:'Số lượng đã bán',
                        data:data.map(x => x.tong_da_ban)
                    }]
                }
            }
        );
    }

    function renderChartDay(data){

        if(chartDay){
            chartDay.destroy();
        }

        chartDay = new Chart(
            document.getElementById('chartDay'),
            {
                type:'line',

                data:{
                    labels:data.map(x => x.ngay),

                    datasets:[{
                        label:'Doanh thu',
                        data:data.map(x => x.doanh_thu)
                    }]
                }
            }
        );
    }

    function renderChartMonth(data){

        if(chartMonth){
            chartMonth.destroy();
        }

        chartMonth = new Chart(
            document.getElementById('chartMonth'),
            {
                type:'bar',

                data:{
                    labels:data.map(
                        x => `Tháng ${x.thang}/${x.nam}`
                    ),

                    datasets:[{
                        label:'Doanh thu',
                        data:data.map(x => x.doanh_thu)
                    }]
                }
            }
        );
    }

    function renderChartYear(data){

        if(chartYear){
            chartYear.destroy();
        }

        chartYear = new Chart(
            document.getElementById('chartYear'),
            {
                type:'bar',

                data:{
                    labels:data.map(x => x.nam),

                    datasets:[{
                        label:'Doanh thu',
                        data:data.map(x => x.doanh_thu)
                    }]
                }
            }
        );
    }

    function showChart(type) {

        $('.chart-section').hide();

        $('#chart-' + type).show();

        $('.button-group button').removeClass('active-chart');

        $(event.target).addClass('active-chart');
    }

    $(function(){

        ajaxRequest({
            url:'/api/thong-ke',
            type:'GET',

            loading:false,
            showSuccess:false,

            successCallback:function(res){

                renderThongKe(res);

                renderChartTop10(res.top10SanPham);

                renderChartDay(res.doanhThuTheoNgay);

                renderChartMonth(res.doanhThuTheoThang);

                renderChartYear(res.doanhThuTheoNam);

                showChart('top10');
            }
        });

    });

</script>

@endsection