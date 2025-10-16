@extends('home')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">:: Categories ::</h3>
  <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">Add</a>
</div>

<table class="table table-bordered table-striped align-middle">
  <thead class="table-light">
    <tr>
      <th class="text-center" style="width: 80px">#ID</th>
      <th>Category name</th>
      <th>Description</th>
      <th class="text-center" style="width: 200px">Created at</th>
      <th class="text-center" style="width: 180px">Actions</th>
    </tr>
  </thead>
  <tbody>
  @forelse ($categories as $cat)
    <tr>
      <td class="text-center">{{ $categories->firstItem() + $loop->index}}</td>
      <td>{{ $cat->category_name }}</td>
      <td>{{ $cat->description }}</td>
      <td class="text-center">{{ $cat->created_at }}</td>
      <td class="text-center">
        <a href="{{ route('admin.categories.edit', $cat->category_id) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('admin.categories.destroy', $cat->category_id) }}" method="POST" class="d-inline delete-form">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-sm btn-danger">Delete</button>
        </form>
      </td>
    </tr>
  @empty
    <tr><td colspan="5" class="text-center text-muted">— No data —</td></tr>
  @endforelse
  </tbody>
</table>

<div>{{ $categories->links() }}</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.delete-form').forEach(f => {
    f.addEventListener('submit', e => {
      if (!confirm('ยืนยันลบหมวดหมู่?')) e.preventDefault();
    });
  });
});
</script>
@endpush
