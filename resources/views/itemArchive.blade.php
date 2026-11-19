<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Archive | Campus Lost &amp; Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('frontend/style.css')}}">
</head>
<body class="bg-body-tertiary site-image-background">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <img src="{{asset('frontend/images/logo.jpg')}}" alt="" class="site-brand-logo">Campus Lost &amp; Found</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('myItems') }}">My items</a></li>
                    <li class="nav-item">                    <a class="nav-link" href="{{ route('claimItems') }}">Claims</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="{{ route('itemArchive') }}">Item archive</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}">Profile</a></li>
                </ul>
            </div>
        </div>
    </nav>

  <main class="container py-4 py-lg-5">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <p class="text-primary text-uppercase fw-semibold small mb-1">Records</p>
                <h1 class="h2 fw-bold mb-2">Item archive</h1>
                <p class="text-secondary mb-0">Items that have been returned, closed, or are no longer active will be kept here.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">Back to dashboard</a>
        </div>

  <section class="row g-3 mb-4" aria-label="Archive summary">
            <div class="col-12 col-sm-4"><div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <p class="small text-secondary mb-2">Archived items</p>
                    <p class="h2 fw-bold mb-1">{{ $archivedCount }}</p>
                    <p class="small text-secondary mb-0">Inactive records retained for reference</p>
                </div>
            </div>
            </div>
            <div class="col-12 col-sm-4">
            <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
            <p class="small text-secondary mb-2">Returned</p>
            <p class="h2 fw-bold text-success mb-1">{{ $returnedCount }}</p>
            <p class="small text-secondary mb-0">Resolved or returned reports</p>
            </div>
            </div>
            </div>
            <div class="col-12 col-sm-4">
            <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
            <p class="small text-secondary mb-2">Closed or expired</p>
            <p class="h2 fw-bold text-secondary mb-1">{{ $closedCount }}</p>
            <p class="small text-secondary mb-0">Closed, claimed or expired reports</p>
        </div>
            </div>
            </div>
        </section>

<section class="card border-0 shadow-sm mb-4" aria-labelledby="searchHeading">
            <div class="card-body p-4">
                <h2 id="searchHeading" class="h5 fw-bold mb-3">Find an archived item</h2>
                <form action="{{ route('itemArchive') }}" method="get">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-5">
                            <label for="searchItem" class="form-label">Name or reference</label>
                            <input type="search" class="form-control" id="searchItem" name="q" value="{{ $keyword }}" placeholder="For example, keys or LF001">
                        </div>
  <div class="col-12 col-md-3">
                            <label for="itemType" class="form-label">Item type</label>
                            <select class="form-select" id="itemType" name="type">
                                <option value="">All types</option>
                                <option value="lost" @selected($selectedType === 'lost')>Lost</option>
                                <option value="found" @selected($selectedType === 'found')>Found</option></select>
                        </div>
   <div class="col-12 col-md-3">
                            <label for="archiveStatus" class="form-label">Status</label>
                            <select class="form-select" id="archiveStatus" name="status">
                                <option value="">All statuses</option>
                                <option value="resolved" @selected($selectedStatus === 'resolved')>Resolved</option>
                                <option value="returned" @selected($selectedStatus === 'returned')>Returned</option>
                                <option value="claimed" @selected($selectedStatus === 'claimed')>Claimed</option>
                                <option value="expired" @selected($selectedStatus === 'expired')>Expired</option>
                                <option value="closed" @selected($selectedStatus === 'closed')>Closed</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-1 d-grid"><button type="submit" class="btn btn-primary">Search</button></div>
                    </div>
                </form>
            </div>
        </section>

 <section class="card border-0 shadow-sm" aria-labelledby="archiveHeading">
            <div class="card-header bg-white border-0 px-4 pt-4 pb-2">
                <h2 id="archiveHeading" class="h5 fw-bold mb-1">Archived records</h2>
                <p class="small text-secondary mb-0">Resolved, closed, expired, returned and claimed reports stay here for reference.</p>
            </div>
   <div class="card-body p-4">
                @if ($items->isEmpty())
                    <div class="text-center rounded-3 bg-body-tertiary px-3 py-5">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary mb-3" style="width: 3.5rem; height: 3.5rem;" aria-hidden="true"><span class="fs-3">&#128203;</span></div>
                        <h3 class="h6 fw-bold">No archived items found</h3>
                        <p class="text-secondary small mb-0">Items appear here after their status changes from active.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Date reported</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td><span class="badge {{ $item['type'] === 'lost' ? 'text-bg-danger' : 'text-bg-success' }}">{{ ucfirst($item['type']) }}</span></td>
                                        <td>{{ $item['category'] }}</td>
                                        <td>{{ $item['location'] }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item['date'])->format('M j, Y') }}</td>
                                        <td><span class="badge text-bg-secondary">{{ ucfirst($item['status']) }}</span></td>
                                        <td><a href="{{ route('itemDetails', ['type' => $item['type'], 'id' => $item['id']]) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>

   <div class="alert alert-light border mt-4 mb-0" role="note">
            <p class="fw-semibold mb-1">About the archive</p>
            <p class="small text-secondary mb-0">Changing an item's status to anything other than active puts it in this archive. Reports are not automatically deleted or expired; they remain stored until an authorized user changes their status.</p>
        </div>
    </main>

  <footer class="text-center text-secondary small py-4">
        <p class="mb-0">&copy; 2026 Campus Lost &amp; Found</p>
    </footer>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('frontend/script.js')}}"></script>
</body>
</html>
