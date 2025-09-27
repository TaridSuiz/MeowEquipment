@extends('home')

@section('content')
  <h3>:: Edit User ::</h3>

  <form action="{{ route('user.update', $user->user_id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Name --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Name *</label>
      <div class="col-sm-7">
        <input type="text" name="name" class="form-control" required minlength="3"
               value="{{ old('name', $user->name) }}">
        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Email --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Email *</label>
      <div class="col-sm-7">
        <input type="email" name="email" class="form-control" required
               value="{{ old('email', $user->email) }}">
        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Role (เฉพาะ admin ที่ล็อกอินอยู่เท่านั้น) --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Role</label>
      <div class="col-sm-4">
        @if(auth()->check() && auth()->user()->role === 'admin')
          <select name="role" class="form-select">
            <option value="user"  @selected(old('role', $user->role)==='user')>user</option>
            <option value="admin" @selected(old('role', $user->role)==='admin')>admin</option>
          </select>
        @else
          <input type="text" class="form-control" value="{{ $user->role }}" readonly>
        @endif
        @error('role') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Avatar --}}
    <div class="mb-4 row">
      <label class="col-sm-2 col-form-label">Avatar</label>
      <div class="col-sm-7">
        @if($user->profile_img)
          <div class="mb-2">
            <img src="{{ asset('storage/'.$user->profile_img) }}" alt="avatar" width="100" height="100" style="object-fit:cover;border-radius:10px;">
          </div>
        @endif
        <input type="file" name="profile_img" class="form-control" accept="image/*">
        <small class="text-muted">อัปโหลดใหม่เพื่อแทนที่ (ไม่บังคับ)</small>
        @error('profile_img') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2"></label>
      <div class="col-sm-7">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('user.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </div>
  </form>
@endsection
