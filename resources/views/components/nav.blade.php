<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container-fluid px-4">
        @auth
        <span class="fw-bold">{{ auth()->user()->name }}</span>

        <ul class="navbar-nav flex-row flex-wrap gap-3 ms-auto  align-items-center">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
            </li>

            @if (auth()->user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.index') }}">Manage Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('courses.index') }}">Manage Courses</a>
            </li>
            @endif
            @if (auth()->user()->role === 'instructor')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('courses.index') }}">My Courses</a>
            </li>
            @endif
            @if (auth()->user()->role === 'student')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('courses.mine') }}">My Courses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('exams.available') }}">My Exams</a>
            </li>
            @endif

            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            </li>
        </ul>
        @endauth
    </div>
</nav>