@extends('home')

@section('content')
  <h3>:: Form Add Category ::</h3>

  <form action="{{ route('category.store') }}" method="POST">
    @csrf

    {{-- ชื่อหมวดหมู่ --}}
    <div class="form-group row mb-2">
      <label class="col-sm-2">ชื่อหมวดหมู่ <span class="text-danger">*</span></label>
      <div class="col-sm-7">
        <input type="text" class="form-control" name="category_name" required minlength="3"
               placeholder="เช่น อุปกรณ์ไฟฟ้า"
               value="{{ old('category_name') }}">
        @error('category_name') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- รายละเอียด (ไม่บังคับ) --}}
    <div class="form-group row mb-2">
      <label class="col-sm-2">รายละเอียด</label>
      <div class="col-sm-7">
        <textarea name="description" class="form-control" rows="4"
                  placeholder="รายละเอียดหมวดหมู่ (ถ้ามี)">{{ old('description') }}</textarea>
        @error('description') <div class="text-danger">{{ $message }}</div> @enderror
        <small class="text-muted">* วันที่เพิ่มจะบันทึกอัตโนมัติ</small>
      </div>
    </div>

    {{-- ปุ่ม --}}
    <div class="form-group row mb-2">
      <label class="col-sm-2"></label>
      <div class="col-sm-7">
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <a href="{{ route('category.index') }}" class="btn btn-danger">ยกเลิก</a>
      </div>
    </div>
  </form>
@endsection
