@extends('frontend')
@section('css_before')

<style>
  
  /* --- ทำแถบหมวดหมู่ให้มีแถวเดียว --- */
.row.g-4.category-strip{
  display:flex;
  flex-wrap:nowrap;           /* ห้ามตัดบรรทัด */
  gap:1.25rem;                /* ระยะห่างระหว่างการ์ด */
  overflow-x:auto;            /* เลื่อนแนวนอนได้เมื่อเกิน */
  padding:.25rem .25rem .5rem;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
}
.row.g-4.category-strip > [class^="col-"],
.row.g-4.category-strip > [class*=" col-"]{
  flex:0 0 200px;             /* ความกว้างการ์ดต่อใบ */
  max-width:200px;
}

/* ย่อการ์ดให้กระชับขึ้นนิดหน่อย */
.product-card{padding:.6rem;}
.product-card .pc-img{aspect-ratio:1/1;}
.product-card p{font-size:.95rem;}
  /* ===== Café Modern Theme — drop‑in overrides (safe to remove) ===== */
  :root{
    --brand:#e26b6b;         /* primary */
    --brand-2:#f4a9a8;       /* secondary accent */
    --ink:#2a2a2a;
    --muted:#6b6f76;
    --bg:#fffaf9;            /* warm off‑white */
    --card:#ffffff;
    --ring: rgba(226,107,107,.35);
    --shadow: 0 .5rem 1.25rem rgba(0,0,0,.08);
    --shadow-lg: 0 1rem 2rem rgba(0,0,0,.12);
    --radius: 1rem;
  }

  html,body{background:var(--bg); color:var(--ink);}
  h1,h2,h3,h4,h5{letter-spacing:.2px; color:var(--ink);}


  /* Buttons */
  .btn{border-radius: .9rem; padding:.55rem .95rem; border-width:2px;}
  .btn-primary{background:var(--brand); border-color:var(--brand);}
  .btn-primary:hover{background:#d45d5d; border-color:#d45d5d; box-shadow:0 0 0 .25rem var(--ring);}
  .btn-outline-primary{color:var(--brand); border-color:var(--brand); background:transparent;}
  .btn-outline-primary:hover{background:var(--brand); color:#fff;}

  /* Product cards */
  .product-card, .m-card{
    background:var(--card);
    border:0;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    transition: transform .16s ease, box-shadow .16s ease;
  }
  .product-card:hover, .m-card:hover{
    transform: translateY(-6px);
    box-shadow: var(--shadow-lg);
  }

  .product-card .pc-img, .m-thumb{
    position: relative;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    border-radius: calc(var(--radius) - .125rem);
    background: linear-gradient(180deg, #fff, #f7f7f7);
  }
  .product-card .pc-img img, .m-thumb img{
    position:absolute; inset:0; width:100%; height:100%; object-fit:cover;
    transform: scale(1); transition: transform .25s ease;
  }
  .product-card:hover .pc-img img, .m-card:hover .m-thumb img{
    transform: scale(1.04);
  }

  .price-tag{
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.35rem .65rem; border-radius:999px;
    background: rgba(226,107,107,.08); color: var(--brand);
    font-weight:600; font-size:.95rem;
  }

  /* Badges / category pills */
  .pill{display:inline-block; padding:.3rem .6rem; border-radius:999px;
        background:#fff0f0; color:#b24f4f; font-weight:600; font-size:.8rem;}

  /* Tables (if present) */
  table.table{--bs-table-bg: #fff; --bs-table-striped-bg: #fff7f7; border-radius: var(--radius); overflow:hidden; box-shadow: var(--shadow);}
  .table thead th{background:#ffe7e7 !important; color:#7e3c3b; border:0;}
  .table tbody td{vertical-align:middle;}

  /* Forms */
  .form-control{border-radius:.8rem; border:2px solid #f0d6d6;}
  .form-control:focus{border-color:var(--brand); box-shadow:0 0 0 .25rem var(--ring);}

  /* Section headings */
  .section-title{
    position:relative; display:inline-block; padding-bottom:.2rem; margin-bottom:1rem;
  }
  .section-title::after{
    content:""; position:absolute; left:0; right:0; bottom:0; height:4px;
    background:linear-gradient(90deg, var(--brand), var(--brand-2));
    border-radius:4px;
  }

  /* Utilities */
  .soft {color:var(--muted);}
  .card-spacer {padding: .9rem .95rem 1rem;}
</style>
<style>
  /* การ์ดสวยๆ + โฮเวอร์ลอยนิดๆ */
  .m-card{border:0; border-radius:1rem; overflow:hidden; box-shadow:0 .25rem .75rem rgba(0,0,0,.06); transition:transform .18s ease, box-shadow .18s ease;}
  .m-card:hover{transform:translateY(-4px); box-shadow:0 .75rem 1.5rem rgba(0,0,0,.12);}
  .m-card .card-body{padding: .9rem .95rem 1rem;}

  /* โซนรูปให้เป็นสี่เหลี่ยมจัตุรัส และครอบรูปพอดี */
  .m-thumb{position:relative; aspect-ratio:1/1; background:#f7f7f7;}
  .m-thumb img{position:absolute; inset:0; width:100%; height:100%; object-fit:cover;}
  .m-thumb::after{content:""; position:absolute; inset:0; 
    background:linear-gradient(to top, rgba(0,0,0,.35), rgba(0,0,0,0) 60%);
    pointer-events:none;
  }

  /* แบรนด์มุมซ้ายบน + เรตติ้งมุมขวาล่าง */
  .m-badge-brand{position:absolute; left:.5rem; top:.5rem; 
    background:rgba(255,255,255,.92); padding:.25rem .55rem; border-radius:999px; 
    font-size:.75rem; font-weight:600; color:#444;
  }
  .m-rating{position:absolute; right:.5rem; bottom:.5rem; display:flex; align-items:center; gap:.35rem;
    background:rgba(255,255,255,.95); padding:.2rem .45rem; border-radius:.6rem; 
    font-weight:700; font-size:.9rem; color:#d60000;
  }
  .m-rating svg{width:14px; height:14px; flex:0 0 14px;}

  /* ตัดบรรทัดชื่อสินค้าไม่ให้ยาวเกิน */
  .line-clamp-2{display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;}

  /* ราคาให้เด่น */
  .m-price{font-weight:800;}
    /* card ยกตัว + เงา */
  .product-card{
    position:relative;
    border:0;
    border-radius:1rem;
    background:#fff;
    box-shadow:0 .25rem .75rem rgba(0,0,0,.06);
    transition:transform .18s ease, box-shadow .18s ease;
    padding:.75rem;
  }
  .product-card:hover{
    transform:translateY(-6px);
    box-shadow:0 .75rem 1.5rem rgba(0,0,0,.12);
  }

  /* รูป + ครอบมุมโค้ง */
   .product-card .pc-img{
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;          /* <- ถ้าอยากสี่เหลี่ยมผืนผ้า ใช้ 4/3 หรือ 16/9 */
    border-radius: .9rem;
    overflow: hidden;
    background: #f7f7f7;
    margin-bottom: .5rem;
  }
  .product-card .pc-img img{
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    object-fit: cover;             /* ครอบเต็มและตัดส่วนเกิน */
    object-position: center;
    display: block;
  }
  .product-card:hover .pc-img img{
    transform:scale(1.06);
    filter:saturate(1.05) contrast(1.03);
  }

  /* แถบสว่าง (shine) ตอน hover */
  .product-card .pc-img::after{
    content:"";
    position:absolute; inset:0;
    background:linear-gradient(120deg, transparent 0%, rgba(255,255,255,.35) 20%, transparent 40%);
    transform:translateX(-120%);
    pointer-events:none;
  }
  .product-card:hover .pc-img::after{
    animation:pc-shine 900ms ease;
  }
  @keyframes pc-shine { to { transform:translateX(120%); } }

  /* ป้ายหมวดหมู่มุมซ้ายบน */
  .product-card .pc-tag{
    position:absolute; left:.6rem; top:.6rem;
    background:rgba(255,255,255,.92);
    color:#aa7c7a; font-weight:700; font-size:.75rem;
    padding:.2rem .6rem; border-radius:999px;
  }

  .product-card p{margin:0; font-weight:600; color:#333;}

  /* ลดแอนิเมชันสำหรับคนไม่ชอบ motion */
  @media (prefers-reduced-motion: reduce){
    .product-card, .product-card .pc-img img{transition:none}
    .product-card:hover{transform:none; box-shadow:0 .25rem .75rem rgba(0,0,0,.06)}
  }
    /* --- ทำแถบหมวดหมู่ให้มีแถวเดียว --- */
  .row.g-4.category-strip{
    display:flex;
    flex-wrap:nowrap;
    gap:1.25rem;
    overflow-x:auto;
    padding:.25rem .25rem .5rem;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
  }
  .row.g-4.category-strip > [class^="col-"],
  .row.g-4.category-strip > [class*=" col-"]{
    flex:0 0 200px;
    max-width:200px;
    transition: transform 0.2s ease-in-out;
  }
  .row.g-4.category-strip > [class^="col-"]:hover {
    transform: scale(1.05);
  }

  /* --- การ์ดสินค้า --- */
  .product-card{
    position:relative;
    border: 0;
    border-radius:1rem;
    background:#fff;
    box-shadow:0 .5rem 1.25rem rgba(0,0,0,.1);
    transition: transform .2s ease, box-shadow .2s ease;
    padding:.75rem;
  }
  .product-card:hover{
    transform: translateY(-6px);
    box-shadow: 0 1rem 2rem rgba(0,0,0,.12);
  }

  .product-card .pc-img{
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;
    border-radius: .9rem;
    overflow: hidden;
    background: #f7f7f7;
    margin-bottom: .5rem;
    transition: transform .3s ease;
  }
  .product-card:hover .pc-img{
    transform: scale(1.06);
  }

  .product-card .pc-img img{
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  /* ป้ายหมวดหมู่ */
  .product-card .pc-tag{
    position: absolute;
    left:.6rem;
    top:.6rem;
    background:rgba(255,255,255,.92);
    color:#aa7c7a;
    font-weight:700;
    font-size:.75rem;
    padding:.2rem .6rem;
    border-radius:999px;
  }

  /* ปุ่ม more detail */
  .btn-success{
    background-color: #d60000;
    border-color: #d60000;
    transition: background-color .3s ease, border-color .3s ease;
  }
  .btn-success:hover{
    background-color: #aa0000;
    border-color: #aa0000;
  }

  /* --- ผลการแสดงสินค้า --- */
  .m-rating {
    position: absolute;
    right: .5rem;
    bottom: .5rem;
    background: rgba(255,255,255,.95);
    padding: .2rem .45rem;
    border-radius: .6rem;
    font-weight:700;
    font-size:.9rem;
    color:#d60000;
  }

  .m-price{
    font-weight:800;
    color: #d60000;
  }

  .category-strip a {
    text-decoration: none;
  }
</style>
@endsection

@section('navbar')
@endsection

@section('showProduct')
<!-- start banner -->
    <div class="container">
      <div class="row">
        <div class="col">
          <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active" data-bs-interval="3000">
                <img src="assets/img/catBanner2.jpg" class="d-block w-100" alt="..." />
              </div>
              <div class="carousel-item" data-bs-interval="3000">
                <img src="assets/img/catBanner3.jpg" class="d-block w-100" alt="..." />
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- end banner -->
  <div class="container mt-2 mb-2">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-12">
            <!-- start product cards -->
    <section class="container my-5">
      <h2 class="mb-4" style="color: #d60000">Discover our recommend products</h2>
@php
  use App\Models\CategorieModel;
  use Illuminate\Support\Str;

  $categories = CategorieModel::orderBy('category_name')->get();

  // เลือกไอคอนให้หมวดแบบง่าย ๆ จากคีย์เวิร์ดในชื่อหมวด (ไม่มีรูปในตาราง ก็แมปด้วย asset ได้)
  $iconFor = function (string $name): string {
    $n = Str::lower($name);
    if (Str::contains($n, ['ขนม', 'snack']))  return 'assets/img/cat snack 1.png';
    if (Str::contains($n, ['ของเล่น', 'play']))  return 'assets/img/cat play 1.png';
    if (Str::contains($n, ['บ้าน', 'home']))  return 'assets/img/cat home 1.png';
    if (Str::contains($n, ['อาหาร','food']))  return 'assets/img/cat food 1.png';
    if (Str::contains($n, ['ทราย','litter'])) return 'assets/img/cat litter 1.png';
    // default เผื่อหมวดอื่น ๆ
    return 'assets/img/cat snack 1.png';
  };
@endphp

<div class="row justify-content-center g-4 category-strip">
  @forelse($categories as $cat)
    <div class="col-12 col-sm-6 col-md-3">
      <a href="{{ route('shop.index', ['category_id' => $cat->category_id]) }}" class="text-decoration-none text-dark">
        <div class="product-card text-center h-100">
          <div class="pc-img" style="aspect-ratio:1/1; overflow:hidden;">
            <img src="{{ asset($iconFor($cat->category_name)) }}" alt="{{ $cat->category_name }}" style="width:100%;height:100%;object-fit:cover;">
          </div>
          <p class="fw-semibold mt-2">{{ $cat->category_name }}</p>
        </div>
      </a>
    </div>
  @empty
    <div class="col-12">
      <div class="alert alert-secondary">ยังไม่มีหมวดหมู่</div>
    </div>
  @endforelse
</div>
 </section>
    <!-- end product cards -->
      <div class="alert alert-success" role="alert">
          Update latest product</div>
      </div>
    </div>
  </div>
  @yield('navbar')


 <div class="row">
@foreach($merchandise as $data)
  <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
    <article class="card m-card position-relative h-100">
      <div class="m-thumb">
        <img 
          src="{{ asset('storage/'.$data->merchandise_image) }}" 
          alt="{{ $data->merchandise_name }}" 
          loading="lazy"
          onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22><rect width=%22400%22 height=%22300%22 fill=%22%23f2f2f2%22/><text x=%2250%22 y=%22160%22 font-size=%2240%22 fill=%22%23999%22>no image</text></svg>';"
        >


        <span class="m-rating">
          {{-- ไอคอนดาวสีแดง (inline SVG) --}}
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path fill="#d60000" d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.62L12 2 9.19 8.62 2 9.24l5.46 4.73L5.82 21z"/>
          </svg>
          {{ number_format($data->rating_avg ?? 0, 2) }}
        </span>
      </div>

      <div class="card-body">
        <h6 class="card-title mb-1 line-clamp-2">{{ $data->merchandise_name }}</h6>
        <div class="small text-muted">
          {{ \Illuminate\Support\Carbon::parse($data->created_at)->format('Y-m-d') }}
        </div>

        <div class="d-flex justify-content-between align-items-center mt-2">
          <span class="m-price text-danger">฿{{ number_format($data->price ?? 0, 2) }}</span>
          <a href="{{ route('shop.show', $data->merchandise_id) }}" class="btn btn-success btn-sm">more detail</a>
        </div>

        {{-- ทำให้ทั้งการ์ดคลิกได้ --}}
        <a href="{{ route('shop.show', $data->merchandise_id) }}" class="stretched-link" aria-label="ดูรายละเอียด {{ $data->merchandise_name }}"></a>
      </div>
    </article>
  </div>
@endforeach
</div>

<div class="row mt-2 mb-2">
  <div class="col-sm-5 col-md-5"></div>
  <div class="col-sm-3 col-md-3">
    <center>{{ $merchandise->links() }}</center>
  </div>
</div>



  {{-- Reviews on home --}}
  @include('home.partials.reviews')
@endsection


@section('footer')
@endsection

@section('js_before')
@endsection

{{-- devbanban.com --}}