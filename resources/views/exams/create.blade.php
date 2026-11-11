<x-layout title="Create Exam">
    <h1 class="h3 mb-1">Create Exam</h1>
    <p class="text-muted mb-4">Course: {{ $course->name }}</p>

    <form method="POST" action="{{ route('exams.store', $course) }}" class="bg-white border rounded p-4">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}"
                   class="form-control @error('title') is-invalid @enderror">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="description">Description</label>
            <textarea id="description" name="description" rows="3"
                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="opens_at">Opens at</label>
                <input type="datetime-local" id="opens_at" name="opens_at" value="{{ old('opens_at') }}"
                       class="form-control @error('opens_at') is-invalid @enderror">
                @error('opens_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="closes_at">Closes at</label>
                <input type="datetime-local" id="closes_at" name="closes_at" value="{{ old('closes_at') }}"
                       class="form-control @error('closes_at') is-invalid @enderror">
                @error('closes_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="duration_minutes">Duration (minutes)</label>
                <input type="number" id="duration_minutes" name="duration_minutes" min="1"
                       value="{{ old('duration_minutes') }}"
                       class="form-control @error('duration_minutes') is-invalid @enderror">
                @error('duration_minutes') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="pass_mark">Pass mark (%)</label>
                <input type="number" step="0.01" min="0" max="100" id="pass_mark" name="pass_mark"
                       value="{{ old('pass_mark') }}"
                       class="form-control @error('pass_mark') is-invalid @enderror">
                @error('pass_mark') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-check mb-2">
            <input type="checkbox" class="form-check-input" id="show_correct_answers"
                   name="show_correct_answers" value="1" @checked(old('show_correct_answers'))>
            <label class="form-check-label" for="show_correct_answers">
                Show correct answers to students after submitting
            </label>
        </div>

        <div class="form-check mb-4">
            <input type="checkbox" class="form-check-input" id="randomize_questions"
                   name="randomize_questions" value="1" @checked(old('randomize_questions'))>
            <label class="form-check-label" for="randomize_questions">Randomize question order</label>
        </div>

        <button class="btn btn-primary">Create Exam</button>
        <a href="{{ route('exams.index', $course) }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</x-layout>