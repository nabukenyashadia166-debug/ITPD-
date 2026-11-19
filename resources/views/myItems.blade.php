
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Items - Campus Lost & Found Hub</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('frontend/style.css')}}">
</head>

<body class="bg-light site-image-background">

    <!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <img src="{{asset('frontend/images/logo.jpg')}}" alt="" class="site-brand-logo">
                <i class="bi bi-search"></i> Campus Lost & Found
            </a>
 <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarMenu"
                    aria-controls="navbarMenu" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

 <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('searchItems') }}">Search Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('myItems') }}">My Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile') }}">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

 <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
<aside class="col-md-3 col-lg-2 bg-white border-end min-vh-100 p-3">
                <h6 class="text-secondary mb-3">MAIN MENU</h6>

<ul class="nav nav-pills flex-column gap-2">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link text-dark">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>

 <li class="nav-item">
                        <a href="{{ route('postItem') }}" class="nav-link text-dark">
                            <i class="bi bi-plus-circle me-2"></i> Post Lost Item
                        </a>
                    </li>

 <li class="nav-item">
                        <a href="{{ route('postItem') }}" class="nav-link text-dark">
                            <i class="bi bi-box-seam me-2"></i> Post Found Item
                        </a>
                    </li>

 <li class="nav-item">
                        <a href="{{ route('myItems') }}" class="nav-link active">
                            <i class="bi bi-collection me-2"></i> My Items
                        </a>
                    </li>

<li class="nav-item">
                        <a href="{{ route('searchItems') }}" class="nav-link text-dark">
                            <i class="bi bi-search me-2"></i> Search Items
                        </a>
                    </li>

 <li class="nav-item">
                        <a href="{{ route('claimItems') }}" class="nav-link text-dark">
                            <i class="bi bi-clipboard-check me-2"></i> My Claims
                        </a>
                    </li>

 <li class="nav-item">
                        <a href="{{ route('profile') }}" class="nav-link text-dark">
                            <i class="bi bi-person-circle me-2"></i> My Profile
                        </a>
                    </li>
                </ul>

<hr>

<div class="alert alert-info small">
                    <i class="bi bi-info-circle me-1"></i>
                    Keep your item details updated so other students can identify them.
                </div>
            </aside>

            
 <main class="col-md-9 col-lg-10 p-4">

                <!-- Page Heading -->
                 @if (session('success'))
                     <div class="alert alert-success" role="status">{{ session('success') }}</div>
                 @endif
 <div class="d-flex flex-column flex-md-row justify-content-between
                            align-items-md-center gap-3 mb-4">
                    <div>
                        <h2 class="fw-bold">My Items</h2>
                        <p class="text-secondary mb-0">
                            Manage the lost and found items you have reported.
                        </p>
                    </div>

 <div class="d-flex gap-2">
                        <a href="{{ route('postItem') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Report Lost Item
                        </a>
                        <a href="{{ route('postFoundItems') }}" class="btn btn-outline-primary">
                            <i class="bi bi-box-seam me-1"></i> Report Found Item
                        </a>
                    </div>
                </div>

                <!-- Summary Cards -->
 <div class="row g-3 mb-4">

<div class="col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-primary-subtle text-primary rounded p-3 me-3">
                                    <i class="bi bi-collection fs-3"></i>
                                </div>
                                <div>
                                    <p class="text-secondary mb-1">Total Items</p>
                                    <h3 class="fw-bold mb-0">{{ $lostCount + $foundCount }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

<div class="col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-warning-subtle text-warning rounded p-3 me-3">
                                    <i class="bi bi-hourglass-split fs-3"></i>
                                </div>
                                <div>
                                    <p class="text-secondary mb-1">Lost Reports</p>
                                    <h3 class="fw-bold mb-0">{{ $lostCount }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

<div class="col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-success-subtle text-success rounded p-3 me-3">
                                    <i class="bi bi-check-circle fs-3"></i>
                                </div>
                                <div>
                                    <p class="text-secondary mb-1">Found Reports</p>
                                    <h3 class="fw-bold mb-0">{{ $foundCount }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

<div class="col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-danger-subtle text-danger rounded p-3 me-3">
                                    <i class="bi bi-search fs-3"></i>
                                </div>
                                <div>
                                    <p class="text-secondary mb-1">Active Reports</p>
                                    <h3 class="fw-bold mb-0">{{ $activeCount }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Search and Filter -->
<div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <form action="{{ route('myItems') }}" method="get">
                            <div class="row g-3 align-items-end">

 <div class="col-md-5">
                                    <label for="keyword" class="form-label">Search my items</label>
                                    <input type="search" class="form-control" id="keyword"
                                           name="keyword"
                                           placeholder="Enter item name or description"
                                           value="{{ request('keyword') }}">
                                </div>

 <div class="col-md-3">
                                    <label for="itemType" class="form-label">Item Type</label>
                                    <select class="form-select" id="itemType" name="type">
                                        <option value="">All Items</option>
                                        <option value="lost" @selected(request('type') === 'lost')>Lost Items</option>
                                        <option value="found" @selected(request('type') === 'found')>Found Items</option>
                                    </select>
                                </div>

 <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">All Statuses</option>
                                        <option value="active" @selected(request('status') === 'active')>Active</option>
                                        <option value="resolved" @selected(request('status') === 'resolved')>Resolved</option>
                                    </select>
                                </div>

<div class="col-md-1 d-grid">
                                    <button type="submit" class="btn btn-primary"
                                            aria-label="Search items">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>

</div>
                        </form>
                    </div>
                </div>

                <!-- Items Table -->
 <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">My Reported Items</h5>
                    </div>

<div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Item</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Date Reported</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ $item['name'] }}</td>
                                            <td><span class="badge {{ $item['type'] === 'Lost' ? 'text-bg-danger' : 'text-bg-success' }}">{{ $item['type'] }}</span></td>
                                            <td>{{ \Illuminate\Support\Carbon::parse($item['date'])->format('M j, Y') }}</td>
                                            <td>{{ $item['location'] }}</td>
                                            <td><span class="badge text-bg-primary">{{ ucfirst($item['status']) }}</span></td>
                                            <td>
                                                <a href="{{ route('itemDetails', ['type' => strtolower($item['type']), 'id' => $item['id']]) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                @if ($item['status'] !== 'claimed')
                                                    <form action="{{ route('items.status.update', ['type' => strtolower($item['type']), 'id' => $item['id']]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="{{ $item['status'] === 'active' ? 'resolved' : 'active' }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                            {{ $item['status'] === 'active' ? 'Mark resolved' : 'Reopen' }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-secondary py-4">
                                                @if (request()->hasAny(['keyword', 'type', 'status']))
                                                    No reports match those filters.
                                                @else
                                                    You have not reported any lost or found items yet.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Helpful Information -->
                <div class="alert alert-info border-0">
                    <h6 class="fw-bold">
                        <i class="bi bi-lightbulb me-2"></i>Helpful Tip
                    </h6>
                    <p class="mb-0">
                        If you recover a lost item or return an item to its owner,
                        update its status so other students know it has been resolved.
                    </p>
                </div>

 </main>
        </div>
    </div>

    <!-- Footer -->
 <footer class="bg-white border-top text-center text-secondary py-3">
        <p class="mb-0">
            &copy; 2026 Campus Lost & Found Intelligent Hub.
            Helping students reconnect with their belongings.
        </p>
    </footer>

    <!-- Bootstrap JavaScript Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{asset('frontend/script.js')}}"></script>
</body>
</html>
```
