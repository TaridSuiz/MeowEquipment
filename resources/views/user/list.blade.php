@extends('home')

@section('content')
  <h3>
    :: User Management ::
    <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">Add User</a>
  </h3>

  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr class="table-info">
        <th class="text-center" width="6%">No.</th>
        <th width="10%">Avatar</th>
        <th width="22%">Name</th>
        <th width="28%">Email</th>
        <th class="text-center" width="10%">Role</th>
        <th class="text-center" width="8%">Edit</th>
        <th class="text-center" width="12%">Reset PW</th>
        <th class="text-center" width="8%">Delete</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $row)
        <tr>
          <td class="text-center">
            {{ ($users->currentPage()-1)*$users->perPage() + $loop->iteration }}
          </td>
          <td>
            @if($row->profile_img)
              <img src="{{ asset('storage/'.$row->profile_img) }}" alt="avatar" width="60" height="60" style="object-fit:cover;border-radius:8px;">
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td>{{ $row->name }}</td>
          <td>{{ $row->email }}</td>
          <td class="text-center">
            <span class="badge {{ $row->role === 'admin' ? 'bg-danger' : 'bg-secondary' }}">{{ $row->role }}</span>
          </td>
          <td class="text-center">
            <a class="btn btn-warning btn-sm" href="{{ route('user.edit', $row->user_id) }}">Edit</a>
          </td>
          <td class="text-center">
            <a class="btn btn-info btn-sm" href="{{ route('user.reset', $row->user_id) }}">Reset</a>
          </td>
          <td class="text-center">
            <form action="{{ route('user.destroy', $row->user_id) }}" method="POST" class="form-delete d-inline">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8" class="text-center text-muted">No users</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{ $users->links() }}
@endsection

{{-- SweetAlert2 confirm --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form.form-delete').forEach(form => {
    form.addEventListener('submit', e => {
      e.preventDefault();
      Swal.fire({
        title: 'ลบผู้ใช้?',
        text: 'คุณต้องการลบผู้ใช้นี้หรือไม่',
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
