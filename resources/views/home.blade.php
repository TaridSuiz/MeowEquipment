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
  <form action="{{ route('logout') }}" method="POST" class="d-inline">
    @csrf
    <button class="btn btn-outline-secondary btn-sm">
      Logout ({{ auth()->user()->name }})
    </button>
  </form>
@else
  <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
@endauth
