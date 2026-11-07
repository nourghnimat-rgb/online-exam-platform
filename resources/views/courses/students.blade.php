<x-layout title="Students">
    <h1 class="h3 mb-3">Students — {{ $course->name }}</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-warning">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('courses.students.add', $course) }}" class="row g-2 col-md-6 mb-4">
        @csrf
        <div class="col">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Student email"
                class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-auto">
            <button class="btn btn-primary">Add Student</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered bg-white">
            <thead>
                <tr><th>Name</th><th>Email</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>
                            <form method="POST" action="{{ route('courses.students.remove', [$course, $student]) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted">No students yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('courses.index') }}" class="btn btn-link">Back</a>
</x-layout>