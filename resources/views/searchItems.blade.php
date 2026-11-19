
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Search Items | Campus Lost & Found</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">
    <link rel="stylesheet" href="{{asset('frontend/style.css')}}">
</head>

<body class="bg-light site-image-background">

    <!-- Top navigation -->
    <nav class="navbar navbar-light bg-white border-bottom p-3">
        <div class="container-fluid">

            <a href="{{ auth()->check() ? route('dashboard') : route('searchItems') }}"
               class="navbar-brand fw-bold text-primary">
               <img src="{{asset('frontend/images/logo.jpg')}}" alt="" class="site-brand-logo">
                <i class="bi bi-geo-alt-fill"></i>
                Campus Lost & Found
            </a>

            <div class="d-flex align-items-center gap-2">
                <span class="d-none d-sm-inline">
                    @auth
                        Hello, {{ auth()->user()->name }}
                    @else
                        Browsing as a guest
                    @endauth
                </span>
                <i class="bi bi-person-circle fs-3 text-primary"></i>
            </div>

        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <aside class="col-md-3 col-lg-2 bg-primary min-vh-100 p-3">

                <h6 class="text-white mb-4">MAIN MENU</h6>

                <ul class="nav flex-column">

                    @auth
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link text-white">
                                <i class="bi bi-grid me-2"></i>
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('postItem') }}" class="nav-link text-white">
                                <i class="bi bi-plus-square me-2"></i>
                                Post Lost Item
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('myItems') }}" class="nav-link text-white">
                                <i class="bi bi-bag me-2"></i>
                                My Items
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('claimItems') }}" class="nav-link text-white">
                                <i class="bi bi-clipboard-check me-2"></i>
                                Claim Requests
                            </a>
                        </li>
                    @endauth

                    <li class="nav-item">
                        <a href="{{ route('searchItems') }}"
                           class="nav-link active bg-white text-primary rounded">
                            <i class="bi bi-search me-2"></i>
                            Search Items
                        </a>
                    </li>

                    @auth
                        <li class="nav-item">
                            <a href="{{ route('profile') }}" class="nav-link text-white">
                                <i class="bi bi-person me-2"></i>
                                My Profile
                            </a>
                        </li>

                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link text-white">
                                    <i class="bi bi-box-arrow-left me-2"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link text-white">Log in to report or claim an item</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="nav-link text-white">Create an account</a>
                        </li>
                    @endauth

                </ul>
            </aside>

            <!-- Main page -->
 <main class="col-md-9 col-lg-10 p-4">

                <!-- Page heading -->
 <div class="mb-4">
                    <h2 class="fw-bold text-primary">
                        Find Your Lost Item
                    </h2>

 <p class="text-secondary">
                        Search through items reported by students and staff.
                        You might find exactly what you are looking for.
                    </p>
                </div>

                <!-- Search and filters -->
 <div class="card shadow-sm mb-4">

  <div class="card-body p-4">

 <h5 class="mb-3">
                            <i class="bi bi-search text-primary"></i>
                            Search and Filter
                        </h5>

  <form action="{{ route('searchItems') }}" method="get">

 <div class="row g-3">

                                <!-- Search keyword -->
 <div class="col-lg-12">
                                    <label for="keyword" class="form-label">
                                        What are you looking for?
                                    </label>

<input type="search"
                                           id="keyword"
                                           name="keyword"
                                           class="form-control"
                                           placeholder="e.g. phone, black bag, student ID"
                                           value="{{ request('keyword') }}">
                                </div>

                                <!-- Category -->
 <div class="col-md-4">
                                    <label for="category" class="form-label">
                                        Category
                                    </label>

<select id="category"
                                            name="category"
                                            class="form-select">

<option value="">All Categories</option>
                                        @foreach (['Electronics', 'Bags', 'Books', 'Documents', 'IDs', 'Clothing', 'Jewellery', 'Keys', 'Wallets', 'Money', 'Other'] as $itemCategory)
                                            <option value="{{ $itemCategory }}" @selected(request('category') === $itemCategory)>{{ $itemCategory }}</option>
                                        @endforeach

</select>
                                </div>

                                <!-- Location -->
 <div class="col-md-4">
                                    <label for="location" class="form-label">
                                        Location
                                    </label>

 <input id="location"
        name="location"
        class="form-control"
        value="{{ request('location') }}"
        placeholder="Any location">

                                 </div>

                                 <!-- Report type -->
 <div class="col-md-4">
                                     <label for="type" class="form-label">
                                         Report Type
                                     </label>

 <select id="type"
                                             name="type"
                                             class="form-select">

  <option value="">Lost and Found</option>
                                         <option value="lost" @selected(request('type') === 'lost')>Lost Items</option>
                                         <option value="found" @selected(request('type') === 'found')>Found Items</option>

   </select>
                                 </div>

                                 <!-- Listing status -->
                                 <div class="col-md-4">
                                     <label for="status" class="form-label">Listing Status</label>
                                     <select id="status" name="status" class="form-select">
                                         <option value="active" @selected(request('status', 'active') === 'active')>Active listings</option>
                                         <option value="resolved" @selected(request('status') === 'resolved')>Resolved</option>
                                         <option value="claimed" @selected(request('status') === 'claimed')>Claimed</option>
                                     </select>
                                 </div>

                                <!-- Start date -->
 <div class="col-md-5">
                                    <label for="dateFrom" class="form-label">
                                        From Date
                                    </label>

<input type="date"
                                           id="dateFrom"
                                           name="dateFrom"
                                           class="form-control"
                                           value="{{ request('dateFrom') }}">
                                </div>

                                <!-- End date -->
 <div class="col-md-5">
                                    <label for="dateTo" class="form-label">
                                        To Date
                                    </label>

<input type="date"
                                           id="dateTo"
                                           name="dateTo"
                                           class="form-control"
                                           value="{{ request('dateTo') }}">
                                </div>

                                <!-- Search buttons -->
 <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit"
                                            class="btn btn-primary w-100">
                                        <i class="bi bi-search"></i>
                                        Search
                                    </button>
                                </div>

 </div>

 </form>

  </div>
     </div>

                <!-- Search results heading -->
 <div class="d-flex justify-content-between
                            align-items-center flex-wrap gap-2 mb-3">

 <div>
                        <h5 class="mb-1">Recently Reported Items</h5>

  <p class="text-secondary small mb-0">
                            Browse the items reported around campus.
                        </p>
                    </div>

  <div>
                        <label for="sortBy" class="form-label me-2">
                            Sort by:
                        </label>

 <select id="sortBy" name="sortBy"
                                class="form-select form-select-sm d-inline-block w-auto">

 <option value="recent" @selected(request('sortBy', 'recent') === 'recent')>Most Recent</option>
                            <option value="oldest" @selected(request('sortBy') === 'oldest')>Oldest First</option>
                            <option value="name" @selected(request('sortBy') === 'name')>Item Name</option>

 </select>
                    </div>

</div>

                <!-- Item results -->
                @if ($items->isEmpty())
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center text-secondary py-5">
                            <i class="bi bi-search display-5 d-block mb-3"></i>
                            <p class="mb-0">
                                @if (request()->hasAny(['keyword', 'category', 'location', 'type', 'dateFrom', 'dateTo']))
                                    No reports match those filters.
                                @else
                                    No lost or found items have been reported yet.
                                @endif
                            </p>
                        </div>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach ($items as $item)
                            <div class="col-12 col-md-6 col-xl-4">
                                <article class="card border-0 shadow-sm h-100">
                                    @if ($item['photo'])
                                        <img src="/storage/{{ ltrim($item['photo'], '/') }}"
                                             class="card-img-top"
                                             alt="{{ $item['name'] }}"
                                             style="height: 200px; object-fit: cover;">
                                    @endif
                                    <div class="card-body">
                                        <span class="badge {{ $item['type'] === 'Lost' ? 'text-bg-danger' : 'text-bg-success' }}">{{ $item['type'] }} item</span>
                                        <h3 class="h5 fw-bold mt-3">{{ $item['name'] }}</h3>
                                        <p class="text-secondary small">{{ $item['description'] }}</p>
                                        <p class="small mb-0">{{ $item['category'] }} · {{ $item['location'] }}</p>
                                        <p class="small text-secondary">{{ \Illuminate\Support\Carbon::parse($item['date'])->format('M j, Y') }}</p>
                                        <a href="{{ route('itemDetails', ['type' => strtolower($item['type']), 'id' => $item['id']]) }}"
                                           class="btn btn-outline-primary btn-sm">See details</a>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Footer -->
<footer class="text-center text-secondary small mt-4">
                    Campus Lost & Found Intelligent Hub
                    <br>
                    Helping students and staff reconnect with their belongings.
                </footer>

</main>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{asset('frontend/script.js')}}"></script>
</body>
</html>
