@extends('home')

@section('content')
  <h3 class="mb-3">:: Edit Article ::</h3>

  <form action="{{ url('/article/'.$article->article_id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Title *</label>
      <div class="col-sm-7">
        <input type="text" name="title" class="form-control" required minlength="3"
               value="{{ old('title', $article->title) }}">
        @error('title') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Author *</label>
      <div class="col-sm-5">
        <select name="author_id" class="form-select" required>
          @foreach($authors as $a)
            <option value="{{ $a->user_id }}" @selected(old('author_id',$article->author_id)==$a->user_id)>
              {{ $a->name }}
            </option>
          @endforeach
        </select>
        @error('author_id') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Content *</label>
      <div class="col-sm-9">
        <textarea name="content" rows="8" class="form-control" required>{{ old('content',$article->content) }}</textarea>
        @error('content') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="mb-4 row">
      <label class="col-sm-2 col-form-label">Cover Image</label>
      <div class="col-sm-6">
        @if($article->cover_image)
          <div class="mb-2">รูปเดิม:</div>
          <img src="{{ asset('storage/'.$article->cover_image) }}" alt="" width="200" style="object-fit:cover" class="mb-2 d-block">
        @endif
        <input type="file" name="cover_image" accept="image/*" class="form-control">
        <small class="text-muted">อัปโหลดไฟล์ใหม่เพื่อเปลี่ยนรูปปก (ไม่บังคับ)</small>
        @error('cover_image') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="row">
      <label class="col-sm-2"></label>
      <div class="col-sm-6">
        <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
        <a href="{{ url('/article') }}" class="btn btn-secondary">ยกเลิก</a>
      </div>
    </div>
  </form>
@endsection
