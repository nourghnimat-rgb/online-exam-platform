<x-layout title="Result">
    <h1 class="h3 mb-3">{{ $attempt->exam->title }}</h1>

    <div class="bg-white border rounded p-3 mb-3">
        <div>Score: <strong>{{ $attempt->score }}</strong></div>
        <div>Percentage: <strong>{{ $attempt->percentage }}%</strong></div>
        @if ($attempt->passed)
        <span class="badge text-bg-success">Passed</span>
        @else
        <span class="badge text-bg-danger">Failed</span>
        @endif
    </div>

    @foreach ($attempt->exam->questions as $question)
    @php $given = $attempt->attemptAnswers->where('question_id', $question->id); @endphp
    <div class="bg-white border rounded p-3 mb-3">
        <strong>{{ $loop->iteration }}. {{ $question->text }}</strong>
        <span class="text-muted small">({{ $question->points }} pts)</span>

        <div class="mt-2">
            Your answer:
            @if ($question->type->value === 'short_text')
            {{ $given->first()->text_answer ?? '-' }}
            @else
            {{ $question->options->whereIn('id', $given->pluck('selected_option_id'))->pluck('text')->join(', ') ?: '-' }}
            @endif
        </div>

        @if ($attempt->exam->show_correct_answers)
        <div class="text-success">
            Correct answer:
            @if ($question->type->value === 'short_text')
            {{ $question->correct_answer }}
            @else
            {{ $question->options->where('is_correct', true)->pluck('text')->join(', ') }}
            @endif
        </div>
        @endif
    </div>
    @endforeach

    <a href="{{ route('exams.available') }}" class="btn btn-primary">Back to my exams</a>
</x-layout>