<x-layout title="Exams">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $course->name }} — Exams</h1>
        <a href="{{ route('exams.create', $course) }}" class="btn btn-primary">Add Exam</a>
    </div>

    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @error('status') <div class="alert alert-danger">{{ $message }}</div> @enderror
    @error('exam') <div class="alert alert-danger">{{ $message }}</div> @enderror

    <div class="table-responsive">
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Opens</th>
                    <th>Closes</th>
                    <th>Questions</th>
                    <th>Attempts</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($exams as $exam)
                <tr>
                    <td>{{ $exam->title }}</td>
                    <td>
                        <span class="badge text-bg-{{ $exam->status === 'published' ? 'success' : 'secondary' }}">
                            {{ ucfirst($exam->status) }}
                        </span>
                    </td>
                    <td>{{ $exam->opens_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $exam->closes_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $exam->questions_count }}</td>
                    <td>{{ $exam->attempts_count }}</td>
                    <td class="d-flex gap-2">
                        @can('manageQuestions', $exam)
                        <a href="{{ route('questions.index', $exam) }}" class="btn btn-sm btn-outline-primary">Questions</a>
                        @endcan
                        @can('viewAttempts', $exam)
                        <a href="{{ route('exams.attempts', $exam) }}" class="btn btn-sm btn-outline-secondary">Attempts</a>
                        @endcan
                        @can('update', $exam)
                        <a href="{{ route('exams.edit', $exam) }}" class="btn btn-sm btn-secondary">Edit</a>

                        @if ($exam->status === 'draft')
                        <form method="POST" action="{{ route('exams.publish', $exam) }}">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-success">Publish</button>
                        </form>
                        @elseif ($exam->attempts_count === 0)
                        <form method="POST" action="{{ route('exams.unpublish', $exam) }}">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-warning">Unpublish</button>
                        </form>
                        @endif
                        @endcan

                        @can('delete', $exam)
                        @if ($exam->attempts_count === 0)
                        <form method="POST" action="{{ route('exams.destroy', $exam) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                        @endif
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No exams yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $exams->links() }}
</x-layout>