<x-layout title="Classes">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Courses</h1>
        @can('create', App\Models\Course::class)
        <a href="{{ route('courses.create') }}" class="btn btn-primary">Add Courses</a>
        @endcan
    </div>


    <div class="table-responsive">
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Instructors</th>
                    <th>Members</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $course)
                <tr>
                    <td>{{ $course->name }}</td>
                    <td>
                        @forelse ($course->instructors as $instructor)
                        <span class="badge text-bg-secondary">{{ $instructor->name }}</span>
                        @empty
                        <span class="text-muted">None</span>
                        @endforelse
                    </td>
                    <td>{{ $course->users_count }}</td>
                    <td class="d-flex gap-2">
                        @can('manageStudents', $course)
                        <a href="{{ route('courses.students.index', $course) }}" class="btn btn-sm btn-info">Students</a>
                        @endcan
                        @can('update', $course)
                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-secondary">Edit</a>
                        @endcan
                        @can('delete', $course)
                        <form method="POST" action="{{ route('courses.destroy', $course) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                        @endcan
                        @can('manageStudents', $course)
                        <a href="{{ route('exams.index', $course) }}" class="btn btn-sm btn-success">Exams</a>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No classes yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $courses->links() }}
</x-layout>