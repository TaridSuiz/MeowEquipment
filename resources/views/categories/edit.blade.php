@extends('home')

@section('js_before')
  @include('sweetalert::alert')
@endsection

@section('content')
  <h3>:: Edit Category ::</h3>

  <form action="{{ route('category.update', $id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- ชื่อหมวดหมู่ --}}
    <div class="form-group row mb-2">
      <label class="col-sm-2">ชื่อหมวดหมู่ <span class="text-danger">*</span></label>
      <div class="col-sm-7">
        <input type="text" class="form-control" name="category_name" required minlength="3"
               value="{{ old('category_name', $category_name) }}">
        @error('category_name') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- รายละเอียด (ไม่บังคับ) --}}
    <div class="form-group row mb-3">
      <label class="col-sm-2">รายละเอียด</label>
      <div class="col-sm-7">
        <textarea name="description" class="form-control" rows="4"
                  placeholder="รายละเอียดหมวดหมู่ (ถ้ามี)">{{ old('description', $description) }}</textarea>
        @error('description') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- วันที่เพิ่ม (แสดงอย่างเดียว) --}}
    <div class="form-group row mb-3">
      <label class="col-sm-2">วันที่เพิ่ม</label>
      <div class="col-sm-7">
        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($created_at)->format('Y-m-d H:i') }}" readonly>
      </div>
    </div>

    {{-- ปุ่ม --}}
    <div class="form-group row mb-2">
      <label class="col-sm-2"></label>
      <div class="col-sm-7">
        <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
        <a href="{{ route('category.index') }}" class="btn btn-danger">ยกเลิก</a>
      </div>
    </div>
  </form>
@endsection
