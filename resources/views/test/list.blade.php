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
    <div class="col-md-10">
<h3> Test Data  
<a  href="/test/adding" class="btn btn-primary btn-sm mb-2"> + Data </a>
</h3>


<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr class="table-info">
            <th width="5%" class="text-center">ลำดับ</th>
            <th width="45%">ชื่อ - สกุล</th>
            <th width="40%">อีเมล</th>

            <th width="5%">แก้ไข</th>
            <th width="5%">ลบ</th>
        </tr>
    </thead>

    <tbody>
        @foreach($testList as $row)
        <tr>
            <td align="center"> {{ $loop->iteration }}.  <!--เรียงลำดับใหม่  --></td>
            <td>{{ $row->name }} {{ $row->lastname }} </td>
            <td>{{ $row->email }}</td>
            <td>
                    <a href="/test/{{ $row->id }}" class="btn btn-warning btn-sm">edit</a>
            </td>
            <td>
                
                 <button type="button" class="btn btn-danger btn-sm" onclick="deleteConfirm({{ $row->id }})">delete</button>

                        <form id="delete-form-{{ $row->id }}" action="/test/remove/{{$row->id}}" method="POST" style="display: none;">
                            @csrf
                            @method('delete')
                        </form>
            </td>
        </tr>
        @endforeach
    </tbody>

</table>

<p> Add column phone, email, age </p>

<div>
        {{ $testList->links() }}
    </div>
    
</div>
</div>
</div>
{{-- devbanban.com  --}}

@endsection

@section('footer')
@endsection

@section('js_before')
@endsection




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function deleteConfirm(id) {
    Swal.fire({
        title: 'ยืนยันการลบข้อมูล?',
        text: "หากลบแล้วจะไม่สามารถกู้คืนได้!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>



