{{-- resources/views/user/list.blade.php --}}
@extends('home')

@section('css_before')
<style>
  .toolbar{ gap:.5rem; flex-wrap:wrap; }
  .toolbar .form-control, .toolbar .form-select{ height: 42px; }
  .table thead th{ white-space:nowrap; }
  .table-responsive{ border-radius:.75rem; box-shadow:0 .25rem .75rem rgba(0,0,0,.06); }
  .badge-role{ text-transform:capitalize; }
  .btn-icon{ padding:.25rem .5rem; }
  @media (max-width: 576px){
    .action-col .btn{ width:100%; margin-bottom:.35rem; }
  }
</style>
@endsection

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">:: User Management ::</h3>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">Add</a>
  </div>

  {{-- Toolbar: Search + Per Page --}}
  <form method="GET" action="{{ url()->current() }}" class="d-flex toolbar mb-3">
    <div class="input-group" style="max-width:520px;">
      <span class="input-group-text">ค้นหา</span>
      <input type="text" class="form-control" name="q" placeholder="ชื่อ / อีเมล"
             value="{{ request('q') }}">
      <button class="btn btn-outline-secondary" type="submit">Go</button>
      @if(request()->hasAny(['q','per_page']))
        <a href="{{ url()->current() }}" class="btn btn-outline-dark">Reset</a>
      @endif
    </div>

    <div class="ms-auto d-flex align-items-center" style="gap:.5rem;">
      <label class="text-muted">Rows/page</label>
      <select name="per_page" class="form-select" onchange="this.form.submit()">
        @php $pp = (int) request('per_page', $users->perPage()); @endphp
        @foreach([10,15,20,30,50] as $opt)
          <option value="{{ $opt }}" @selected($pp===$opt)>{{ $opt }}</option>
        @endforeach
      </select>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover align-middle mb-0">
      <thead class="table-info">
        <tr>
          <th class="text-center" width="6%">#</th>
          <th>Name</th>
          <th>Email</th>
          <th class="text-center" width="10%">Role</th>
          <th class="text-center" width="26%">Action</th>
        </tr>
      </thead>
      <tbody>
      @forelse ($users as $i => $user)
        <tr>
          <td class="text-center">{{ $users->firstItem() + $i }}</td>
          <td>
            <div class="fw-semibold">{{ $user->name }}</div>
            @if(!empty($user->created_at))
              <small class="text-muted">Joined: {{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }}</small>
            @endif
          </td>
          <td>
            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
          </td>
          <td class="text-center">
            @php $isAdmin = ($user->role === 'admin'); @endphp
            <span class="badge badge-role bg-{{ $isAdmin ? 'success' : 'secondary' }}">
              {{ $user->role ?? 'user' }}
            </span>
          </td>
          <td class="text-center action-col">
            <div class="d-inline-flex flex-wrap justify-content-center" style="gap:.35rem;">
              <a class="btn btn-sm btn-warning" href="{{ route('admin.users.edit', $user->user_id) }}">Edit</a>
              <a class="btn btn-sm btn-info" href="{{ route('admin.users.reset.edit', $user->user_id) }}">Reset</a>
              <form action="{{ route('admin.users.destroy', $user->user_id) }}"
                    method="POST" class="d-inline delete-form" data-user="{{ $user->name }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center text-muted py-4">— No data —</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-2">
    {{-- คง query string (q, per_page) ขณะเปลี่ยนหน้า --}}
    {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
  </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // ยืนยันก่อนลบ (Vanilla JS)
  document.querySelectorAll('.delete-form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      const name = form.getAttribute('data-user') || 'this user';
      if(!confirm(`ยืนยันลบผู้ใช้: ${name} ?`)){
        e.preventDefault();
        return false;
      }
    });
  });
});
</script>
@endpush
