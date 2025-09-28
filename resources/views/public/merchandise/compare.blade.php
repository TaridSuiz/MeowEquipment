@extends('backend')

@section('content')
    <h3 class="mb-3">ผลการเปรียบเทียบสินค้า</h3>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 220px;">คุณสมบัติ</th>
                    @foreach($items as $item)
                        <th class="text-center" style="width: 380px;">
                            {{ $item->merchandise_name }}
                            <div class="mt-2">
                                @php
                                    $img = $item->merchandise_image
                                        ? asset('storage/'.$item->merchandise_image)
                                        : 'https://via.placeholder.com/600x400?text=No+Image';
                                @endphp
                                <img src="{{ $img }}" alt="" class="img-fluid rounded" style="max-height: 180px; object-fit: cover;">
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>ราคา</th>
                    @foreach($items as $item)
                        <td class="text-center">฿{{ number_format($item->price, 2) }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>คะแนนเฉลี่ย</th>
                    @foreach($items as $item)
                        <td class="text-center">{{ number_format($item->rating_avg ?? 0, 2) }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>ช่วงวัยเหมาะสม</th>
                    @foreach($items as $item)
                        <td class="text-center">{{ $item->age_range ?? '-' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>แบรนด์</th>
                    @foreach($items as $item)
                        <td class="text-center">{{ $item->brand ?? '-' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <th>ลิงก์ร้านค้า</th>
                    @foreach($items as $item)
                        <td class="text-center">
                            @if(!empty($item->link_store))
                                <a href="{{ $item->link_store }}" target="_blank" rel="noopener">เปิดร้านค้า</a>
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <th>รายละเอียดโดยย่อ</th>
                    @foreach($items as $item)
                        <td>{{ \Illuminate\Support\Str::limit($item->description ?? '-', 180, '...') }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    <a href="{{ route('public.catalog') }}" class="btn btn-outline-secondary"><< กลับไปเลือกสินค้า</a>
@endsection
