@extends('home')
@section('js_before')
@include('sweetalert::alert')
@section('header')
@section('sidebarMenu')   
@section('content')

    <h3> :: Edit User :: </h3>

    <form action="/user/{{ $user->user_id }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')

        {{-- Name --}}
        <div class="form-group row mb-2">
            <label class="col-sm-2">Name</label>
            <div class="col-sm-7">
                <input type="text"
                       class="form-control"
                       name="name"
                       required
                       minlength="3"
                       placeholder="Full Name"
                       value="{{ old('name', $user->name) }}">
                @if($errors->has('name'))
                    <div class="text-danger">{{ $errors->first('name') }}</div>
                @endif
            </div>
        </div>

        {{-- Email --}}
        <div class="form-group row mb-2">
            <label class="col-sm-2">Email</label>
            <div class="col-sm-7">
                <input type="email"
                       class="form-control"
                       name="email"
                       required
                       placeholder="Email Address"
                       value="{{ old('email', $user->email) }}">
                @if($errors->has('email'))
                    <div class="text-danger">{{ $errors->first('email') }}</div>
                @endif
            </div>
        </div>

        {{-- Role (editable only by admin) --}}
        @php
            $isAdmin = auth()->check() && auth()->user()->role === 'admin';
        @endphp
        <div class="form-group row mb-2">
            <label class="col-sm-2">Role</label>
            <div class="col-sm-7">
                @if($isAdmin)
                    <select name="role" class="form-control" required>
                        <option value="user"  {{ old('role', $user->role) === 'user'  ? 'selected' : '' }}>user</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>admin</option>
                    </select>
                @else
                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                    {{-- disabled ไม่ถูก submit จึงต้อง preserve ค่าเดิมด้วย hidden --}}
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <small class="text-muted">เฉพาะผู้ดูแลระบบ (admin) เท่านั้นที่สามารถแก้ไขสิทธิ์ได้</small>
                @endif
                @if($errors->has('role'))
                    <div class="text-danger">{{ $errors->first('role') }}</div>
                @endif
            </div>
        </div>

        {{-- Current Image --}}
        <div class="form-group row mb-2">
            <label class="col-sm-2">Current Image</label>
            <div class="col-sm-7">
                @if($user->profile_img)
                    <img src="{{ asset('storage/' . $user->profile_img) }}" alt="Profile" width="100" class="rounded mb-2">
                @else
                    <span class="text-muted">No Image</span>
                @endif
            </div>
        </div>

        {{-- Upload New Image (optional) --}}
        <div class="form-group row mb-2">
            <label class="col-sm-2">New Image</label>
            <div class="col-sm-6">
                <input type="file" name="profile_img" accept="image/*">
                @if($errors->has('profile_img'))
                    <div class="text-danger">{{ $errors->first('profile_img') }}</div>
                @endif
                <small class="text-muted d-block">รองรับ jpeg, png, jpg ขนาดไม่เกิน 5MB</small>
            </div>
        </div>

        {{-- Actions --}}
        <div class="form-group row mb-2">
            <label class="col-sm-2"></label>
            <div class="col-sm-5">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="/user" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
@endsection

@section('footer')
@endsection
