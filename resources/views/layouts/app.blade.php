<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel Quiz')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-blue-600">Laravel Quiz</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <span class="text-gray-700">{{ auth()->user()->name }}</span>
                        @if(auth()->user()->isAdmin())
                            <a href="/admin" class="text-blue-600 hover:text-blue-800">Admin Panel</a>
                        @endif
                        <a href="/" class="text-blue-600 hover:text-blue-800">Quizzes</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-6">
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>
