@extends('home')

@section('css_before')
@endsection

@section('header')
@endsection

@section('sidebarMenu')
@endsection

@section('content')
    <h3> ::Product Managements ::
        <a href="/product/adding" class="btn btn-primary btn-sm"> Add Product </a>
    </h3>

    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr class="table-info">
                <th width="5%" class="text-center">No.</th>
                <th width="5%">Pic</th>
                <th width="65%">Product Name & Detail </th>
                <th width="15%" class="text-center">Price</th>
                <th width="5%" class="text-center">edit</th>
                <th width="5%" class="text-center">delete</th>
            </tr>
        </thead>

        <tbody>
            @foreach($products as $row)
            <tr>
                <td align="center">{{ $row->id }}</td>
                <td>
                    
                    <img src="{{ asset('storage/' . $row->product_img) }}" width="100"></td>
                <td>
                    <b>Name: {{ $row->product_name }}</b> <br>
                    Detail: 
                    {{ Str::limit($row->product_detail, 120, '...') }}
                </td>
                <td align="right">฿{{ number_format($row->product_price, 2) }}</td>
                <td align="center">
                    <a href="/product/{{ $row->id }}" class="btn btn-warning btn-sm">edit</a>
                </td>
                <td align="center">

                    {{-- <form action="/product/remove/{{$row->id}}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sure to Delete !!');">delete</button>
                    </form> --}}

                    
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteConfirm({{ $row->id }})">delete</button>

                        <form id="delete-form-{{ $row->id }}" action="/product/remove/{{$row->id}}" method="POST" style="display: none;">
                            @csrf
                            @method('delete')
                        </form>


                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        {{ $products->links() }}
    </div>
@endsection

@section('footer')
@endsection

@section('js_before')
@endsection

@section('js_before')
@endsection




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function deleteConfirm(id) {
    Swal.fire({
        title: 'แน่ใจหรือไม่?',
        text: "คุณต้องการลบข้อมูลนี้จริง ๆ หรือไม่",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ลบเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // ถ้ากด "ลบเลย" ให้ submit form ที่ซ่อนไว้
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>

