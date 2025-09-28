@extends('layouts.backend')

@section('content')
  <h3 class="mb-3">:: รายละเอียดสินค้า ::</h3>

  <div class="card">
    <div class="row g-0">
      <div class="col-md-4">
        @if(!empty($item->merchandise_image))
          <img src="{{ asset('storage/'.$item->merchandise_image) }}" class="img-fluid rounded-start" alt="image">
        @endif
      </div>
      <div class="col-md-8">
        <div class="card-body">
          <h4 class="card-title">{{ $item->merchandise_name }}</h4>
          <div class="text-muted mb-2">
            หมวด: {{ $item->category->category_name ?? '-' }} •
            คะแนนเฉลี่ย: {{ number_format($item->rating_avg ?? 0,1) }}
          </div>
          @if($item->price)
            <p class="fw-bold">ราคา: ฿{{ number_format($item->price,2) }}</p>
          @endif
          <p class="card-text">{{ $item->description }}</p>

          @if($item->link_store)
            <a class="btn btn-success btn-sm" target="_blank" rel="noopener" href="{{ $item->link_store }}">ไปยังร้านค้า</a>
          @endif

          <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary btn-sm"><< ย้อนกลับ</a>
        </div>
      </div>
    </div>
  </div>
@endsection
