@extends('home')

@section('content')
  <h3>:: Create User ::</h3>

  <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Name --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Name *</label>
      <div class="col-sm-7">
        <input type="text" name="name" class="form-control" required minlength="3"
               value="{{ old('name') }}">
        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Email --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Email *</label>
      <div class="col-sm-7">
        <input type="email" name="email" class="form-control" required
               value="{{ old('email') }}">
        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Password --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Password *</label>
      <div class="col-sm-7">
        <input type="password" name="password" class="form-control" required minlength="8"
               placeholder="อย่างน้อย 8 ตัว, มี a-z, A-Z, 0-9">
        @error('password') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Role (optional, default user) --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Role</label>
      <div class="col-sm-4">
        <select name="role" class="form-select">
          <option value="" {{ old('role')==='' ? 'selected' : '' }}>— default: user —</option>
          <option value="user"  @selected(old('role')==='user')>user</option>
          <option value="admin" @selected(old('role')==='admin')>admin</option>
        </select>
        @error('role') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Avatar --}}
    <div class="mb-4 row">
      <label class="col-sm-2 col-form-label">Avatar</label>
      <div class="col-sm-7">
        <input type="file" name="profile_img" class="form-control" accept="image/*">
        <small class="text-muted">jpeg/png/jpg ไม่เกิน 5MB</small>
        @error('profile_img') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2"></label>
      <div class="col-sm-7">
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="{{ route('user.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </div>
  </form>
@endsection
