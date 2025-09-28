@extends('home')

@section('content')
  <h3 class="mb-3">:: Edit Review ::</h3>

  <form action="{{ url('/reviews/'.$review->review_id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">ผู้ใช้ *</label>
      <div class="col-sm-6">
        <select name="user_id" class="form-select" required>
          @foreach($users as $u)
            <option value="{{ $u->user_id }}" @selected(old('user_id', $review->user_id)==$u->user_id)>
              {{ $u->name }}
            </option>
          @endforeach
        </select>
        @error('user_id') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">สินค้า *</label>
      <div class="col-sm-6">
        <select name="merchandise_id" class="form-select" required>
          @foreach($merch as $m)
            <option value="{{ $m->merchandise_id }}" @selected(old('merchandise_id', $review->merchandise_id)==$m->merchandise_id)>
              {{ $m->merchandise_name }}
            </option>
          @endforeach
        </select>
        @error('merchandise_id') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">เรตติ้ง (0-5) *</label>
      <div class="col-sm-2">
        <input type="number" name="rating" class="form-control" required step="1" min="0" max="5"
               value="{{ old('rating', $review->rating) }}">
        @error('rating') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="mb-4 row">
      <label class="col-sm-2 col-form-label">ความคิดเห็น</label>
      <div class="col-sm-6">
        <textarea name="comment" rows="4" class="form-control">{{ old('comment', $review->comment) }}</textarea>
        @error('comment') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="row">
      <label class="col-sm-2"></label>
      <div class="col-sm-6">
        <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
        <a href="{{ url('/reviews') }}" class="btn btn-secondary">ยกเลิก</a>
      </div>
    </div>
  </form>
@endsection
