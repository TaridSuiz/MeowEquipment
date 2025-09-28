@extends('home')

@section('content')
  <h3 class="mb-3">:: Add to Wishlist ::</h3>

  <form action="{{ url('/wishlist') }}" method="POST">
    @csrf

    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">ผู้ใช้ *</label>
      <div class="col-sm-6">
        <select name="user_id" class="form-select" required>
          <option value="">-- เลือกผู้ใช้ --</option>
          @foreach($users as $u)
            <option value="{{ $u->user_id }}" @selected(old('user_id')==$u->user_id)>{{ $u->name }}</option>
          @endforeach
        </select>
        @error('user_id') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="mb-4 row">
      <label class="col-sm-2 col-form-label">สินค้า *</label>
      <div class="col-sm-8">
        <select name="merchandise_id" class="form-select" required>
          <option value="">-- เลือกสินค้า --</option>
          @foreach($merch as $m)
            <option value="{{ $m->merchandise_id }}" @selected(old('merchandise_id')==$m->merchandise_id)>
              {{ $m->merchandise_name }}
            </option>
          @endforeach
        </select>
        @error('merchandise_id') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="row">
      <label class="col-sm-2"></label>
      <div class="col-sm-6">
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <a href="{{ url('/wishlist') }}" class="btn btn-secondary">ยกเลิก</a>
      </div>
    </div>
  </form>
@endsection
