@extends('home')

@section('content')
  <h3 class="mb-3">Admin Dashboard</h3>

  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="fw-bold text-muted">สินค้าทั้งหมด</div>
          <div class="display-6">{{ number_format($countProduct) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="fw-bold text-muted">มูลค่าสินค้ารวม</div>
          <div class="display-6">{{ number_format($sumPrice,2) }} ฿</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="fw-bold text-muted">ผู้ดูแลระบบ</div>
          <div class="display-6">{{ number_format($countAdmin) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="fw-bold text-muted">จำนวนวิวทั้งหมด</div>
          <div class="display-6">{{ number_format($countView) }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">จำนวนเข้าชมรายเดือน</h5>
      <canvas id="visitsChart" height="120"></canvas>
    </div>
  </div>

  {{-- Chart.js จาก CDN --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('visitsChart').getContext('2d');
    const visitsChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: @json($label),
        datasets: [{
          label: 'Visits',
          data: @json($data),
          fill: false,
          tension: 0.25
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: true } },
        scales: { y: { beginAtZero: true } }
      }
    });
  </script>
@endsection
