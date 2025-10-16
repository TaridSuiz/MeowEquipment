@extends('layouts.backend')

@section('content')
<h3 class="mb-3" style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #d60000;">
   บทความ
</h3>

<form method="GET" action="{{ route('articles.index') }}" class="row g-2 mb-3">
  <div class="col-md-6">
    <input type="text" name="q" class="form-control" value="{{ $q }}" placeholder="ค้นหาบทความ...">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-secondary">ค้นหา</button>
  </div>
</form>

@forelse($articles as $a)
  <div class="mb-3">
    <h5><a href="{{ route('articles.show', $a->article_id) }}">{{ $a->title }}</a></h5>
    <div class="small text-muted">โดย {{ optional($a->author)->name ?? '-' }} | {{ $a->created_at }}</div>
  </div>
@empty
  <div class="text-muted">ยังไม่มีบทความ</div>
@endforelse

{{ $articles->links() }}
@endsection
