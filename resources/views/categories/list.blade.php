@extends('home')

@section('content')
    <h3>:: Category Management ::
        <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm">Add Category</a>
    </h3>

    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr class="table-info">
                <th width="6%"  class="text-center">No.</th>
                <th>ชื่อหมวดหมู่</th>
                <th>รายละเอียด</th>
                <th width="18%">วันที่เพิ่ม</th>
                <th width="6%"  class="text-center">แก้ไข</th>
                <th width="6%"  class="text-center">ลบ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $row)
                <tr>
                    {{-- running number ตาม paginate --}}
                    <td class="text-center">
                        {{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
                    </td>

                    <td>{{ $row->category_name }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($row->description, 120, '...') }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i') }}</td>

                    <td class="text-center">
                        <a href="{{ route('category.edit', $row->category_id) }}" class="btn btn-warning btn-sm">แก้ไข</a>
                    </td>
                    <td class="text-center">
                        <form action="{{ route('category.destroy', $row->category_id) }}" method="POST" class="form-delete d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        {{ $categories->links() }}
    </div>
@endsection

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('form.form-delete').forEach(function(form){
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      Swal.fire({
        title: 'แน่ใจหรือไม่?',
        text: 'คุณต้องการลบข้อมูลนี้จริง ๆ หรือไม่',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ลบเลย!',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) form.submit();
      });
    });
  });
});
</script>
