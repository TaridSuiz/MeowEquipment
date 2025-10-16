@extends('home')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">:: Articles ::</h3>
  <a href="{{ route('admin.articles.create') }}" class="btn btn-primary btn-sm">Add</a>
</div>

<table class="table table-bordered table-striped align-middle">
  <thead class="table-light">
    <tr>
      <th class="text-center" style="width:80px">#ID</th>
      <th>Title</th>
      <th>Author</th>
      <th class="text-center" style="width:160px">Created</th>
      <th class="text-center" style="width:180px">Actions</th>
    </tr>
  </thead>
  <tbody>
  @forelse ($articles as $a)
    <tr>
      <td class="text-center">{{ $articles->firstItem() + $loop->index }}</td>
      <td>{{ $a->title }}</td>
      <td>{{ optional($a->author)->name ?? '-' }}</td>
      <td class="text-center">{{ $a->created_at }}</td>
      <td class="text-center">
        <a href="{{ route('admin.articles.edit', $a->article_id) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('admin.articles.destroy', $a->article_id) }}" method="POST" class="d-inline delete-form">
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

<div>{{ $articles->links() }}</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.delete-form').forEach(f => {
    f.addEventListener('submit', e => { if(!confirm('ยืนยันลบบทความ?')) e.preventDefault(); });
  });
});
</script>
@endpush
