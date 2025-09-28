@extends('home')

@section('content')
<h3 class="mb-3">สินค้า</h3>

<form class="row g-2 mb-3" method="GET" action="{{ route('shop.index') }}">
  <div class="col-md-4">
    <input type="text" name="q" class="form-control" placeholder="ค้นหาสินค้า..." value="{{ $q }}">
  </div>
  <div class="col-md-4">
    <select name="category[]" class="form-select" multiple>
      @foreach($cats as $c)
        <option value="{{ $c->category_id }}" @selected(in_array($c->category_id, $categories))>
          {{ $c->category_name }}
        </option>
      @endforeach
    </select>
    <small class="text-muted">กด Ctrl/Command + Click เพื่อเลือกหลายหมวด</small>
  </div>
  <div class="col-md-2">
    <select name="min_rating" class="form-select">
      <option value="">คะแนนขั้นต่ำ</option>
      @for($i=5;$i>=1;$i--)
        <option value="{{ $i }}" @selected(($minRating ?? null)==$i)>{{ $i }} ดาว+</option>
      @endfor
    </select>
  </div>
  <div class="col-md-2">
    <input type="text" name="age" class="form-control" placeholder="ช่วงวัย เช่น 1-2" value="{{ $age }}">
  </div>
  <div class="col-12">
    <button class="btn btn-primary">กรอง</button>
  </div>
</form>

<div class="row">
  @foreach($items as $it)
    <div class="col-md-3 mb-3">
      <div class="card h-100">
        @if($it->merchandise_image)
          <img src="{{ asset('storage/'.$it->merchandise_image) }}" class="card-img-top" alt="">
        @endif
        <div class="card-body">
          <h6 class="mb-1">{{ $it->merchandise_name }}</h6>
          <div class="small text-muted">{{ $it->category->category_name ?? '-' }}</div>
          <div class="small">⭐ {{ number_format($it->rating_avg,1) }}</div>
          <a href="{{ route('shop.show',$it->merchandise_id) }}" class="btn btn-sm btn-outline-primary mt-2">ดูรายละเอียด</a>
        </div>
      </div>
    </div>
  @endforeach
</div>

{{ $items->links() }}
@endsection
