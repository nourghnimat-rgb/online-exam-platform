<x-layout title="Edit Class">
    <h1 class="h3 mb-3">Edit Class</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('courses.update', $course) }}" class="col-md-6 mb-5">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $course->name) }}"
                class="form-control @error('name') is-invalid @enderror">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" rows="3"
                class="form-control @error('description') is-invalid @enderror">{{ old('description', $course->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary">Save</button>
        <a href="{{ route('courses.index') }}" class="btn btn-link">Back</a>
    </form>

    <h2 class="h5">Instructors</h2>

    <form method="POST" action="{{ route('courses.instructors', $course) }}" class="col-md-6">
        @csrf
        @method('PUT')

        @forelse ($instructors as $instructor)
            <div class="form-check">
                <input type="checkbox" name="instructor_ids[]" value="{{ $instructor->id }}"
                    id="i{{ $instructor->id }}" class="form-check-input"
                    @checked($course->users->contains($instructor->id))>
                <label for="i{{ $instructor->id }}" class="form-check-label">
                    {{ $instructor->name }} ({{ $instructor->email }})
                </label>
            </div>
        @empty
            <p class="text-muted">No instructors available. Create an instructor account first.</p>
        @endforelse

        @error('instructor_ids.*')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        @if ($instructors->count())
            <button class="btn btn-primary mt-3">Save Instructors</button>
        @endif
    </form>
</x-layout>