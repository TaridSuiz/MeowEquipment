@extends('home')

@section('css_before')
@endsection

@section('header')
@endsection

@section('sidebarMenu')
@endsection

@section('content')


<div class="container mt-4">
    <div class="row">
        <div class="col-sm-9">

            <h3> :: form Add Admin :: </h3>


            <form action="/admin/" method="post">
                @csrf

                
                <div class="form-group row mb-2">
                    <label class="col-sm-2"> Username </label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" name="admin_username" required placeholder="email/username"
                            minlength="3"  value="{{ old('admin_username') }}">
                        @if(isset($errors))
                        @if($errors->has('admin_username'))
                        <div class="text-danger"> {{ $errors->first('admin_username') }}</div>
                        @endif
                        @endif
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <label class="col-sm-2"> Password </label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" name="admin_password" required placeholder="Password"
                            minlength="3">
                        @if(isset($errors))
                        @if($errors->has('admin_password'))
                        <div class="text-danger"> {{ $errors->first('admin_password') }}</div>
                        @endif
                        @endif
                    </div>
                </div>

                <div class="form-group row mb-2">
                    <label class="col-sm-2">Name </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="admin_name" required placeholder="admin name"
                            minlength="3" value="{{ old('admin_name') }}">
                        @if(isset($errors))
                        @if($errors->has('admin_name'))
                        <div class="text-danger"> {{ $errors->first('admin_name') }}</div>
                        @endif
                        @endif
                    </div>
                </div>

               <!--  <div class="form-group row mb-2">
                    <label class="col-sm-2"> Phone </label>
                    <div class="col-sm-6">
                        <input type="tel" class="form-control" name="phone" required placeholder="Phone 10 digit"
                            minlength="3" maxlength="10" value="{{ old('phone') }}">
                        @if(isset($errors))
                        @if($errors->has('phone'))
                        <div class="text-danger"> {{ $errors->first('phone') }}</div>
                        @endif
                        @endif
                    </div>
                </div>
 -->

                <div class="form-group row mb-2">
                    <label class="col-sm-2"> </label>
                    <div class="col-sm-5">

                        <button type="submit" class="btn btn-primary"> Save </button>
                        <a href="/admin" class="btn btn-danger">cancel</a>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection

@section('footer')
@endsection

@section('js_before')
@endsection

@section('js_before')
@endsection