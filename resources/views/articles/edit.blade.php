@extends('home')

@section('content')
<h3>:: Edit Article ::</h3>

<form action="{{ route('admin.articles.update', $article->article_id) }}" method="POST" enctype="multipart/form-data">
  @csrf @method('PUT')

  <div class="mb-3">
    <label class="form-label">Title *</label>
    <input type="text" name="title" class="form-control" required maxlength="200"
           value="{{ old('title', $article->title) }}">
  </div>

  <div class="mb-3">
    <label class="form-label">Content *</label>
    <textarea name="content" class="form-control" rows="8" required>{{ old('content', $article->content) }}</textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Cover image</label>
    @if ($article->cover_image)
      <div class="mb-2">
        <img src="{{ asset('storage/'.$article->cover_image) }}" alt="" style="height:80px">
      </div>
    @endif
    <input type="file" name="cover_image" class="form-control" accept="image/*">
  </div>

  <button class="btn btn-primary">Save</button>
  <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
