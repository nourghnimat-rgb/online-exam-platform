<x-layout title="Users">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Users</h1>
        @can('create', App\Models\User::class)
            <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>
        @endcan
    </div>
    
    <form method="GET" action="{{ route('users.index') }}" class="mb-3">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
            placeholder="Search name or email">
    </form>

    <div class="table-responsive">
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @can('updateRole', $user)
                                <form method="POST" action="{{ route('users.role', $user) }}" class="d-flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="form-select form-select-sm">
                                        <option value="admin" @selected($user->role == 'admin')>Admin</option>
                                        <option value="instructor" @selected($user->role == 'instructor')>Instructor</option>
                                        <option value="student" @selected($user->role == 'student')>Student</option>
                                    </select>
                                    <button class="btn btn-sm btn-secondary">Save</button>
                                </form>
                            @else
                                {{ $user->role }}
                            @endcan
                        </td>
                        <td>
                            @can('delete', $user)
                                <form method="POST" action="{{ route('users.destroy', $user) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
</x-layout>