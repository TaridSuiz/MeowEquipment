@extends('layouts.backend')

@section('content')
  <h3 class="mb-3">:: เปรียบเทียบสินค้า ::</h3>

  @if(!$a || !$b)
    <div class="alert alert-warning">ต้องเลือกสินค้า 2 ชิ้น</div>
  @else
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>คุณสมบัติ</th>
            <th>{{ $a->merchandise_name }}</th>
            <th>{{ $b->merchandise_name }}</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>หมวดหมู่</th>
            <td>{{ $a->category->category_name ?? '-' }}</td>
            <td>{{ $b->category->category_name ?? '-' }}</td>
          </tr>
          <tr>
            <th>ราคา</th>
            <td>{{ $a->price ? '฿'.number_format($a->price,2) : '-' }}</td>
            <td>{{ $b->price ? '฿'.number_format($b->price,2) : '-' }}</td>
          </tr>
          <tr>
            <th>คะแนนเฉลี่ย</th>
            <td>{{ number_format($a->rating_avg ?? 0,1) }}</td>
            <td>{{ number_format($b->rating_avg ?? 0,1) }}</td>
          </tr>
          <tr>
            <th>รายละเอียด</th>
            <td>{{ \Illuminate\Support\Str::limit($a->description, 200) }}</td>
            <td>{{ \Illuminate\Support\Str::limit($b->description, 200) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  @endif

  <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary btn-sm"><< ย้อนกลับ</a>
@endsection
