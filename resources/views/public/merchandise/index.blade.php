@extends('backend')

@section('content')
    <h3 class="mb-3">เลือกสินค้าเพื่อเปรียบเทียบ (เลือก 2 ชิ้น)</h3>

    {{-- แสดง error ถ้าเลือกสินค้าไม่ครบ 2 ชิ้น --}}
    @if($errors->has('compare'))
        <div class="alert alert-danger">{{ $errors->first('compare') }}</div>
    @endif

    {{-- ฟอร์มค้นหาแบบง่าย ๆ --}}
    <form action="{{ route('public.catalog') }}" method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อสินค้า...">
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-secondary">ค้นหา</button>
        </div>
    </form>

    {{-- ฟอร์มส่งไปหน้า compare (ใช้ GET ง่าย ๆ ) --}}
    <form action="{{ route('public.compare') }}" method="GET">
        <div class="row g-3">
            @forelse($merch as $m)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        @php
                            $img = $m->merchandise_image
                                ? asset('storage/'.$m->merchandise_image)
                                : 'https://via.placeholder.com/600x400?text=No+Image';
                        @endphp
                        <img src="{{ $img }}" class="card-img-top" alt="{{ $m->merchandise_name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $m->merchandise_name }}</h5>
                            <p class="mb-1">ราคา: ฿{{ number_format($m->price, 2) }}</p>
                            <p class="mb-1">แบรนด์: {{ $m->brand ?? '-' }}</p>
                            <p class="mb-2">คะแนนเฉลี่ย: {{ number_format($m->rating_avg ?? 0, 2) }}</p>

                            <label class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       name="merchandise_ids[]"
                                       value="{{ $m->merchandise_id }}">
                                <span class="form-check-label">เลือกเพื่อเปรียบเทียบ</span>
                            </label>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">ไม่พบสินค้า</div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                {{ $merch->links() }}
            </div>
            <div>
                <button type="submit" class="btn btn-primary">
                    เปรียบเทียบ 2 ชิ้น
                </button>
            </div>
        </div>
    </form>
@endsection
