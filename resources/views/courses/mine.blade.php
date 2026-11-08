<x-layout title="My Courses">
    <h1 class="h3 mb-3">My Courses</h1>

    <div class="table-responsive">
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Description</th>
                    <th>Exams</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $course)
                    <tr>
                        <td>{{ $course->name }}</td>
                        <td>{{ $course->description ?? '—' }}</td>
                        <td>{{ $course->exams_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            You are not enrolled in any course yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>