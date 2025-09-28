@extends('home')

@section('content')
<h3 class="mb-2">{{ $item->merchandise_name }}</h3>
<div class="mb-2">หมวด: {{ $item->category->category_name ?? '-' }}</div>
<div class="mb-3">⭐ เฉลี่ย: {{ number_format($item->rating_avg,1) }}</div>

@auth
<form method="POST" action="{{ route('reviews.store') }}" class="mb-3">
  @csrf
  <input type="hidden" name="merchandise_id" value="{{ $item->merchandise_id }}">
  <div class="row g-2 align-items-end">
    <div class="col-md-2">
      <label class="form-label">ให้คะแนน</label>
      <select name="rating" class="form-select" required>
        <option value="">--</option>
        @for($i=5;$i>=1;$i--)
          <option value="{{ $i }}">{{ $i }} ดาว</option>
        @endfor
      </select>
      @error('rating') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
      <label class="form-label">ความคิดเห็น (ไม่บังคับ)</label>
      <textarea name="comment" class="form-control" rows="2"></textarea>
    </div>
    <div class="col-md-4">
      <button class="btn btn-primary">ส่งรีวิว</button>

      <button formaction="{{ route('wishlist.toggle') }}" formmethod="POST" class="btn btn-outline-secondary ms-2">
        @csrf
        <input type="hidden" name="merchandise_id" value="{{ $item->merchandise_id }}">
        Wishlist
      </button>
    </div>
  </div>
</form>
@endauth

<hr>
<h5>รีวิวล่าสุด</h5>
@forelse($item->reviews as $rv)
  <div class="mb-2">
    <strong>{{ $rv->user->name ?? 'ผู้ใช้' }}</strong> — ⭐ {{ $rv->rating }}
    <div class="small text-muted">{{ $rv->created_at }}</div>
    @if($rv->comment) <div>{{ $rv->comment }}</div> @endif

    @auth
      @if(auth()->user()->role === 'admin' || auth()->id() === $rv->user_id)
        <form action="{{ route('reviews.destroy', $rv->review_id) }}" method="POST" class="d-inline">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">ลบรีวิว</button>
        </form>
      @endif
    @endauth
  </div>
@empty
  <div class="text-muted">ยังไม่มีรีวิว</div>
@endforelse

@auth
  <hr>
  {{-- ตัวอย่างลิงก์เปรียบเทียบ: เลือกสินค้านี้ + อีกชิ้นหนึ่ง (คุณอาจทำ UI เลือกจาก list ก็ได้) --}}
  <form action="{{ route('shop.compare') }}" method="GET" class="row g-2 align-items-end mt-3">
    <div class="col-md-4">
      <label class="form-label">สินค้าชิ้นที่ 1</label>
      <input type="text" class="form-control" value="{{ $item->merchandise_id }}" disabled>
      <input type="hidden" name="items[]" value="{{ $item->merchandise_id }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">สินค้าชิ้นที่ 2 (กรอก merchandise_id)</label>
      <input type="number" name="items[]" class="form-control" required>
    </div>
    <div class="col-md-4">
      <button class="btn btn-info">ไปหน้าเปรียบเทียบ</button>
    </div>
  </form>
@endauth
@endsection
