@extends('layouts.backend')

@section('css_before')
<style>
  :root{
    --brand:#e26b6b; --brand-2:#f4a9a8; --ink:#2a2a2a; --muted:#6b6f76;
    --bg:#fffaf9; --card:#ffffff; --ring: rgba(226,107,107,.28);
    --shadow: 0 .5rem 1.25rem rgba(0,0,0,.08);
    --radius: 1rem;
  }
  .spec-card, .hero-card{
    border:0; border-radius: var(--radius);
    background:#fff; box-shadow: var(--shadow);
  }
  .thumb{
    position:relative; width:100%; aspect-ratio: 1 / 1; overflow:hidden;
    border-radius: calc(var(--radius) - .25rem); background:#f7f7f7;
  }
  .thumb img{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
  .badge-soft{
    display:inline-block; padding:.35rem .65rem; border-radius:999px;
    background:#fff0f0; color:#b24f4f; font-weight:700; font-size:.8rem;
  }
  .btn-brand{ background:var(--brand); border-color:var(--brand); color:#fff; border-width:2px; border-radius:.9rem; }
  .btn-brand:hover{ background:#d45d5d; border-color:#d45d5d; box-shadow:0 0 0 .25rem var(--ring); }
  .form-control, .form-select{ border-radius:.85rem; border:2px solid #f0d6d6; }
  .form-control:focus, .form-select:focus{ border-color:var(--brand); box-shadow:0 0 0 .25rem var(--ring); }
  .table.specs{ --bs-table-bg:#fff; }
  .table.specs th{ width:180px; color:#7e3c3b; background:#ffe7e7; border:0; }
  .table.specs td{ background:#fff; }
  .rating-stars{ letter-spacing:.5px; color:#d60000; font-weight:700; }
</style>
@endsection

@section('content')
@php
  $rAvg = $ratingAvg ?? $item->rating_avg ?? null;
  $catName = $item->category->category_name ?? null;
@endphp

<div class="d-flex align-items-center justify-content-between">
  <h3 class="mb-3">{{ $item->merchandise_name ?? 'Product' }}</h3>
  <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">กลับหน้าร้าน</a>
</div>

<div class="hero-card p-3 p-md-4 mb-4">
  <div class="row g-4 align-items-start">
    <div class="col-md-5">
      <div class="thumb">
        @if(!empty($item->merchandise_image))
          <img
            src="{{ asset('storage/'.$item->merchandise_image) }}"
            alt="{{ $item->merchandise_name }}"
            onerror="this.style.display='none';"
            loading="lazy"
          >
        @endif
      </div>
    </div>

    <div class="col-md-7">
      <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
        @if($catName)<span class="badge-soft">{{ $catName }}</span>@endif
        @if(!is_null($rAvg))
          <span class="rating-stars">
            {!! str_repeat('★', max(0, min(5, (int) round($rAvg)))) !!}
            {!! str_repeat('☆', max(0, 5 - (int) round($rAvg))) !!}
            <small class="text-muted ms-1">({{ number_format($rAvg,1) }}/5)</small>
          </span>
        @endif
      </div>

      @if(!is_null($item->price))
        <p class="fs-5 mb-1">ราคา: <strong class="text-danger">฿{{ number_format($item->price,2) }}</strong></p>
      @endif

      @if(!empty($item->description))
        <p class="mb-3">{{ $item->description }}</p>
      @endif

      {{-- Wishlist toggle --}}
      @auth
        <form action="{{ route('wishlist.toggle') }}" method="POST" class="d-inline">
          @csrf
          <input type="hidden" name="merchandise_id" value="{{ $item->merchandise_id }}">
          <button class="btn {{ $wishlisted ? 'btn-outline-danger' : 'btn-brand' }}">
            {{ $wishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}
          </button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn btn-brand">เข้าสู่ระบบเพื่อบันทึก Wishlist</a>
      @endauth

      @if(!empty($item->link_store))
        <a href="{{ $item->link_store }}" target="_blank" class="btn btn-outline-primary ms-2">ไปหน้าร้านค้า</a>
      @endif
    </div>
  </div>
</div>

{{-- ✅ ตารางรายละเอียดสินค้า (แสดงเฉพาะที่มีข้อมูล) --}}
<div class="spec-card p-3 p-md-4 mb-4">
  <h5 class="mb-3">รายละเอียดสินค้า</h5>
  <div class="table-responsive">
    <table class="table specs align-middle mb-0">
      <tbody>
        @if($catName)
          <tr>
            <th>หมวดหมู่</th>
            <td>{{ $catName }}</td>
          </tr>
        @endif

        @if(!empty($item->brand))
          <tr>
            <th>แบรนด์</th>
            <td>{{ $item->brand }}</td>
          </tr>
        @endif

        @if(!empty($item->age_range))
          <tr>
            <th>ช่วงอายุที่เหมาะ</th>
            <td>{{ $item->age_range }}</td>
          </tr>
        @endif

        @if(!is_null($item->price))
          <tr>
            <th>ราคา</th>
            <td>฿{{ number_format($item->price,2) }}</td>
          </tr>
        @endif

        @if(!is_null($rAvg))
          <tr>
            <th>คะแนนเฉลี่ย</th>
            <td>{{ number_format($rAvg,1) }} / 5</td>
          </tr>
        @endif

        @if(!empty($item->created_at))
          <tr>
            <th>วันที่เพิ่มสินค้า</th>
            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
          </tr>
        @endif

        @if(!empty($item->link_store))
          <tr>
            <th>ลิงก์ร้านค้า</th>
            <td><a href="{{ $item->link_store }}" target="_blank" rel="noopener">เปิดดูร้านค้า</a></td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>

<hr>

{{-- ฟอร์มเขียนรีวิว --}}
<div class="mb-4">
  <h5 class="mb-2">เขียนรีวิว</h5>
  @auth
    <form action="{{ route('reviews.store') }}" method="POST">
      @csrf
      <input type="hidden" name="merchandise_id" value="{{ $item->merchandise_id }}">

      <div class="row g-3">
        <div class="col-sm-3">
          <label class="form-label">ให้คะแนน</label>
          <select name="rating" class="form-select" required>
            <option value="">-- เลือก --</option>
            @for($i=5;$i>=1;$i--) <option value="{{ $i }}">{{ $i }} ★</option> @endfor
          </select>
          @error('rating')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="col-sm-9">
          <label class="form-label">ความคิดเห็น</label>
          <textarea name="comment" class="form-control" rows="3" placeholder="เล่าประสบการณ์ของคุณ..." required></textarea>
          @error('comment')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
      </div>

      <button class="btn btn-brand mt-3">ส่งรีวิว</button>
    </form>
  @else
    <p><a href="{{ route('login') }}">ล็อกอิน</a> เพื่อเขียนรีวิว</p>
  @endauth
</div>

{{-- รายการรีวิว --}}
<h5 class="mb-3">รีวิวทั้งหมด</h5>
@if($reviews->count())
  <ul class="list-group mb-3">
    @foreach($reviews as $r)
      <li class="list-group-item">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <strong>{{ $r->user->name ?? 'ผู้ใช้' }}</strong>
            @if(!is_null($r->rating))
              <span class="ms-2 text-warning">{{ $r->rating }} ★</span>
            @endif
            <div>{{ $r->comment }}</div>
          </div>
          <small class="text-muted">{{ \Carbon\Carbon::parse($r->created_at)->format('d/m/Y H:i') }}</small>
        </div>

        {{-- เจ้าของรีวิวหรือแอดมินลบได้ --}}
        @auth
          @if(auth()->id() === (int)($r->user_id) || (auth()->user()->role ?? null) === 'admin')
            <form action="{{ route('reviews.destroy', $r->review_id) }}" method="POST" class="mt-2"
                  onsubmit="return confirm('ลบรีวิวนี้หรือไม่?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          @endif
        @endauth
      </li>
    @endforeach
  </ul>
  {{ $reviews->links() }}
@else
  <p class="text-muted">ยังไม่มีรีวิว</p>
@endif
@endsection
