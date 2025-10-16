@extends('home')

@section('content')
<h3>:: Create Article ::</h3>

<form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <div class="mb-3">
    <label class="form-label">Title *</label>
    <input type="text" name="title" class="form-control" required maxlength="200" value="{{ old('title') }}">
    @error('title') <div class="text-danger">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Content *</label>
    <textarea name="content" class="form-control" rows="8" required>{{ old('content') }}</textarea>
    @error('content') <div class="text-danger">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Cover image</label>
    <input type="file" name="cover_image" class="form-control" accept="image/*">
    <small class="text-muted">jpeg/png/jpg ไม่เกิน 5MB</small>
    @error('cover_image') <div class="text-danger">{{ $message }}</div> @enderror
  </div>

  <button class="btn btn-primary">Save</button>
  <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
