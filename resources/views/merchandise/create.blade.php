@extends('home')

@section('content')
  <h3 class="mb-3">:: Add Merchandise ::</h3>

  <form action="{{ url('/merchandise') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Category --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">หมวดหมู่ *</label>
      <div class="col-sm-7">
        <select name="category_id" class="form-select" required>
          <option value="">-- เลือกหมวดหมู่ --</option>
          @foreach($categories as $c)
            <option value="{{ $c->category_id }}" @selected(old('category_id')==$c->category_id)>{{ $c->category_name }}</option>
          @endforeach
        </select>
        @error('category_id') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Name --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">ชื่อสินค้า *</label>
      <div class="col-sm-7">
        <input type="text" name="merchandise_name" class="form-control" required minlength="3"
               value="{{ old('merchandise_name') }}" placeholder="เช่น คอนโดแมว, ชามอาหาร">
        @error('merchandise_name') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Description --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">รายละเอียด</label>
      <div class="col-sm-7">
        <textarea name="description" rows="4" class="form-control" placeholder="รายละเอียดสินค้า (ถ้ามี)">{{ old('description') }}</textarea>
        @error('description') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Price & Rating --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">ราคา</label>
      <div class="col-sm-3">
        <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price') }}" placeholder="เช่น 690.00">
        @error('price') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <label class="col-sm-1 col-form-label text-end">เรตติ้ง</label>
      <div class="col-sm-3">
        <input type="number" step="0.01" min="0" max="5" name="rating_avg" class="form-control" value="{{ old('rating_avg') }}" placeholder="0 - 5">
        @error('rating_avg') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Brand & Age --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">แบรนด์</label>
      <div class="col-sm-3">
        <input type="text" name="brand" class="form-control" value="{{ old('brand') }}">
        @error('brand') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <label class="col-sm-1 col-form-label text-end">ช่วงวัย</label>
      <div class="col-sm-3">
        <input type="text" name="age_range" class="form-control" value="{{ old('age_range') }}" placeholder="เช่น ลูกแมว/โต/สูงวัย">
        @error('age_range') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Link store --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">ลิงก์ร้านค้า</label>
      <div class="col-sm-7">
        <input type="url" name="link_store" class="form-control" value="{{ old('link_store') }}" placeholder="https://...">
        @error('link_store') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Image --}}
    <div class="mb-4 row">
      <label class="col-sm-2 col-form-label">รูปสินค้า</label>
      <div class="col-sm-7">
        <input type="file" name="merchandise_image" class="form-control" accept="image/*">
        <small class="text-muted">รองรับ jpeg, png, jpg ขนาดไม่เกิน 5MB</small>
        @error('merchandise_image') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Buttons --}}
    <div class="row">
      <label class="col-sm-2"></label>
      <div class="col-sm-7">
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <a href="{{ url('/merchandise') }}" class="btn btn-secondary">ยกเลิก</a>
      </div>
    </div>
  </form>
@endsection
