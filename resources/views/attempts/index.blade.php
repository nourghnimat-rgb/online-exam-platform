<x-layout title="Attempts">
    <h1 class="h3 mb-3">{{ $exam->title }} - Attempts</h1>

    @forelse ($attempts as $attempt)
    @if ($loop->first)
    <div class="table-responsive bg-white border rounded">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Status</th>
                    <th>Score</th>
                    <th>Percentage</th>
                    <th>Result</th>
                    <th>Submitted</th>
                </tr>
            </thead>
            <tbody>
    @endif
                <tr>
                    <td>{{ $attempt->student->name }}<div class="text-muted small">{{ $attempt->student->email }}</div></td>
                    <td>{{ $attempt->status }}</td>
                    <td>{{ $attempt->score ?? '-' }}</td>
                    <td>{{ $attempt->percentage !== null ? $attempt->percentage . '%' : '-' }}</td>
                    <td>
                        @if (! $attempt->graded_at)
                        <span class="badge text-bg-secondary">Not graded</span>
                        @elseif ($attempt->passed)
                        <span class="badge text-bg-success">Passed</span>
                        @else
                        <span class="badge text-bg-danger">Failed</span>
                        @endif
                    </td>
                    <td>{{ $attempt->submitted_at?->format('d M, H:i') ?? '-' }}</td>
                </tr>
    @if ($loop->last)
            </tbody>
        </table>
    </div>
    @endif
    @empty
    <div class="alert alert-light border text-center text-muted">No attempts yet.</div>
    @endforelse

    <div class="mt-3">{{ $attempts->links() }}</div>

    <a href="{{ route('exams.index', $exam->course_id) }}" class="btn btn-outline-secondary mt-3">Back to exams</a>
</x-layout>