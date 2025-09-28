@extends('home')

@section('content')
  <h3 class="mb-3">:: Create Article ::</h3>

  <form action="{{ url('/article') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Title *</label>
      <div class="col-sm-7">
        <input type="text" name="title" class="form-control" required minlength="3"
               value="{{ old('title') }}" placeholder="หัวข้อบทความ">
        @error('title') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Author *</label>
      <div class="col-sm-5">
        <select name="author_id" class="form-select" required>
          <option value="">-- เลือกผู้เขียน --</option>
          @foreach($authors as $a)
            <option value="{{ $a->user_id }}" @selected(old('author_id')==$a->user_id)>{{ $a->name }}</option>
          @endforeach
        </select>
        @error('author_id') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Content *</label>
      <div class="col-sm-9">
        <textarea name="content" rows="8" class="form-control" required
                  placeholder="เนื้อหาบทความ">{{ old('content') }}</textarea>
        @error('content') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="mb-4 row">
      <label class="col-sm-2 col-form-label">Cover Image</label>
      <div class="col-sm-6">
        <input type="file" name="cover_image" accept="image/*" class="form-control">
        <small class="text-muted">รองรับ jpeg, png, jpg ขนาดไม่เกิน 5MB</small>
        @error('cover_image') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="row">
      <label class="col-sm-2"></label>
      <div class="col-sm-6">
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <a href="{{ url('/article') }}" class="btn btn-secondary">ยกเลิก</a>
      </div>
    </div>
  </form>
@endsection
