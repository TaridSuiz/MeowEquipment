@extends('home')

@section('content')
  <h3 class="mb-3">
    :: Category Management ::
    <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm">Add Category</a>
  </h3>

  {{-- Search --}}
  <form method="GET" action="{{ route('category.index') }}" class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" name="s" class="form-control" placeholder="ค้นหาชื่อ/รายละเอียดหมวดหมู่"
             value="{{ request('s') }}">
    </div>
    <div class="col-md-2">
      <button class="btn btn-outline-secondary" type="submit">ค้นหา</button>
      @if(request()->has('s') && request('s')!=='')
        <a href="{{ route('category.index') }}" class="btn btn-link">ล้าง</a>
      @endif
    </div>
  </form>

  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr class="table-info">
        <th class="text-center" width="7%">No.</th>
        <th>ชื่อหมวดหมู่</th>
        <th>รายละเอียด</th>
        <th class="text-center" width="18%">วันที่เพิ่ม</th>
        <th class="text-center" width="8%">แก้ไข</th>
        <th class="text-center" width="8%">ลบ</th>
      </tr>
    </thead>
    <tbody>
      @forelse($categories as $row)
        <tr>
          <td class="text-center">
            {{ ($categories->currentPage()-1)*$categories->perPage() + $loop->iteration }}
          </td>
          <td>{{ $row->category_name }}</td>
          <td>{{ \Illuminate\Support\Str::limit($row->description, 120, '...') }}</td>
          <td class="text-center">{{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i') }}</td>
          <td class="text-center">
            <a href="{{ route('category.edit', $row->category_id) }}" class="btn btn-warning btn-sm">แก้ไข</a>
          </td>
          <td class="text-center">
            <form action="{{ route('category.destroy', $row->category_id) }}" method="POST" class="form-delete d-inline">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="text-center text-muted">ไม่พบข้อมูล</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{ $categories->links() }}
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('form.form-delete').forEach(form => {
        form.addEventListener('submit', e => {
          e.preventDefault();
          Swal.fire({
            title: 'ยืนยันการลบ?',
            text: 'คุณต้องการลบหมวดหมู่นี้หรือไม่',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก'
          }).then(res => { if (res.isConfirmed) form.submit(); });
        });
      });
    });
  </script>
@endpush
