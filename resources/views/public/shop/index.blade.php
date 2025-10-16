@extends('layouts.backend')

@section('css_before')
<style>
  /* ===== Grid & Gutters ===== */
  .row.g-3{ --bs-gutter-x: .9rem; --bs-gutter-y: .9rem; }

  /* ===== Card Base ===== */
  .product-tile{
    border:0; border-radius:1rem; background:#fff;
    box-shadow:0 .35rem .9rem rgba(0,0,0,.07);
    transition: transform .18s ease, box-shadow .18s ease;
    display:flex; flex-direction:column; height:100%;
  }
  .product-tile:hover{
    transform: translateY(-4px);
    box-shadow:0 .85rem 1.6rem rgba(0,0,0,.12);
  }
  .product-tile .card-body{
    display:flex; flex-direction:column; flex:1 1 auto; padding:.85rem .9rem .9rem;
  }

  /* ===== Image ===== */
  .product-tile .thumb{
    position:relative; width:100%; aspect-ratio:1/1;
    overflow:hidden; border-radius:1rem 1rem .75rem .75rem; background:#f5f5f5;
  }
  .product-tile .thumb img{
    position:absolute; inset:0; width:100%; height:100%; object-fit:cover; display:block;
    transform: scale(1); transition: transform .35s ease;
  }
  .product-tile:hover .thumb img{ transform: scale(1.04); }

  /* ===== Title (2 lines clamp) ===== */
  .product-tile .card-title{
    font-size:1rem; font-weight:600; line-height:1.25; margin-bottom:.25rem;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    min-height:2.5em;
  }

  /* ===== Category pill ===== */
  .cat-pill{
    display:inline-flex; align-items:center; gap:.4rem;
    font-size:.78rem; color:#5f6b76; background:#f0f4f8;
    border-radius:999px; padding:.25rem .6rem; margin:.25rem 0 .6rem;
  }

  /* ===== Meta Row (rating + price) ===== */
  .meta-row{
    display:flex; align-items:center; justify-content:space-between; gap:.5rem;
    margin-bottom:.4rem;
  }
  .rating{
    display:inline-flex; align-items:center; gap:.35rem; font-size:.85rem; color:#6b7280;
  }
  .rating .stars{ letter-spacing:.05em; font-size:.95rem; }
  .price{
    font-weight:700; font-size:1rem; color:#1f2937;
    background:#fff3f3; border:1px solid #ffd7d7; padding:.2rem .55rem; border-radius:.5rem;
    white-space:nowrap;
  }

  /* ===== Description (2 lines clamp) ===== */
  .product-tile .card-text{
    color:#6c757d; font-size:.9rem; margin-bottom:.6rem;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
    min-height:2.6em;
  }

  /* ===== CTA pin to bottom ===== */
  .cta{ margin-top:auto; }
  .btn-ghost{
    --ring: rgba(214,0,0,.25);
    border:1px solid #d60000; color:#d60000; background:#fff;
  }
  .btn-ghost:hover{ color:#fff; background:#d60000; }

  /* ===== Filters ===== */
  .filters .form-control, .filters .form-select{ height:42px; }

  /* ===== Pagination ===== */
  .pagination{ justify-content:center; margin-top:.75rem; }
  .page-link{ padding:.35rem .7rem; font-size:.9rem; border-radius:.5rem; }

  /* ===== Responsive ===== */
  @media (max-width: 575.98px){
    .filters .col-md-4, .filters .col-md-3, .filters .col-md-2{ flex:0 0 100%; max-width:100%; }
    .filters .d-grid{ margin-top:.25rem; }
    .product-tile .card-text{ display:none; } /* ซ่อนคำอธิบายบนจอเล็ก */
  }
</style>
@endsection

@section('content')
  <h3 class="mb-3" style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #d60000;">
    สินค้าแนะนำ
  </h3>

  {{-- Filter/Search --}}
  <form method="GET" class="row g-2 mb-3 filters" action="{{ url()->current() }}">
    <div class="col-md-4">
      <input type="text" name="q" class="form-control" placeholder="ค้นหาสินค้า หรือแบรนด์..."
             value="{{ request('q') }}">
    </div>

    <div class="col-md-3">
      <select name="category_id" class="form-select">
        <option value="">-- เลือกหมวดหมู่ --</option>
        @isset($categories)
          @foreach($categories as $cat)
            <option value="{{ $cat->category_id }}" @selected((string)request('category_id') === (string)$cat->category_id)>
              {{ $cat->category_name }}
            </option>
          @endforeach
        @endisset
      </select>
    </div>

    <div class="col-md-3">
      <select name="min_rating" class="form-select">
        <option value="">-- เลือกดาวขั้นต่ำ --</option>
        @for($i=5;$i>=1;$i--)
          <option value="{{ $i }}" @selected((string)request('min_rating') === (string)$i)>{{ $i }} ดาวขึ้นไป</option>
        @endfor
      </select>
    </div>

    <div class="col-md-2 d-grid">
      <button class="btn btn-danger" type="submit">กรอง</button>
    </div>
  </form>

  {{-- รายการสินค้า --}}
  <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-5 g-3">
    @forelse($items ?? [] as $it)
      <div class="col">
        <div class="product-tile card h-100">
          {{-- รูปสินค้า --}}
          <div class="thumb">
            @php $img = $it->merchandise_image ?? null; @endphp
            @if($img)
              <img
                src="{{ asset('storage/'.$img) }}"
                alt="{{ $it->merchandise_name }}"
                loading="lazy"
                onerror="this.remove()"
              >
            @endif

            {{-- ตัวอย่าง badge (ปลดคอมเมนต์ถ้าต้องการ) --}}
            {{-- <div class="position-absolute top-0 start-0 m-2 badge bg-danger rounded-pill">ขายดี</div> --}}
          </div>

          {{-- เนื้อหา --}}
          <div class="card-body">
            <h5 class="card-title" title="{{ $it->merchandise_name }}">
              {{ $it->merchandise_name }}
            </h5>

            <div class="cat-pill">
              <span>หมวด:</span>
              <strong>{{ $it->category->category_name ?? '-' }}</strong>
            </div>

            <div class="meta-row">
              <div class="rating" aria-label="คะแนนรีวิว">
                @php $avg = (float)($it->rating_avg ?? 0); @endphp
                <span class="stars">★</span>
                <span>{{ number_format($avg, 1) }}/5</span>
              </div>

              @php $price = $it->price; @endphp
              @if(!is_null($price))
                <div class="price">฿{{ number_format((float)$price, 2) }}</div>
              @endif
            </div>

            <p class="card-text">
              {{ \Illuminate\Support\Str::limit($it->description ?? '', 120) }}
            </p>

            <div class="cta pt-1">
              <a href="{{ route('shop.show', ['id' => $it->merchandise_id]) }}"
                 class="btn btn-sm btn-ghost w-100">
                ดูรายละเอียด
              </a>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col">
        <div class="alert alert-secondary mb-0">ยังไม่มีสินค้า</div>
      </div>
    @endforelse
  </div>

  {{-- Pagination: แสดงเฉพาะ Prev / Next (ไม่ซ้ำ) --}}
  <div class="mt-3">
    @if(method_exists(($items ?? null), 'links'))
      {{ $items->withQueryString()->links('pagination::simple-bootstrap-5') }}
    @endif
  </div>
@endsection
