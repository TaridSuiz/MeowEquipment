@extends('home')

@section('content')
    <h3> :: User Management ::
        <a href="/user/adding" class="btn btn-primary btn-sm">Add User</a>
    </h3>

    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr class="table-info">
                <th width="5%" class="text-center">No.</th>
                <th width="10%" class="text-center">Pic</th>
                <th width="25%">Name</th>
                <th width="25%">Email</th>
                <th width="15%" class="text-center">Role</th>
                <th width="5%" class="text-center">Edit</th>
                <th width="5%" class="text-center">Reset</th>
                <th width="5%" class="text-center">Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $row)
                <tr>
                    <td class="text-center">{{ $row->user_id }}</td>
                    <td class="text-center">
                        @if($row->profile_img)
                            <img src="{{ asset('storage/' . $row->profile_img) }}" width="60" class="rounded">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->email }}</td>
                    <td class="text-center">{{ ucfirst($row->role) }}</td>

                    <td class="text-center">
                        <a href="/user/{{ $row->user_id }}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                    <td class="text-center">
                        <a href="/user/reset/{{ $row->user_id }}" class="btn btn-info btn-sm">Reset</a>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm"
                                onclick="deleteConfirm({{ $row->user_id }})">Delete</button>
                        <form id="delete-form-{{ $row->user_id }}" 
                              action="/user/remove/{{ $row->user_id }}" 
                              method="POST" style="display: none;">
                            @csrf
                            @method('delete')
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        {{ $users->links() }}
    </div>
@endsection

@section('js_before')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteConfirm(id) {
            Swal.fire({
                title: 'แน่ใจหรือไม่?',
                text: "คุณต้องการลบข้อมูลนี้จริง ๆ หรือไม่",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endsection
