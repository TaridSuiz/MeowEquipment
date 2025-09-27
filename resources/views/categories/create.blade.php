@extends('home')

@section('content')
  <h3 class="mb-3">:: Add Category ::</h3>

  <form action="{{ route('category.store') }}" method="POST">
    @csrf

    {{-- ชื่อหมวดหมู่ --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">ชื่อหมวดหมู่ *</label>
      <div class="col-sm-7">
        <input type="text" name="category_name" class="form-control" required minlength="3"
               value="{{ old('category_name') }}" placeholder="เช่น อุปกรณ์แมว, อาหารแมว">
        @error('category_name') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- รายละเอียด --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">รายละเอียด</label>
      <div class="col-sm-7">
        <textarea name="description" class="form-control" rows="4"
                  placeholder="รายละเอียดหมวดหมู่ (ถ้ามี)">{{ old('description') }}</textarea>
        @error('description') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- ปุ่ม --}}
    <div class="row">
      <label class="col-sm-2"></label>
      <div class="col-sm-7">
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <a href="{{ route('category.index') }}" class="btn btn-secondary">ยกเลิก</a>
      </div>
    </div>
  </form>
@endsection
