@extends('home')

@section('content')
<h3 class="mb-3">Wishlist ของฉัน</h3>

<div class="row">
@forelse($items as $w)
  <div class="col-md-3 mb-3">
    <div class="card h-100">
      @if($w->merchandise && $w->merchandise->merchandise_image)
        <img src="{{ asset('storage/'.$w->merchandise->merchandise_image) }}" class="card-img-top" alt="">
      @endif
      <div class="card-body">
        <h6 class="mb-1">{{ $w->merchandise->merchandise_name ?? '-' }}</h6>
        <div class="small">⭐ {{ number_format($w->merchandise->rating_avg ?? 0, 1) }}</div>
        <a href="{{ route('shop.show', $w->merchandise_id) }}" class="btn btn-sm btn-outline-primary mt-2">ดูสินค้า</a>
      </div>
    </div>
  </div>
@empty
  <div class="col-12 text-muted">ยังไม่มีสินค้าใน Wishlist</div>
@endforelse
</div>

{{ $items->links() }}
@endsection
