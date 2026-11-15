<x-layout title="My Results">
    <h1 class="h3 mb-3">My results</h1>

    @forelse ($attempts as $attempt)
    @if ($loop->first)
    <div class="table-responsive bg-white border rounded">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Exam</th>
                    <th>Course</th>
                    <th>Submitted</th>
                    <th>Score</th>
                    <th>Result</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
    @endif
                <tr>
                    <td>{{ $attempt->exam->title }}</td>
                    <td>{{ $attempt->exam->course->name }}</td>
                    <td>{{ $attempt->submitted_at?->format('d M, H:i') ?? '-' }}</td>
                    <td>
                        @if ($attempt->graded_at)
                        {{ $attempt->score }} ({{ $attempt->percentage }}%)
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        @if (! $attempt->graded_at)
                        <span class="badge text-bg-secondary">Waiting for result</span>
                        @elseif ($attempt->passed)
                        <span class="badge text-bg-success">Passed</span>
                        @else
                        <span class="badge text-bg-danger">Failed</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @can('viewResult', $attempt)
                        <a href="{{ route('attempts.result', $attempt) }}" class="btn btn-sm btn-outline-primary">View</a>
                        @endcan
                    </td>
                </tr>
    @if ($loop->last)
            </tbody>
        </table>
    </div>
    @endif
    @empty
    <div class="alert alert-light border text-center text-muted">
        You haven't taken any exams yet.
    </div>
    @endforelse

    <div class="mt-3">{{ $attempts->links() }}</div>

    <a href="{{ route('exams.available') }}" class="btn btn-outline-secondary mt-3">Back to my exams</a>
</x-layout>