@extends('home')

@section('content')
  <h3>:: Reset Password ::</h3>

  <form action="{{ route('user.reset.update', $user->user_id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
          <label class="form-label">รหัสผ่านใหม่ *</label>
          <input type="password" name="password" class="form-control" required minlength="8">
          @error('password') <div class="text-danger">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
          <label class="form-label">ยืนยันรหัสผ่าน *</label>
          <input type="password" name="password_confirmation" class="form-control" required minlength="8">
      </div>

      <button type="submit" class="btn btn-primary">รีเซ็ต</button>
      <a href="{{ url('/user') }}" class="btn btn-secondary">ยกเลิก</a>
  </form>
@endsection
