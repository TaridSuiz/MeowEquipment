@extends('home')

@section('content')
  <h3>:: Reset Password ::</h3>

  <div class="mb-3">
    <strong>User:</strong> {{ $user->name }} ({{ $user->email }})
  </div>

  <form action="{{ route('user.reset.update', $user->user_id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- New Password --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">New Password *</label>
      <div class="col-sm-7">
        <input type="password" name="password" class="form-control" required minlength="8"
               placeholder="อย่างน้อย 8 ตัว, มี a-z, A-Z, 0-9">
        @error('password') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
    </div>

    {{-- Confirm --}}
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Confirm *</label>
      <div class="col-sm-7">
        <input type="password" name="password_confirmation" class="form-control" required minlength="8">
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2"></label>
      <div class="col-sm-7">
        <button type="submit" class="btn btn-primary">Reset</button>
        <a href="{{ route('user.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </div>
  </form>
@endsection
