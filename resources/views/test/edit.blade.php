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
        <div class="col-sm-12">

            <h3> :: form Update  :: </h3>


<form action="/test/{{ $id }}" method="post">
@csrf
@method('put')

<div class="form-group row mb-2">
    <label class="col-sm-2"> ชื่อ </label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="name" required placeholder=" ชื่อ" minlength="3" value="{{ $name }}">
        @if(isset($errors))
            @if($errors->has('name'))
                <div class="text-danger"> {{ $errors->first('name') }}</div>
            @endif 
        @endif
    </div>
</div>

<div class="form-group row mb-2">
    <label class="col-sm-2"> นามสกุล </label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="lastname" required placeholder="นามสกุล" minlength="3"  value="{{ $lastname }}">
        @if(isset($errors))
            @if($errors->has('lastname'))
                <div class="text-danger"> {{ $errors->first('lastname') }}</div>
            @endif 
        @endif
    </div>
</div>

<div class="form-group row mb-2">
    <label class="col-sm-2"> อีเมล </label>
    <div class="col-sm-6">
        <input type="email" class="form-control" name="email" required placeholder="อีเมล" minlength="3"  value="{{ $email }}">
        @if(isset($errors))
            @if($errors->has('email'))
                <div class="text-danger"> {{ $errors->first('email') }}</div>
            @endif 
        @endif
    </div>
</div>


<div class="form-group row mb-2">
    <label class="col-sm-2">  </label>
    <div class="col-sm-5">
       <button type="submit" class="btn btn-primary"> Update  </button>
       <a href="/test" class="btn btn-danger">cancel</a>
    </div>
</div>

</form>
</div> <!--  / <div class="col-sm-9 col-md-9"> -->


    @endsection

@section('footer')
@endsection

@section('js_before')
@endsection

@section('js_before')
@endsection