@extends('home')

@section('content')
@php $a = $products[0]; $b = $products[1]; @endphp
<h4 class="mb-3">เปรียบเทียบสินค้า</h4>

<table class="table table-bordered">
  <tr>
    <th width="25%">คุณสมบัติ</th>
    <th>{{ $a->merchandise_name }}</th>
    <th>{{ $b->merchandise_name }}</th>
  </tr>
  <tr><td>หมวด</td><td>{{ $a->category->category_name ?? '-' }}</td><td>{{ $b->category->category_name ?? '-' }}</td></tr>
  <tr><td>คะแนนเฉลี่ย</td><td>{{ number_format($a->rating_avg,1) }}</td><td>{{ number_format($b->rating_avg,1) }}</td></tr>
  <tr><td>ช่วงวัย</td><td>{{ $a->age_range }}</td><td>{{ $b->age_range }}</td></tr>
  <tr><td>แบรนด์</td><td>{{ $a->brand }}</td><td>{{ $b->brand }}</td></tr>
  <tr>
    <td>ลิงก์ร้านค้า</td>
    <td>@if($a->link_store)<a href="{{ $a->link_store }}" target="_blank">เปิด</a>@endif</td>
    <td>@if($b->link_store)<a href="{{ $b->link_store }}" target="_blank">เปิด</a>@endif</td>
  </tr>
</table>
@endsection
