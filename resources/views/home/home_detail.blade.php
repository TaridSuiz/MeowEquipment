@extends('frontend')
@section('css_before')
@section('navbar')
@endsection
@section('showProduct')

<div class="col-12 col-sm-3 col-md-3 mb-2">
    <div class="card" style="width: 100%;">
        <img src="{{ asset('storage/' . $merchandise_image) }}" class="card-img-top" alt="devbanban.com">
    </div>
</div>
<div class="col-12 col-sm-8 col-md-8 mb-2">
    <h5 class="card-title">{{ $merchandise_name }}, Price {{ number_format($price) }} THB. </h5>
    <p>
        product detail
        <br>
        <br>
        วันที่เผยแพร่ {{date('d/m/Y', strtotime($created_at))  }}
    </p>
</div>

@endsection

@section('footer')
@endsection

@section('js_before')
@endsection

{{-- devbanban.com --}}