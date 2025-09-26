@extends('home')

@section('content')
    <h3> :: Form Add User :: </h3>

    <form action="/user" method="post" enctype="multipart/form-data">
        @csrf

        {{-- Email --}}
        <div class="form-group row mb-2">
            <label class="col-sm-2">Email</label>
            <div class="col-sm-7">
                <input type="email" class="form-control" name="email" required
                       placeholder="Email Address" value="{{ old('email') }}">
                @if($errors->has('email'))
                    <div class="text-danger">{{ $errors->first('email') }}</div>
                @endif
            </div>
        </div>

        {{-- Name --}}
        <div class="form-group row mb-2">
            <label class="col-sm-2">User Name</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" name="name" required
                       placeholder="Full Name" minlength="3" value="{{ old('name') }}">
                @if($errors->has('name'))
                    <div class="text-danger">{{ $errors->first('name') }}</div>
                @endif
            </div>
        </div>

        {{-- Password --}}
        <div class="form-group row mb-2">
            <label class="col-sm-2">Password</label>
            <div class="col-sm-7">
                <input type="password" class="form-control" name="password" required minlength="6"
                       placeholder="Password">
                @if($errors->has('password'))
                    <div class="text-danger">{{ $errors->first('password') }}</div>
                @endif
            </div>
        </div>

        {{-- Profile Image --}}
        <div class="form-group row mb-2">
            <label class="col-sm-2">Profile Image</label>
            <div class="col-sm-6">
                <input type="file" name="profile_img" accept="image/*">
                @if($errors->has('profile_img'))
                    <div class="text-danger">{{ $errors->first('profile_img') }}</div>
                @endif
            </div>
        </div>

        {{-- Hidden role = user --}}
        <input type="hidden" name="role" value="user">

        {{-- Buttons --}}
        <div class="form-group row mb-2">
            <label class="col-sm-2"></label>
            <div class="col-sm-5">
                <button type="submit" class="btn btn-primary">Save User</button>
                <a href="/user" class="btn btn-danger">Cancel</a>
            </div>
        </div>
    </form>
@endsection
