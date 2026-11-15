<x-layout title="Edit question">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $exam->title }} — Edit question</h1>
        <a href="{{ route('questions.index', $exam) }}" class="btn btn-outline-secondary">Back to questions</a>
    </div>

    @error('exam') <div class="alert alert-danger">{{ $message }}</div> @enderror

    @php
        $hasOld = old('text') !== null;
        $slots = max(4, $question->options->count());
    @endphp

    <form method="POST" action="{{ route('questions.update', $question) }}" class="bg-white border rounded p-4">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label" for="text">Question</label>
            <textarea id="text" name="text" rows="2"
                      class="form-control @error('text') is-invalid @enderror">{{ old('text', $question->text) }}</textarea>
            @error('text') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="type">Type</label>
                @php $type = old('type', $question->type->value); @endphp
                <select id="type" name="type" class="form-select @error('type') is-invalid @enderror">
                    <option value="single_choice" @selected($type === 'single_choice')>Single choice</option>
                    <option value="multiple_choice" @selected($type === 'multiple_choice')>Multiple choice</option>
                    <option value="short_text" @selected($type === 'short_text')>Short text</option>
                </select>
                @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="points">Points</label>
                <input type="number" id="points" name="points" min="1" value="{{ old('points', $question->points) }}"
                       class="form-control @error('points') is-invalid @enderror">
                @error('points') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <p class="fw-semibold mb-2">Options (for choice questions — tick the correct ones)</p>
        @error('options') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

        @for ($i = 0; $i < $slots; $i++)
            @php
                $option = $question->options[$i] ?? null;
                $text = $hasOld ? old("options.$i.text") : $option?->text;
                $correct = $hasOld ? old("options.$i.is_correct") : $option?->is_correct;
            @endphp
            <div class="input-group mb-2">
                <div class="input-group-text">
                    <input type="checkbox" class="form-check-input mt-0" name="options[{{ $i }}][is_correct]" value="1"
                           @checked($correct)>
                </div>
                <input type="text" class="form-control" name="options[{{ $i }}][text]"
                       value="{{ $text }}" placeholder="Option {{ $i + 1 }}">
            </div>
        @endfor

        <div class="mt-3 mb-3">
            <label class="form-label" for="correct_answer">Correct answer (short text only)</label>
            <input type="text" id="correct_answer" name="correct_answer"
                   value="{{ old('correct_answer', $question->correct_answer) }}"
                   class="form-control @error('correct_answer') is-invalid @enderror">
            @error('correct_answer') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button class="btn btn-primary">Save changes</button>
    </form>
</x-layout>