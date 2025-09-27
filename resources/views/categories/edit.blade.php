@extends('home')

@section('content')
  <h3 class="mb-3">:: Edit Category ::</h3>

  <form action="{{ route('category.update', $id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- ชื่อหมวดหมู่ --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">ชื่อหมวดหมู่ *</label>
      <div class="col-sm-7">
        <input type="text" name="category_name" class="form-control" required minlength="3"
               value="{{ old('category_name', $category_name) }}">
        @error('category_name') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- รายละเอียด --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">รายละเอียด</label>
      <div class="col-sm-7">
        <textarea name="description" class="form-control" rows="4"
                  placeholder="รายละเอียดหมวดหมู่ (ถ้ามี)">{{ old('description', $description) }}</textarea>
        @error('description') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- วันที่เพิ่ม (อ่านอย่างเดียว) --}}
    <div class="mb-4 row">
      <label class="col-sm-2 col-form-label">วันที่เพิ่ม</label>
      <div class="col-sm-7">
        <input type="text" class="form-control"
               value="{{ \Carbon\Carbon::parse($created_at)->format('Y-m-d H:i') }}" readonly>
      </div>
    </div>

    {{-- ปุ่ม --}}
    <div class="row">
      <label class="col-sm-2"></label>
      <div class="col-sm-7">
        <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
        <a href="{{ route('category.index') }}" class="btn btn-secondary">ยกเลิก</a>
      </div>
    </div>
  </form>
@endsection
