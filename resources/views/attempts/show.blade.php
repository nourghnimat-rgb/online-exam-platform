<x-layout title="{{ $attempt->exam->title }}">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $attempt->exam->title }}</h1>
        <div>
            <small class="text-muted" id="save-status"></small>
            <span class="badge text-bg-dark fs-5" id="timer" data-remaining="{{ $attempt->remainingSeconds() }}">--:--</span>
        </div>
    </div>

    @error('attempt') <div class="alert alert-danger">{{ $message }}</div> @enderror

    <form id="attempt-form" method="POST" action="{{ route('attempts.answers', $attempt) }}">
        @csrf
        @method('PUT')

        @forelse ($questions as $question)
        @php $given = $attempt->attemptAnswers->where('question_id', $question->id); @endphp
        <div class="bg-white border rounded p-3 mb-3">
            <strong>{{ $loop->iteration }}. {{ $question->text }}</strong>
            <span class="text-muted small">({{ $question->points }} pts)</span>

            <div class="mt-2">
                @if ($question->type->value === 'single_choice')
                @foreach ($question->options as $option)
                <div class="form-check">
                    <input class="form-check-input" type="radio"
                        name="answers[{{ $question->id }}]"
                        id="o{{ $option->id }}" value="{{ $option->id }}"
                        @checked($given->contains('selected_option_id', $option->id))>
                    <label class="form-check-label" for="o{{ $option->id }}">{{ $option->text }}</label>
                </div>
                @endforeach
                @elseif ($question->type->value === 'multiple_choice')
                @foreach ($question->options as $option)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                        name="answers[{{ $question->id }}][]"
                        id="o{{ $option->id }}" value="{{ $option->id }}"
                        @checked($given->contains('selected_option_id', $option->id))>
                    <label class="form-check-label" for="o{{ $option->id }}">{{ $option->text }}</label>
                </div>
                @endforeach
                @else
                <input type="text" class="form-control"
                    name="answers[{{ $question->id }}]"
                    value="{{ $given->first()->text_answer ?? '' }}">
                @endif
            </div>
        </div>
        @empty
        <div class="alert alert-info">This exam has no questions.</div>
        @endforelse
    </form>

    <form id="submit-form" method="POST" action="{{ route('attempts.submit', $attempt) }}">
        @csrf
        <button type="submit" class="btn btn-success">Submit exam</button>
    </form>

    <script>
        const timer = document.getElementById('timer');
        const form = document.getElementById('attempt-form');
        const submitForm = document.getElementById('submit-form');
        const message = document.getElementById('save-status');
        let seconds = Number(timer.dataset.remaining);

        function showTime() {
            const minutes = Math.floor(seconds / 60);
            let rest = seconds % 60;
            if (rest < 10) {
                rest = '0' + rest;
            }
            timer.textContent = minutes + ':' + rest;
        }

        showTime();

        const clock = setInterval(function () {
            seconds = seconds - 1;
            showTime();

            if (seconds <= 0) {
                clearInterval(clock);
                window.location = "{{ route('exams.available') }}";
            }
        }, 1000);

        form.addEventListener('change', function () {
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'Accept': 'application/json' },
                keepalive: true
            })
            .then(function (response) {
                return response.json();
            })
            .then(function (result) {
                if (result.saved_at) {
                    message.textContent = 'Saved at ' + result.saved_at;
                } else {
                    message.textContent = 'Could not save';
                }
            })
            .catch(function () {
                message.textContent = 'Could not save';
            });
        });
    </script>
</x-layout>