<x-layout title="Add Class">
    <h1 class="h3 mb-3">Add Class</h1>

    <form method="POST" action="{{ route('courses.store') }}" class="col-md-6">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" rows="3"
                class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary">Create</button>
        <a href="{{ route('courses.index') }}" class="btn btn-link">Cancel</a>
    </form>
</x-layout>