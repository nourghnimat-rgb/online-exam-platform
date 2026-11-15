<x-layout title="{{ $attempt->exam->title }}">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $attempt->exam->title }}</h1>
        <div>
            <small class="text-muted" id="save-status"></small>
            <span class="badge text-bg-dark fs-5" id="timer" data-remaining="{{ $attempt->remainingSeconds() }}">--:--</span>
        </div>
    </div>

    @error('attempt')
    <div id="time-up" class="alert alert-warning d-none">
        Time is up. Your saved answers will be graded automatically.
    <a href="{{ route('attempts.mine') }}" class="alert-link">Go to my results</a>
    </div>
    <div class="alert alert-danger">{
        { $message }}

    </div>
    @enderror

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
        const timeUp = document.getElementById('time-up');

        
        let deadline = Date.now() + Number(timer.dataset.remaining) * 1000;
        let finished = false;

        function secondsLeft() {
            return Math.max(0, Math.ceil((deadline - Date.now()) / 1000));
        }

        function showTime() {
            const seconds = secondsLeft();
            const minutes = Math.floor(seconds / 60);
            let rest = seconds % 60;
            if (rest < 10) {
                rest = '0' + rest;
            }
            timer.textContent = minutes + ':' + rest;
            timer.classList.toggle('text-bg-danger', seconds <= 60);
            timer.classList.toggle('text-bg-dark', seconds > 60);
        }

        function finish() {
            if (finished) {
                return;
            }
            finished = true;
            clearInterval(clock);
            timer.textContent = '0:00';
            form.querySelectorAll('input, textarea, select').forEach(function (field) {
                field.disabled = true;
            });
            submitForm.classList.add('d-none');
            timeUp.classList.remove('d-none');
            message.textContent = '';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        showTime();

        const clock = setInterval(function () {
            showTime();
            if (secondsLeft() <= 0) {
                finish();
            }
        }, 1000);

        form.addEventListener('change', function () {
            if (finished) {
                return;
            }
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'Accept': 'application/json' },
                keepalive: true
            })
            .then(function (response) {
                
                if (response.status === 422 || response.status === 403) {
                    finish();
                    return null;
                }
                return response.json();
            })
            .then(function (result) {
                if (result === null) {
                    return;
                }
                if (result.saved_at) {
                    message.textContent = 'Saved at ' + result.saved_at;
                    if (typeof result.remaining_seconds === 'number') {
                        deadline = Date.now() + result.remaining_seconds * 1000;
                    }
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