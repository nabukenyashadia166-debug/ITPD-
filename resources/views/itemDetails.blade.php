<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Item Details - Lost and Found Hub</title>

    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link rel="stylesheet" href="{{asset('frontend/style.css')}}">
</head>

<body class="bg-light site-image-background">

    <!-- Navigation Bar -->
 <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">

 <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
    <img src="{{asset('frontend/images/logo.jpg')}}" alt="" class="site-brand-logo">
                Lost & Found Hub
            </a>

   <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarMenu"
                    aria-controls="navbarMenu"
                    aria-expanded="false"
                    aria-label="Toggle navigation">

  <span class="navbar-toggler-icon"></span>
            </button>

  <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto">

 <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            Dashboard
                        </a>
                    </li>

 <li class="nav-item">
                        <a class="nav-link" href="{{ route('postItem') }}">
                            Post Item
                        </a>
                    </li>

 <li class="nav-item">
                        <a class="nav-link" href="{{ route('searchItems') }}">
                            Search Items
                        </a>
                    </li>

   <li class="nav-item">
                        <a class="nav-link" href="{{ route('myItems') }}">
                            My Items
                        </a>
                    </li>

   <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile') }}">
                            Profile
                        </a>
                    </li>

   </ul>
            </div>

   </div>
</nav>

    <!-- Main Content -->
 <div class="container py-5">

<div class="row justify-content-center py-4 py-lg-5">
            <div class="col-12 col-lg-8 col-xl-7">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4 p-md-5">
                        @isset($item)
                            <span class="badge {{ $item['type'] === 'Lost' ? 'text-bg-danger' : 'text-bg-success' }} mb-3">{{ $item['type'] }} item</span>
                            <h1 class="h3 fw-bold mb-3">{{ $item['name'] }}</h1>
                            @if ($item['photo'])
                                <img src="/storage/{{ ltrim($item['photo'], '/') }}"
                                     alt="Photo of {{ $item['name'] }}"
                                     class="img-fluid rounded mb-4"
                                     style="max-height: 420px; object-fit: contain;">
                            @else
                                <p class="text-secondary">No photo was provided for this report.</p>
                            @endif
                            <p class="text-secondary mb-4">{{ $item['description'] }}</p>
                            <dl class="row text-start mx-auto mb-4" style="max-width: 520px;">
                                <dt class="col-sm-4">Reference</dt>
                                <dd class="col-sm-8">{{ strtoupper(substr($item['type'], 0, 1)) }}-{{ $item['id'] }}</dd>
                                <dt class="col-sm-4">Category</dt>
                                <dd class="col-sm-8">{{ $item['category'] }}</dd>
                                <dt class="col-sm-4">Location</dt>
                                <dd class="col-sm-8">{{ $item['location'] }}</dd>
                                <dt class="col-sm-4">Date</dt>
                                <dd class="col-sm-8">{{ \Illuminate\Support\Carbon::parse($item['date'])->format('M j, Y') }}</dd>
                                <dt class="col-sm-4">Status</dt>
                                <dd class="col-sm-8">{{ ucfirst($item['status']) }}</dd>
                            </dl>
                            <a href="{{ route('searchItems') }}" class="btn btn-outline-primary px-4">Back to items</a>
                        @else
                        <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-4" style="width: 5rem; height: 5rem;" aria-hidden="true">
                            <span class="fs-2">🔎</span>
                        </div>
                        <p class="text-uppercase text-primary fw-semibold small mb-2">Item details</p>
                        <h1 id="emptyHeading" class="h3 fw-bold mb-3">Choose an item to see its details</h1>
                        <p class="text-secondary mb-4">
                            There isn’t an item selected yet. Browse the listings and open an item to see its description, location, and status here.
                        </p>
                        <a href="{{ route('searchItems') }}" class="btn btn-primary px-4">Browse items</a>
                        @endisset
                    </div>
                </div>
            </div>
        </div>

  </div>

    <!-- Footer -->
<footer class="bg-primary text-white text-center py-3 mt-5">

 <p class="mb-0">
            &copy; 2026 Campus Lost & Found Hub.
            All rights reserved.
        </p>

   </footer>

    <!-- Bootstrap JavaScript Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="{{asset('frontend/script.js')}}"></script>
</body>
</html>
