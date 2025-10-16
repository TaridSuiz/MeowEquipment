@extends('home')

@section('content')
<h3>:: Create Merchandise ::</h3>

<form action="{{ route('admin.merchandise.store') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <div class="mb-3">
    <label class="form-label">Category *</label>
    <select name="category_id" class="form-select" required>
      <option value="" hidden>-- เลือกหมวดหมู่ --</option>
      @foreach ($categories as $c)
        <option value="{{ $c->category_id }}" @selected(old('category_id')==$c->category_id) >
          [#{{ $c->category_id }}] {{ $c->category_name }}
        </option>
      @endforeach
    </select>
    @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Name *</label>
    <input type="text" name="merchandise_name" class="form-control" required maxlength="150" value="{{ old('merchandise_name') }}">
    @error('merchandise_name') <div class="text-danger">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-3">
      <label class="form-label">Price</label>
      <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">Brand</label>
      <input type="text" name="brand" class="form-control" value="{{ old('brand') }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">Age range</label>
      <input type="text" name="age_range" class="form-control" value="{{ old('age_range') }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">Rating avg</label>
      <input type="number" step="0.1" name="rating_avg" class="form-control" value="{{ old('rating_avg') }}">
    </div>
  </div>

  <div class="mb-3">
    <label class="form-label">Link store</label>
    <input type="text" name="link_store" class="form-control" value="{{ old('link_store') }}">
  </div>

  <div class="mb-3">
    <label class="form-label">Image</label>
    <input type="file" name="merchandise_image" class="form-control" accept="image/*">
    <small class="text-muted">jpeg/png/jpg ไม่เกิน 5MB</small>
  </div>

  <button class="btn btn-primary">Save</button>
  <a href="{{ route('admin.merchandise.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
