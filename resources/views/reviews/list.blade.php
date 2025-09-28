@extends('home')

@section('content')
  <h3 class="mb-3">
    :: Review Management ::
    <a href="{{ url('/reviews/adding') }}" class="btn btn-primary btn-sm">Add Review</a>
  </h3>

  {{-- Filters --}}
  <form method="GET" action="{{ url('/reviews') }}" class="row g-2 mb-3">
    <div class="col-md-3">
      <input type="text" name="s" class="form-control" placeholder="ค้นหาความคิดเห็น"
             value="{{ request('s') }}">
    </div>
    <div class="col-md-3">
      <select name="user_id" class="form-select">
        <option value="">-- ผู้ใช้ทั้งหมด --</option>
        @foreach($users as $u)
          <option value="{{ $u->user_id }}" @selected(request('user_id')==$u->user_id)>{{ $u->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <select name="merchandise_id" class="form-select">
        <option value="">-- สินค้าทั้งหมด --</option>
        @foreach($merch as $m)
          <option value="{{ $m->merchandise_id }}" @selected(request('merchandise_id')==$m->merchandise_id)>
            {{ $m->merchandise_name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <input type="number" step="1" min="0" max="5" name="min_rating" class="form-control" placeholder="เรตติ้ง ≥"
             value="{{ request('min_rating') }}">
    </div>
    <div class="col-md-1 d-grid">
      <button class="btn btn-outline-secondary">ค้นหา</button>
    </div>
  </form>

  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr class="table-info">
        <th class="text-center" width="6%">No.</th>
        <th width="18%">User</th>
        <th width="24%">Merchandise</th>
        <th class="text-center" width="8%">Rating</th>
        <th>Comment</th>
        <th class="text-center" width="10%">Created</th>
        <th class="text-center" width="8%">Edit</th>
        <th class="text-center" width="8%">Delete</th>
      </tr>
    </thead>
    <tbody>
      @forelse($reviews as $row)
        <tr>
          <td class="text-center">
            {{ ($reviews->currentPage()-1)*$reviews->perPage() + $loop->iteration }}
          </td>
          <td>{{ optional($row->user)->name ?? '-' }}</td>
          <td>{{ optional($row->merchandise)->merchandise_name ?? '-' }}</td>
          <td class="text-center">{{ (int)$row->rating }}</td>
          <td>{{ \Illuminate\Support\Str::limit($row->comment, 120, '...') }}</td>
          <td class="text-center">{{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i') }}</td>
          <td class="text-center">
            <a href="{{ url('/reviews/'.$row->review_id) }}" class="btn btn-warning btn-sm">Edit</a>
          </td>
          <td class="text-center">
            <form action="{{ url('/reviews/remove/'.$row->review_id) }}" method="POST" class="form-delete d-inline">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8" class="text-center text-muted">ไม่พบข้อมูล</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{ $reviews->links() }}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form.form-delete').forEach(f => {
    f.addEventListener('submit', e => {
      e.preventDefault();
      Swal.fire({
        title: 'ลบรีวิว?',
        text: 'คุณต้องการลบรีวิวนี้หรือไม่',
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
