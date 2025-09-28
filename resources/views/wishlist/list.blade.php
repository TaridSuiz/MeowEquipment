@extends('home')

@section('content')
  <h3 class="mb-3">
    :: Wishlist Management ::
    <a href="{{ url('/wishlist/adding') }}" class="btn btn-primary btn-sm">Add Wishlist</a>
  </h3>

  {{-- Filters --}}
  <form method="GET" class="row g-2 mb-3" action="{{ url('/wishlist') }}">
    <div class="col-md-4">
      <select name="user_id" class="form-select">
        <option value="">-- ผู้ใช้ทั้งหมด --</option>
        @foreach($users as $u)
          <option value="{{ $u->user_id }}" @selected(request('user_id')==$u->user_id)>{{ $u->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-5">
      <select name="merchandise_id" class="form-select">
        <option value="">-- สินค้าทั้งหมด --</option>
        @foreach($merch as $m)
          <option value="{{ $m->merchandise_id }}" @selected(request('merchandise_id')==$m->merchandise_id)>
            {{ $m->merchandise_name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3 d-grid">
      <button class="btn btn-outline-secondary">ค้นหา</button>
    </div>
  </form>

  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr class="table-info">
        <th class="text-center" width="6%">No.</th>
        <th width="28%">User</th>
        <th>Merchandise</th>
        <th width="16%" class="text-center">Created</th>
        <th width="8%"  class="text-center">Delete</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $row)
        <tr>
          <td class="text-center">
            {{ ($items->currentPage()-1)*$items->perPage() + $loop->iteration }}
          </td>
          <td>{{ optional($row->user)->name ?? '-' }}</td>
          <td>{{ optional($row->merchandise)->merchandise_name ?? '-' }}</td>
          <td class="text-center">{{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i') }}</td>
          <td class="text-center">
            <form action="{{ url('/wishlist/remove/'.$row->wishlist_id) }}" method="POST" class="form-delete d-inline">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center text-muted">ไม่พบรายการ</td></tr>
      @endforelse
    </tbody>
  </table>

  {{ $items->links() }}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form.form-delete').forEach(f => {
    f.addEventListener('submit', e => {
      e.preventDefault();
      Swal.fire({
        title: 'ลบจาก Wishlist?',
        text: 'ต้องการลบรายการนี้หรือไม่',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ยกเลิก'
      }).then(res => { if (res.isConfirmed) f.submit(); });
    });
  });
});
</script>
@endpush
