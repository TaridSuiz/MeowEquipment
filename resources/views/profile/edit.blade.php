@extends('home')

@section('content')
<h3 class="mb-3">โปรไฟล์ของฉัน</h3>

<div class="row">
  <div class="col-md-6">
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mb-4">
      @csrf @method('PUT')

      <div class="mb-3">
        <label class="form-label">ชื่อ</label>
        <input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}" required>
        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">อีเมล</label>
        <input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}" required>
        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">รูปโปรไฟล์</label>
        @if($user->profile_img)
          <div class="mb-2">
            <img src="{{ asset('storage/'.$user->profile_img) }}" width="120" style="object-fit:cover;border-radius:8px;">
          </div>
        @endif
        <input type="file" name="profile_img" class="form-control" accept="image/*">
        @error('profile_img') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <button class="btn btn-primary">บันทึกโปรไฟล์</button>
    </form>
  </div>

  <div class="col-md-6">
    <form method="POST" action="{{ route('profile.password') }}">
      @csrf @method('PUT')
      <h5 class="mb-3">เปลี่ยนรหัสผ่าน</h5>

      <div class="mb-3">
        <label class="form-label">รหัสผ่านใหม่</label>
        <input type="password" name="password" class="form-control" required>
        @error('password') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
        <input type="password" name="password_confirmation" class="form-control" required>
      </div>

      <button class="btn btn-outline-primary">เปลี่ยนรหัสผ่าน</button>
    </form>
  </div>
</div>
@endsection
