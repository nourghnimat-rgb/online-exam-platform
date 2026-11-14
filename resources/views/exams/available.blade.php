<x-layout title="My Exams">
    <h1 class="h3 mb-3">Available exams</h1>
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @error('exam')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @forelse ($exams as $exam)
    @php $attempt = $exam->attempts->first();
    @endphp

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
                @can('take', $exam)
                <form method="POST" action="{{ route('attempts.store', $exam) }}">
                    @csrf
                    <button class="btn btn-sm btn-primary">Start</button>
                </form>
                @endcan
                @elseif ($attempt->status === 'in_progress'&& $attempt->expires_at->isFuture())
                <a href="{{ route('attempts.show', $attempt) }}" class="btn btn-sm btn-warning">Continue</a>

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