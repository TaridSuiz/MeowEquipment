{{-- Reviews Section on Home (dashboard) --}}
<div class="container my-4">
  <div class="d-flex align-items-center justify-content-between mb-2">
    <h4 class="mb-0">รีวิวล่าสุด</h4>
    <a href="{{ route('shop.index') }}" class="text-decoration-none">ดูสินค้า</a>
  </div>

  @php
    // Fallback: ถ้า controller ไม่ได้ส่ง $homeReviews มา ให้ดึงเอง
    if (!isset($homeReviews)) {
      try {
        $homeReviews = \App\Models\ReviewModel::with(['user','merchandise'])
                          ->orderByDesc('created_at')
                          ->limit(6)
                          ->get();
      } catch (\Throwable $e) {
        $homeReviews = collect();
      }
    }
    $items = $homeReviews ?? collect();
  @endphp

  @if($items->count() === 0)
    <div class="alert alert-light border">ยังไม่มีรีวิว</div>
  @else
    <div class="row g-3">
      @foreach($items as $rv)
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between mb-1">
                <strong class="small">{{ $rv->user->name ?? $rv->user->user_name ?? 'ผู้ใช้' }}</strong>
                @php $r = (int)($rv->rating ?? 0); @endphp
                <span class="small">
                  {!! str_repeat('★', max(0,min(5,$r))) !!}{!! str_repeat('☆', max(0,5-$r)) !!}
                </span>
              </div>
              <div class="small text-muted mb-2">
                {{ optional($rv->merchandise)->merchandise_name ?? 'สินค้า' }}
                • {{ \Carbon\Carbon::parse($rv->created_at)->format('d/m/Y') }}
              </div>
              <p class="mb-0">{{ \Illuminate\Support\Str::limit($rv->comment, 140) }}</p>
            </div>
            @if(optional($rv->merchandise)->merchandise_id)
            <div class="card-footer bg-transparent border-0 pt-0">
              <a class="btn btn-sm btn-outline-secondary"
                 href="{{ route('shop.show', ['id' => $rv->merchandise->merchandise_id]) }}">
                ดูสินค้า
              </a>
            </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>