@extends('home')

@section('content')
  <h3 class="mb-3">
    :: Merchandise Management ::
    <a href="{{ url('/merchandise/adding') }}" class="btn btn-primary btn-sm">Add Merchandise</a>
  </h3>

  {{-- ค้นหาแบบง่าย (ถ้าจะทำต่อสามารถเพิ่มใน Controller) --}}
  {{-- <form method="GET" action="{{ url('/merchandise') }}" class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" name="s" class="form-control" value="{{ request('s') }}" placeholder="ค้นหาชื่อ/แบรนด์">
    </div>
    <div class="col-md-2">
      <button class="btn btn-outline-secondary">ค้นหา</button>
    </div>
  </form> --}}

  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr class="table-info">
        <th class="text-center" width="6%">No.</th>
        <th width="10%">Pic</th>
        <th width="26%">Name / Brand</th>
        <th width="18%">Category</th>
        <th class="text-end" width="10%">Price</th>
        <th class="text-center" width="8%">Rating</th>
        <th class="text-center" width="10%">Age Range</th>
        <th class="text-center" width="8%">Edit</th>
        <th class="text-center" width="8%">Delete</th>
      </tr>
    </thead>
    <tbody>
      @forelse($merchandise as $row)
        <tr>
          <td class="text-center">
            {{ ($merchandise->currentPage()-1)*$merchandise->perPage() + $loop->iteration }}
          </td>
          <td>
            @if($row->merchandise_image)
              <img src="{{ asset('storage/'.$row->merchandise_image) }}" width="70" height="70" style="object-fit:cover;border-radius:8px;">
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td>
            <strong>{{ $row->merchandise_name }}</strong>
            <div class="text-muted small">{{ $row->brand }}</div>
          </td>
          <td>{{ optional($row->category)->category_name ?? '-' }}</td>
          <td class="text-end">
            @if(!is_null($row->price))
              ฿{{ number_format((float)$row->price, 2) }}
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td class="text-center">{{ $row->rating_avg ?? '-' }}</td>
          <td class="text-center">{{ $row->age_range ?? '-' }}</td>
          <td class="text-center">
            <a href="{{ url('/merchandise/'.$row->merchandise_id) }}" class="btn btn-warning btn-sm">Edit</a>
          </td>
          <td class="text-center">
            <form action="{{ url('/merchandise/remove/'.$row->merchandise_id) }}" method="POST" class="form-delete d-inline">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="9" class="text-center text-muted">No merchandise found</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{ $merchandise->links() }}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form.form-delete').forEach(f => {
    f.addEventListener('submit', e => {
      e.preventDefault();
      Swal.fire({
        title: 'ลบสินค้า?',
        text: 'คุณต้องการลบข้อมูลนี้หรือไม่',
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
