@extends('layouts.backend')

@section('css_before')
@endsection

@section('header')
@endsection
 
@section('sidebarMenu')    
@endsection

@section('content')
@endsection

@section('footer')
@endsection

@section('js_before')
@endsection


{{-- ตัวอย่างใน navbar/header --}}
@auth
  </form>
@else
  <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
@endauth
