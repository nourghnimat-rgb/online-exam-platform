<x-layout title="Questions">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $exam->title }} — Questions</h1>
        <a href="{{ route('exams.index', $exam->course_id) }}" class="btn btn-outline-secondary">Back to exams</a>
    </div>

    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @error('exam') <div class="alert alert-danger">{{ $message }}</div> @enderror

    
    @forelse ($questions as $question)
        <div class="bg-white border rounded p-3 mb-3">
            <div class="d-flex justify-content-between">
                <div>
                    <strong>{{ $loop->iteration }}. {{ $question->text }}</strong>
                    <div class="text-muted small">
                        {{ str_replace('_', ' ', $question->type->value) }} · {{ $question->points }} pts
                    </div>
                </div>

                @can('delete', $question)
                <form method="POST" action="{{ route('questions.destroy', $question) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
                @endcan
            </div>

            @if ($question->type->hasOptions())
                <ul class="mt-2 mb-0">
                    @foreach ($question->options as $option)
                        <li>{{ $option->text }} @if ($option->is_correct) <span class="badge text-bg-success">correct</span> @endif</li>
                    @endforeach
                </ul>
            @else
                <div class="mt-2">Correct answer: <strong>{{ $question->correct_answer }}</strong></div>
            @endif
        </div>
    @empty
        <div class="alert alert-light border text-center text-muted">No questions yet.</div>
    @endforelse

  
    <form method="POST" action="{{ route('questions.store', $exam) }}" class="bg-white border rounded p-4 mt-4">
        @csrf
        <h2 class="h5 mb-3">Add question</h2>

        <div class="mb-3">
            <label class="form-label" for="text">Question</label>
            <textarea id="text" name="text" rows="2"
                      class="form-control @error('text') is-invalid @enderror">{{ old('text') }}</textarea>
            @error('text') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="type">Type</label>
                <select id="type" name="type" class="form-select @error('type') is-invalid @enderror">
                    <option value="single_choice" @selected(old('type') === 'single_choice')>Single choice</option>
                    <option value="multiple_choice" @selected(old('type') === 'multiple_choice')>Multiple choice</option>
                    <option value="short_text" @selected(old('type') === 'short_text')>Short text</option>
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="points">Points</label>
                <input type="number" id="points" name="points" min="1" value="{{ old('points', 1) }}"
                       class="form-control @error('points') is-invalid @enderror">
                @error('points') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <p class="fw-semibold mb-2">Options (for choice questions — tick the correct ones)</p>
        @error('options') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

        @for ($i = 0; $i < 4; $i++)
            <div class="input-group mb-2">
                <div class="input-group-text">
                    <input type="checkbox" class="form-check-input mt-0" name="options[{{ $i }}][is_correct]" value="1"
                           @checked(old("options.$i.is_correct"))>
                </div>
                <input type="text" class="form-control" name="options[{{ $i }}][text]"
                       value="{{ old("options.$i.text") }}" placeholder="Option {{ $i + 1 }}">
            </div>
        @endfor

        <div class="mt-3 mb-3">
            <label class="form-label" for="correct_answer">Correct answer (short text only)</label>
            <input type="text" id="correct_answer" name="correct_answer" value="{{ old('correct_answer') }}"
                   class="form-control @error('correct_answer') is-invalid @enderror">
            @error('correct_answer') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button class="btn btn-primary">Add question</button>
    </form>
</x-layout>