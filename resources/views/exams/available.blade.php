<x-layout title="My Exams">
    <h1 class="h3 mb-3">Available exams</h1>

    @forelse ($exams as $exam)
        @php $attempt = $exam->attempts->first(); @endphp

        <div class="bg-white border rounded p-3 mb-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <strong>{{ $exam->title }}</strong>
                    <div class="text-muted small">
                        {{ $exam->course->name }} · {{ $exam->duration_minutes }} min ·
                        closes {{ $exam->closes_at->format('d M, H:i') }}
                    </div>
                </div>

                <div>
                    @if (! $attempt)
                        <span class="badge text-bg-primary">Not started</span>
                    @elseif ($attempt->status === 'in_progress')
                        <span class="badge text-bg-warning">In progress</span>
                    @else
                        <span class="badge text-bg-secondary">Submitted</span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-light border text-center text-muted">
            No exams available right now.
        </div>
    @endforelse

    {{ $exams->links() }}
</x-layout>