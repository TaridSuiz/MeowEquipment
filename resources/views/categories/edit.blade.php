@extends('home')

@section('content')
<h3>:: Edit Category ::</h3>

<form action="{{ route('admin.categories.update', $category->category_id) }}" method="POST">
  @csrf @method('PUT')
  <div class="mb-3">
    <label class="form-label">Category name *</label>
    <input type="text" name="category_name" class="form-control" required maxlength="100"
           value="{{ old('category_name', $category->category_name) }}">
    @error('category_name') <div class="text-danger">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
  </div>

  <button class="btn btn-primary">Save</button>
  <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
