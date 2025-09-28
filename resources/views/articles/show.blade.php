@extends('home')

@section('content')
<h3 class="mb-2">{{ $article->title }}</h3>
<div class="small text-muted mb-3">โดย {{ optional($article->author)->name ?? '-' }} | {{ $article->created_at }}</div>

@if($article->cover_image)
  <img src="{{ asset('storage/'.$article->cover_image) }}" class="img-fluid mb-3" alt="">
@endif

<div>{!! nl2br(e($article->content)) !!}</div>
@endsection
