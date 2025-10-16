@extends('layouts.backend')

@section('css_before')
<style>
  :root{
    --brand:#e26b6b; --brand-2:#f4a9a8; --ink:#2a2a2a; --muted:#6b6f76;
    --bg:#fffaf9; --card:#ffffff; --ring: rgba(226,107,107,.35);
    --shadow: 0 .5rem 1.25rem rgba(0,0,0,.08); --shadow-lg: 0 1rem 2rem rgba(0,0,0,.12);
    --radius: 1rem;
  }
  body{background:var(--bg);}
  .reading-progress{position:sticky; top:0; left:0; right:0; height:4px; background:rgba(0,0,0,.06); z-index:1030}
  .reading-progress .bar{height:100%; width:0; background:linear-gradient(90deg, var(--brand), var(--brand-2));}

  .article-hero{background:linear-gradient(180deg,#fff,#fff8f7); border-radius:calc(var(--radius) + .4rem); box-shadow:var(--shadow);}
  .article-cover{border-radius: var(--radius); overflow:hidden; background:#f7f7f7; box-shadow:var(--shadow)}
  .article-meta{color:var(--muted); font-size:.95rem}
  .badge-cat{background:#fff0f0; color:#b24f4f; border-radius:999px; padding:.35rem .7rem; font-weight:700}
  .prose{font-size:1.05rem; line-height:1.85; color:var(--ink);}
  .prose h2,.prose h3{margin-top:1.6rem; margin-bottom:.6rem; font-weight:800; letter-spacing:.2px}
  .prose h2{font-size:1.6rem}
  .prose h3{font-size:1.25rem}
  .prose p{margin:.75rem 0}
  .prose img{max-width:100%; height:auto; border-radius:.75rem; box-shadow:var(--shadow); margin:.5rem 0}
  .prose blockquote{border-left:4px solid var(--brand); padding:.5rem 1rem; background:#fff; border-radius:.5rem; color:#444}
  .divider{height:1px; background:linear-gradient(90deg,#ffe1e1,transparent)}
  .card-soft{border:0; border-radius:1rem; background:#fff; box-shadow:var(--shadow)}
  .btn-outline-brand{color:var(--brand); border-color:var(--brand); border-width:2px; border-radius:999px}
  .btn-outline-brand:hover{background:var(--brand); color:#fff}
</style>
@endsection

@section('content')
@php
  $title    = $article->title ?? $article->article_title ?? 'บทความ';
  $cover    = $article->cover_image ?? $article->article_image ?? null;
  $created  = $article->created_at ?? null;
  $author   = optional($article->author)->name ?? ($article->author_name ?? 'ทีมงาน');
  $category = $article->category->category_name ?? ($article->category_name ?? null);
  $content  = $article->content ?? $article->article_content ?? '';

  // ประมาณเวลาอ่าน (ไทยใช้อักษรเป็นหลัก)
  $plain    = trim(preg_replace('/\s+/u', ' ', strip_tags($content)));
  $chars    = function_exists('mb_strlen') ? mb_strlen($plain) : strlen($plain);
  $readMin  = max(1, ceil($chars / 1200)); // ~1200 ตัวอักษร/นาที
@endphp

<div class="reading-progress"><div class="bar" id="readBar"></div></div>

<div class="container my-4 my-md-5">

  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">บทความ</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
    </ol>
  </nav>

  {{-- HERO --}}
  <div class="article-hero p-3 p-md-4 mb-4">
    <div class="row g-4 align-items-center">
      <div class="col-md-7">
        @if($category)
          <span class="badge-cat me-2">{{ $category }}</span>
        @endif
        <h1 class="mt-3 mb-2" style="font-weight:900; letter-spacing:.3px">{{ $title }}</h1>
        <div class="article-meta d-flex flex-wrap align-items-center gap-3">
          <span>โดย {{ $author }}</span>
          @if($created)
            <span>• {{ \Carbon\Carbon::parse($created)->format('d M Y') }}</span>
          @endif
          <span>• {{ $readMin }} นาที</span>
        </div>

        <div class="mt-3 d-flex gap-2">
          <button class="btn btn-outline-brand" type="button" id="btnCopyLink">คัดลอกลิงก์</button>
          <a class="btn btn-outline-brand" href="{{ route('articles.index') }}">กลับหน้ารวม</a>
        </div>
      </div>
      <div class="col-md-5">
        <div class="article-cover">
          @if(!empty($cover))
            <img src="{{ asset('storage/'.$cover) }}" alt="{{ $title }}" class="w-100" style="object-fit:cover; aspect-ratio: 4/3">
          @else
            <img src="data:image/svg+xml;utf8,
              <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 600'>
                <rect width='800' height='600' fill='%23f4f4f4'/>
                <text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='%23999' font-size='36'>ไม่มีภาพหน้าปก</text>
              </svg>" class="w-100" alt="">
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    {{-- MAIN CONTENT --}}
    <div class="col-lg-9">
      <article class="prose card-soft p-3 p-md-4">
        @if(!empty($content))
          {{-- ถ้าคอนเทนต์ของคุณเป็น text ธรรมดา ให้ใช้แบบปลอดภัยด้านล่างแทน --}}
          {{-- <div>{!! nl2br(e($content)) !!}</div> --}}
          {!! $content !!}
        @else
          <p class="text-muted">ยังไม่มีเนื้อหา</p>
        @endif
      </article>

      <div class="divider my-4"></div>

      {{-- PREV / NEXT (จะแสดงเมื่อคอนโทรลเลอร์ส่งตัวแปรมา) --}}
      @if(isset($prev) || isset($next))
      <div class="d-flex justify-content-between flex-wrap gap-2">
        <div>
          @isset($prev)
            <a class="btn btn-outline-brand" href="{{ route('articles.show', $prev->article_id ?? $prev->id) }}">← บทก่อนหน้า</a>
          @endisset
        </div>
        <div>
          @isset($next)
            <a class="btn btn-outline-brand" href="{{ route('articles.show', $next->article_id ?? $next->id) }}">บทถัดไป →</a>
          @endisset
        </div>
      </div>
      @endif
    </div>

    {{-- SIDEBAR --}}
    <div class="col-lg-3">
      <div class="card-soft p-3">
        <h6 class="mb-2" style="font-weight:800">รายละเอียด</h6>
        <div class="small text-muted">
          @if($created)<div>เผยแพร่: {{ \Carbon\Carbon::parse($created)->format('d M Y') }}</div>@endif
          <div>เวลาอ่าน: {{ $readMin }} นาที</div>
          @if($category)<div>หมวด: <span class="badge-cat">{{ $category }}</span></div>@endif
        </div>
        <hr>
        <button class="btn w-100 btn-outline-brand" id="btnCopyLink2">คัดลอกลิงก์</button>
      </div>
    </div>
  </div>

  {{-- RELATED (จะแสดงเมื่อส่ง $related มา) --}}
  @if(isset($related) && $related->count())
    <div class="mt-5">
      <h4 class="mb-3" style="font-weight:900">บทความที่เกี่ยวข้อง</h4>
      <div class="row row-cols-1 row-cols-md-2 g-3">
        @foreach($related as $it)
          <div class="col">
            <a href="{{ route('articles.show', $it->article_id ?? $it->id) }}" class="text-decoration-none text-dark">
              <div class="card card-soft h-100">
                @php $relCover = $it->cover_image ?? $it->article_image ?? null; @endphp
                @if($relCover)
                  <img src="{{ asset('storage/'.$relCover) }}" class="card-img-top" style="height:180px;object-fit:cover" alt="">
                @endif
                <div class="card-body">
                  <h5 class="card-title mb-1">{{ $it->title ?? $it->article_title }}</h5>
                  <div class="text-muted small mb-2">
                    {{ \Carbon\Carbon::parse($it->created_at ?? now())->format('d M Y') }}
                  </div>
                  <p class="card-text">
                    {{ \Illuminate\Support\Str::limit(strip_tags($it->summary ?? $it->article_summary ?? ($it->content ?? $it->article_content)), 110) }}
                  </p>
                </div>
              </div>
            </a>
          </div>
        @endforeach
      </div>

      <div class="mt-3">
        <a class="btn btn-outline-brand" href="{{ route('articles.index') }}">ดูบทความทั้งหมด</a>
      </div>
    </div>
  @endif
</div>
@endsection

@section('js_before')
<script>
  // Copy link
  function copyCurrentURL(){
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(()=>alert('คัดลอกลิงก์แล้ว')).catch(()=>{});
  }
  document.getElementById('btnCopyLink')?.addEventListener('click', copyCurrentURL);
  document.getElementById('btnCopyLink2')?.addEventListener('click', copyCurrentURL);

  // Reading progress
  const bar = document.getElementById('readBar');
  const onScroll = () => {
    const doc = document.documentElement;
    const total = doc.scrollHeight - doc.clientHeight;
    const prog = total > 0 ? (doc.scrollTop / total) * 100 : 0;
    if (bar) bar.style.width = prog + '%';
  };
  window.addEventListener('scroll', onScroll, {passive:true});
  onScroll();
</script>
@endsection
