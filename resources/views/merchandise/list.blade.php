@extends('home')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">:: Merchandise ::</h3>
  <a href="{{ route('admin.merchandise.create') }}" class="btn btn-primary btn-sm">Add</a>
</div>

<table class="table table-bordered table-striped align-middle">
  <thead class="table-light">
    <tr>
      <th class="text-center" style="width:80px">No.</th> {{-- เปลี่ยนหัวคอลัมน์ --}}
      <th>Name</th>
      <th>Category</th>
      <th>Brand</th>
      <th class="text-end" style="width:120px">Price</th>
      <th class="text-center" style="width:180px">Actions</th>
    </tr>
  </thead>
  <tbody>
  @forelse ($items as $m)
    <tr>
      <td class="text-center">{{ $items->firstItem() + $loop->index }}</td>
      <td>{{ $m->merchandise_name }}</td>
      <td>{{ optional($m->category)->category_name ?? '-' }}</td>
      <td>{{ $m->brand }}</td>
      <td class="text-end">{{ $m->price }}</td>
      <td class="text-center">
        <a href="{{ route('admin.merchandise.edit', $m->merchandise_id) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('admin.merchandise.destroy', $m->merchandise_id) }}" method="POST" class="d-inline delete-form">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
  @empty
    <tr><td colspan="6" class="text-center text-muted">— No data —</td></tr>
  @endforelse
  </tbody>
</table>

<div>{{ $items->links() }}</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.delete-form').forEach(f => {
    f.addEventListener('submit', e => { if(!confirm('ยืนยันลบสินค้า?')) e.preventDefault(); });
  });
});
</script>
@endpush
