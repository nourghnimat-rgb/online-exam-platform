<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title ??'Exam platform'}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .app-bg {
            background-color: #e0f2fe;
        }

        .auth-bg {
            background: url('{{ asset("images/auth-bg.png") }}') center / cover no-repeat fixed;
        }

        .auth-card {
            margin-top: 7rem;
        }
    </style>

</head>

<body class="{{ request()->routeIs('login', 'register') ? 'auth-bg' : 'app-bg' }}">
    @auth
    <x-nav />
    @endauth
    <main class="container py-5">
        {{$slot}}
    </main>

</body>

</html>