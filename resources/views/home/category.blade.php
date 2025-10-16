@extends('frontend')

@section('content')
<div class="container py-3">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h4 class="mb-0">หมวด: {{ $category->category_name }}</h4>
    <a href="{{ route('home.index') }}" class="btn btn-outline-secondary btn-sm">กลับหน้าแรก</a>
  </div>

  @if($category->description)
    <p class="text-muted">{{ $category->description }}</p>
  @endif

  <div class="row g-3">
    @forelse($items as $item)
      <div class="col-6 col-md-4 col-lg-3">
        <div class="product-card p-2 rounded-4 shadow-sm h-100">
          <div class="pc-img rounded-3 overflow-hidden mb-2" style="aspect-ratio:1/1;background:#f7f7f7;">
            @if($item->merchandise_image)
              <img src="{{ asset('storage/'.$item->merchandise_image) }}" alt="{{ $item->merchandise_name }}"
                   style="width:100%;height:100%;object-fit:cover;">
            @else
              <div class="d-flex align-items-center justify-content-center h-100">No image</div>
            @endif
          </div>
          <div class="fw-semibold">{{ $item->merchandise_name }}</div>
          <div class="text-primary">฿{{ number_format($item->price ?? 0, 2) }}</div>
          <a href="{{ url('/detail/'.$item->merchandise_id) }}" class="btn btn-sm btn-outline-primary mt-1">รายละเอียด</a>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info">ยังไม่มีสินค้าในหมวดนี้</div>
      </div>
    @endforelse
  </div>

  <div class="mt-3">
    {{ $items->links() }}
  </div>
</div>
@endsection
