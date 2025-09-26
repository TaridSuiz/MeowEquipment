@extends('home')
@section('js_before')
@include('sweetalert::alert')
@section('header')
@section('sidebarMenu')   
@section('content')

    <h3> :: form Update Student :: </h3>

    <form action="/student/{{ $id }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')

        <div class="form-group row mb-2">
            <label class="col-sm-2"> Student Name </label>
            <div class="col-sm-7">
                <input type="text" class="form-control" name="std_name" required placeholder="Student Name "
                    minlength="3" value="{{ $std_name }}">
                @if(isset($errors))
                @if($errors->has('std_name'))
                <div class="text-danger"> {{ $errors->first('std_name') }}</div>
                @endif
                @endif
            </div>
        </div>


        <div class="form-group row mb-2">
            <label class="col-sm-2"> Student Phone </label>
            <div class="col-sm-7">
                <input type="text" class="form-control" name="std_phone" required placeholder="Student Phone "
                    minlength="3" value="{{ old('std_phone') }}">
                @if(isset($errors))
                @if($errors->has('std_phone'))
                <div class="text-danger"> {{ $errors->first('std_phone') }}</div>
                @endif
                @endif
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-2"> Student code </label>
            <div class="col-sm-7">
               <input type="text" class="form-control" name="std_code" required placeholder="Student Code "
                    minlength="3" value="{{ $std_code }}">
                @if(isset($errors))
                @if($errors->has('std_code'))
                <div class="text-danger"> {{ $errors->first('std_code') }}</div>
                @endif
                @endif
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-2"> Pic </label>
            <div class="col-sm-6">
                <input type="file" name="std_img" required placeholder="std_img" accept="image/*">
                @if(isset($errors))
                @if($errors->has('std_img'))
                <div class="text-danger"> {{ $errors->first('std_img') }}</div>
                @endif
                @endif
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-2"> </label>
            <div class="col-sm-5">
                <input type="hidden" name="oldImg" value="{{ $std_img }}">
                <button type="submit" class="btn btn-primary"> Update </button>
                <a href="/student" class="btn btn-danger">cancel</a>
            </div>
        </div>

    </form>
</div>


@endsection


@section('footer')
@endsection

@section('js_before')
@endsection

{{-- devbanban.com --}}