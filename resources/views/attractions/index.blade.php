<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attractions List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">  
</head>
<body>
    <div class="container mt-4">
        <!-- Navigation bar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <a class="navbar-brand" href="#">MyApp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/attractions') }}">Attractions List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/comments/by-rating') }}">Comments by Rating</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/comments/count/1') }}">Comment Count for Attraction 1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/attractions/by-species/1') }}">Attractions by Species 1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/attractions/average-rating/1') }}">Average Rating by Species 1</a>
                    </li>
                </ul>

                <!-- Authentication Links -->
                <ul class="navbar-nav ms-auto">
                    @Auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>

        <!-- Attractions List Table -->
        <h1>Attractions List</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Average Rating</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attractions as $attraction)
                    <tr>
                        <td>{{ $attraction->name }}</td>
                        <td>{{ number_format($attraction->average_rating, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFOw5nYNeT5zOfwUVaKPSzP6fXJ6c6G6oC5x1RIqUe0V7G3OeO0z5g" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-1sK8GGjO6tuTPE5c+HcQ1Q3mZj5xdXMIfb8c1B2UOW9WmL74q1Y+h8t5Tu2N2KoO" crossorigin="anonymous"></script>
</body>
</html>

