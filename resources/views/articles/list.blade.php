@extends('home')

@section('content')
  <h3 class="mb-3">
    :: Article Management ::
    <a href="{{ url('/article/adding') }}" class="btn btn-primary btn-sm">Add Article</a>
  </h3>

  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr class="table-info">
        <th class="text-center" width="6%">No.</th>
        <th width="12%">Cover</th>
        <th>Title</th>
        <th width="18%">Author</th>
        <th width="16%" class="text-center">Created</th>
        <th width="8%"  class="text-center">Edit</th>
        <th width="8%"  class="text-center">Delete</th>
      </tr>
    </thead>
    <tbody>
      @forelse($articles as $row)
        <tr>
          <td class="text-center">
            {{ ($articles->currentPage()-1)*$articles->perPage() + $loop->iteration }}
          </td>
          <td class="text-center">
            @if($row->cover_image)
              <img src="{{ asset('storage/'.$row->cover_image) }}" alt="" width="90" height="60" style="object-fit:cover;">
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td>{{ $row->title }}</td>
          <td>{{ optional($row->author)->name ?? '-' }}</td>
          <td class="text-center">{{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i') }}</td>
          <td class="text-center">
            <a href="{{ url('/article/'.$row->article_id) }}" class="btn btn-warning btn-sm">Edit</a>
          </td>
          <td class="text-center">
            <form action="{{ url('/article/remove/'.$row->article_id) }}" method="POST" class="form-delete d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center text-muted">ไม่พบบทความ</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{ $articles->links() }}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form.form-delete').forEach(f => {
    f.addEventListener('submit', e => {
      e.preventDefault();
      Swal.fire({
        title: 'ลบบทความ?',
        text: 'ต้องการลบบทความนี้หรือไม่',
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
