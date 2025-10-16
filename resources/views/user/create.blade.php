{{-- resources/views/user/create.blade.php --}}
@extends('home')

@section('css_before')
<style>
  /* ===== Table container & visuals ===== */
  .table-card{ border-radius:.75rem; box-shadow:0 .25rem .75rem rgba(0,0,0,.06); }

  /* ===== Avatar ===== */
  .avatar-36{
    width:36px; height:36px; border-radius:50%;
    display:inline-flex; align-items:center; justify-content:center;
    background:#f2f4f7; color:#6b7280; font-weight:700;
    border:1px solid #e5e7eb; object-fit:cover;
  }

  /* ===== Quick password grid (แก้ปุ่ม Update ล้นคอลัมน์) ===== */
  .quick-pass-grid{
    display:grid;
    grid-template-columns: 1fr 1fr auto; /* password | confirm | Update */
    gap:.5rem;
    align-items:center;
  }
  .quick-pass-grid .form-control{ min-width:0; } /* ยอมย่อไม่ดันล้น */
  .quick-pass-grid .btn{ white-space:nowrap; }
  td.col-quick-pass{ min-width:360px; } /* กันแคบเกินไปในเดสก์ท็อป */

  /* ===== Role select row ===== */
  .quick-actions .form-select,
  .quick-actions .btn{ white-space:nowrap; }

  @media (max-width: 992px){
    .quick-pass-grid{ grid-template-columns: 1fr; }  /* จอแคบ: เรียงลง 1 คอลัมน์ */
  }
  @media (max-width: 576px){
    .quick-actions .btn,
    .quick-actions .form-select,
    .quick-actions .form-control{ width:100%; }
  }
</style>
@endsection

@section('content')
  <h3>Create Admin User</h3>

  {{-- ฟอร์มสร้างผู้ใช้ใหม่ --}}
  <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
    @csrf
    <div class="mb-3">
      <label class="form-label">Name *</label>
      <input name="name" class="form-control" required minlength="3" value="{{ old('name') }}">
      @error('name') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Email *</label>
      <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
      @error('email') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Password *</label>
      <input type="password" name="password" class="form-control" required minlength="8">
      @error('password') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Role</label>
      <select name="role" class="form-select">
        <option value="user"  @selected(old('role')==='user')>User</option>
        <option value="admin" @selected(old('role')==='admin')>Admin</option>
      </select>
      @error('role') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Avatar</label>
      <input type="file" name="profile_img" class="form-control" accept="image/*">
      <small class="text-muted">jpeg/png/jpg ไม่เกิน 5MB</small>
      @error('profile_img') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <button class="btn btn-primary">Create</button>
    {{-- <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a> --}}
  </form>

  {{-- ตารางรายชื่อผู้ใช้ + ฟอร์มแก้ role/password แบบด่วน --}}
  <h3 class="mt-4">Users in system</h3>

  <div class="table-responsive table-card">
    <table class="table table-bordered table-striped align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th class="text-center" style="width:80px">#</th>
          <th style="min-width:260px;">User</th>
          <th style="min-width:240px;">Contact</th>
          <th class="text-center" style="width:200px">Role</th>
          <th class="text-center" style="min-width:360px;">Quick Password</th>
          <th class="text-center" style="width:120px">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($users as $u)
          <tr>
            {{-- running number --}}
            <td class="text-center">
              {{ $users->firstItem() + $loop->index }}
            </td>

            {{-- User (avatar + name + id) --}}
            <td>
              <div class="d-flex align-items-center gap-2">
                @php $img = $u->profile_img ?? null; @endphp
                @if($img)
                  <img src="{{ asset('storage/'.$img) }}" alt="" class="avatar-36">
                @else
                  <div class="avatar-36">
                    {{ mb_strtoupper(mb_substr($u->name ?? 'U',0,1,'UTF-8'),'UTF-8') }}
                  </div>
                @endif

                <div>
                  <div class="fw-semibold">{{ $u->name }}</div>
                 
                </div>
              </div>
            </td>

            {{-- Contact (email + joined) --}}
            <td>
              <div><a href="mailto:{{ $u->email }}">{{ $u->email }}</a></div>
              @if(!empty($u->created_at))
                <small class="text-muted">Joined: {{ \Carbon\Carbon::parse($u->created_at)->format('Y-m-d') }}</small>
              @endif
            </td>

            {{-- Role (badge + inline form) --}}
            <td class="text-center">
              <div class="d-flex justify-content-center align-items-center gap-2 quick-actions">
                <span class="badge {{ $u->role==='admin' ? 'bg-success' : 'bg-secondary' }}" style="min-width:64px;">
                  {{ $u->role ?? 'user' }}
                </span>
                <form action="{{ route('admin.users.role.update', $u->user_id) }}" method="POST" class="d-flex gap-2">
                  @csrf @method('PUT')
                  <select name="role" class="form-select form-select-sm" style="min-width:110px">
                    <option value="user"  @selected($u->role==='user')>User</option>
                    <option value="admin" @selected($u->role==='admin')>Admin</option>
                  </select>
                  <button class="btn btn-sm btn-outline-primary" type="submit">Save</button>
                </form>
              </div>
            </td>

            {{-- Quick password change (ใช้ Grid กันล้น) --}}
            <td class="col-quick-pass">
              <form action="{{ route('admin.users.password.quick', $u->user_id) }}" method="POST" class="quick-pass-grid">
                @csrf @method('PUT')

                <input type="password" name="password"
                       class="form-control form-control-sm" placeholder="New password"
                       required minlength="8">

                <input type="password" name="password_confirmation"
                       class="form-control form-control-sm" placeholder="Confirm"
                       required minlength="8">

                <button class="btn btn-sm btn-outline-secondary" type="submit">Update</button>
              </form>
            </td>

            {{-- Actions --}}
            <td class="text-center">
              <form action="{{ route('admin.users.destroy', $u->user_id) }}" method="POST" class="d-inline delete-form" data-user="{{ $u->name }}">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" type="submit">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted py-4">— No users —</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-2">
    {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
  </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.delete-form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      const name = form.getAttribute('data-user') || 'this user';
      if(!confirm(`ยืนยันลบผู้ใช้: ${name} ?`)){
        e.preventDefault();
      }
    });
  });
});
</script>
@endpush
