@extends('layouts.backend')

@section('content')
  <h3 class="mb-3">:: Shop ::</h3>

  {{-- Filter/Search --}}
  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" name="q" class="form-control" placeholder="ค้นหาสินค้า หรือแบรนด์..."
             value="{{ request('q') }}">
    </div>
    <div class="col-md-3">
      <select name="category_id" class="form-select">
        <option value="">-- เลือกหมวดหมู่ --</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->category_id }}" @selected(request('category_id')==$cat->category_id)>
            {{ $cat->category_name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <select name="min_rating" class="form-select">
        <option value="">-- เลือกดาวขั้นต่ำ --</option>
        @for($i=5;$i>=1;$i--)
          <option value="{{ $i }}" @selected(request('min_rating')==$i)>{{ $i }} ดาวขึ้นไป</option>
        @endfor
      </select>
    </div>
    <div class="col-md-2 d-grid">
      <button class="btn btn-primary">กรอง</button>
    </div>
  </form>

  {{-- รายการสินค้า --}}
  <div class="row row-cols-1 row-cols-md-2 g-3">
    @forelse($items as $it)
      <div class="col">
        <div class="card h-100">
          @if(!empty($it->merchandise_image))
            <img src="{{ asset('storage/'.$it->merchandise_image) }}" class="card-img-top" style="height:180px;object-fit:cover">
          @endif
          <div class="card-body">
            <h5 class="card-title mb-1">{{ $it->merchandise_name }}</h5>
            <div class="text-muted small mb-2">
              หมวด: {{ $it->category->category_name ?? '-' }}
              • คะแนน: {{ number_format($it->rating_avg ?? 0,1) }}
              @if($it->price) • ราคา: ฿{{ number_format($it->price,2) }} @endif
            </div>
            <p class="card-text">{{ \Illuminate\Support\Str::limit($it->description, 110) }}</p>
            <a href="{{ route('shop.show', $it->merchandise_id) }}" class="btn btn-sm btn-outline-primary">ดูรายละเอียด</a>
          </div>
        </div>
      </div>
    @empty
      <div class="col"><div class="alert alert-secondary">ยังไม่มีสินค้า</div></div>
    @endforelse
  </div>

  <div class="mt-3">
    {{ $items->links() }}
  </div>
@endsection
