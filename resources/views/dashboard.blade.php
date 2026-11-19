
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

 <title>Dashboard | Campus Lost & Found</title>

    
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

    
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"     rel="stylesheet">
    <link rel="stylesheet" href="{{asset('frontend/style.css')}}">
</head>

<body class="bg-body-tertiary site-image-background">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid px-lg-4">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <img src="{{asset('frontend/images/logo.jpg')}}   " alt="" class="site-brand-logo">Campus Lost &amp; Found</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#studentNav" aria-controls="studentNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="studentNav">
                <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <span class="nav-link text-white-50">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm ms-lg-2"><i class="bi bi-box-arrow-left me-1"></i>Log out</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-0">
        <div class="row g-0">
            <aside class="student-dashboard-sidebar col-12 col-lg-2 bg-primary">
                <nav class="p-3 p-lg-4" aria-label="Main navigation">
                    <p class="small text-uppercase fw-semibold text-white-50 mb-3">Main menu</p>
                    <div class="nav nav-pills flex-row flex-wrap flex-lg-column gap-2">
                        <a href="{{ route('dashboard') }}" class="nav-link active bg-white text-primary" aria-current="page"><i class="bi bi-grid me-2"></i>Dashboard</a>
                        <a href="{{ route('postItem') }}" class="nav-link text-white"><i class="bi bi-plus-square me-2"></i>Post Lost Item</a>
                        <a href="{{ route('postFoundItems') }}" class="nav-link text-white"><i class="bi bi-bag-check me-2"></i>Post Found Item</a>
                        <a href="{{ route('myItems') }}" class="nav-link text-white"><i class="bi bi-bag me-2"></i>My Items</a>
                        <a href="{{ route('claimItems') }}" class="nav-link text-white"><i class="bi bi-clipboard-check me-2"></i>Claim Requests</a>
                        <a href="{{ route('searchItems') }}" class="nav-link text-white"><i class="bi bi-search me-2"></i>Search Items</a>
                        <a href="{{ route('profile') }}" class="nav-link text-white"><i class="bi bi-person-circle me-2"></i>{{ auth()->user()->name }}</a>
                    </div>
                </nav>
            </aside>

            <main class="col-12 col-lg-10 px-3 px-md-4 px-xl-5 py-4 py-lg-5">

             
   <!-- Welcome Section -->
   <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4 mb-lg-5">

                 
                 
   <div>
   <p class="text-primary text-uppercase fw-semibold small mb-1">Overview</p>
   <h1 class="h2 fw-bold mb-2">Welcome back</h1>

   <p class="text-secondary mb-0">
   Find your lost belongings or help someone
                         
                         
   recover theirs.
   </p>
   </div>

  <a href="{{ route('postItem') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-1"></i>
   Post an Item   </a>

  </div>

 <!-- Search Bar -->
  <div class="card border-0 shadow-sm mb-4">
   <div class="card-body p-3">
       <form action="{{ route('searchItems') }}" method="get">
           <div class="input-group">
               <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
               <input type="search"
                                       name="keyword"
                                       class="form-control"
                                       placeholder="Search for a phone, bag, ID card or other item...">
               <button type="submit" class="btn btn-primary">
                                    Search Items
                                </button>
           </div>
       </form>
  </div>
                </div>

                <!-- Summary Cards -->
  <div class="row g-3 mb-4">

                    <!-- Lost Items -->
  <div class="col-sm-6 col-xl-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">

  <div class="d-flex justify-content-between  align-items-center">

  <div>
                                        <p class="text-secondary mb-2">
                                            My Lost Items
                                        </p>

   <h2 class="fw-bold mb-0">{{ $lostCount }}</h2>
                                    </div>

 <i class="bi bi-bag-x fs-1 text-danger"></i>
                                </div>

 <a href="{{ route('myItems', ['type' => 'lost']) }}"
                                   class="text-decoration-none small">
                                    View my lost items
                                    <i class="bi bi-arrow-right"></i>
                                </a>

</div>
                        </div>
                    </div>

                    <!-- Found Items -->
 <div class="col-sm-6 col-xl-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
  <div class="d-flex justify-content-between
                                    align-items-center">

  <div>
                                        <p class="text-secondary mb-2">
                                            My Found Items
                                        </p>

  <h2 class="fw-bold mb-0">{{ $foundCount }}</h2>
                                    </div>

   <i class="bi bi-bag-check fs-1 text-success"></i>
                                </div>

  <a href="{{ route('myItems', ['type' => 'found']) }}"
                                   class="text-decoration-none small">
                                    View my found items
                                    <i class="bi bi-arrow-right"></i>
                                </a>

  </div>
                        </div>
                    </div>

                    <!-- Claim Requests -->
  <div class="col-sm-6 col-xl-4">   <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">

  <div class="d-flex justify-content-between
                                            align-items-center">

 <div>
                                        <p class="text-secondary mb-2">
                                            My Claim Requests
                                        </p>

 <h2 class="fw-bold mb-0">0</h2>
                                    </div>

  <i class="bi bi-clipboard-check fs-1 text-warning"></i>
                                </div>

 <a href="{{ route('claimItems') }}"
                                   class="text-decoration-none small">
                                    Track my claims
                                    <i class="bi bi-arrow-right"></i>
                                </a>

 </div>
                        </div>
                    </div>

 </div>

                <!-- Quick Actions -->
 <div class="card border-0 shadow-sm mb-4">

<div class="card-header bg-white p-3">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>

<div class="card-body">
                        <div class="row g-3">

  <div class="col-md-4">
                                <a href="{{ route('postItem') }}"
                                   class="btn btn-outline-primary w-100 p-3">
                                    <i class="bi bi-plus-circle fs-4 d-block mb-2"></i>
                                    Post Lost Item
                                </a>
                            </div>

 <div class="col-md-4">
                                <a href="{{ route('postFoundItems') }}"
                                   class="btn btn-outline-success w-100 p-3">
                                    <i class="bi bi-bag-check fs-4 d-block mb-2"></i>
                                    Post Found Item
                                </a>
                            </div>

 <div class="col-md-4">
                                <a href="{{ route('searchItems') }}"
                                   class="btn btn-outline-dark w-100 p-3">
                                    <i class="bi bi-search fs-4 d-block mb-2"></i>
                                    Search Items
                                </a>
                            </div>

</div>
                    </div>
                </div>

                <!-- Recently Reported Items -->
<div class="card border-0 shadow-sm mb-4">

 <div class="card-header bg-white p-3
                                d-flex justify-content-between
                                align-items-center">

 <h5 class="mb-0">Recently Reported Items</h5>

 <a href="{{ route('searchItems') }}"
                           class="text-decoration-none small">
                            View All
                        </a>

</div>

<div class="card-body">

  <div class="table-responsive">
                            <table class="table table-hover align-middle">

 <thead class="table-light">
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Location</th>
                                        <th>Date Reported</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

 <tbody>

 @forelse ($recentItems as $item)
     <tr>
         <td>{{ $item['name'] }}</td>
         <td>{{ $item['category'] }}</td>
         <td>{{ $item['location'] }}</td>
         <td>{{ \Illuminate\Support\Carbon::parse($item['date'])->format('M j, Y') }}</td>
         <td>
             <span class="badge {{ $item['type'] === 'Lost' ? 'text-bg-danger' : 'text-bg-success' }}">
                 {{ $item['type'] }} · {{ ucfirst($item['status']) }}
             </span>
         </td>
         <td>
             <a href="{{ route('searchItems', ['type' => strtolower($item['type']), 'keyword' => $item['name']]) }}"
                class="btn btn-sm btn-outline-primary">View</a>
         </td>
     </tr>
 @empty
     <tr>
         <td colspan="6" class="text-center text-secondary py-4">
             No active lost or found reports yet.
         </td>
     </tr>
 @endforelse

 </tbody>
                            </table>
                        </div>

 </div>
</div>

                <!-- Recent Claim Requests -->
<div class="card border-0 shadow-sm mb-4">

 <div class="card-header bg-white p-3
                                d-flex justify-content-between
                                align-items-center">

<h5 class="mb-0">Recent Claim Requests</h5>

 <a href="{{ route('claimItems') }}"
                           class="text-decoration-none small">
                            View All Claims
                        </a>

 </div>

 <div class="card-body">

 <div class="table-responsive">
         <table class="table table-hover align-middle">

 <thead class="table-light">
                                    <tr>
                                        <th>Claim ID</th>
                                        <th>Item Name</th>
                                        <th>Date Submitted</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

<tbody>

<tr>
                                        <td colspan="4" class="text-center text-secondary py-4">
                                            No claim requests have been submitted yet.
                                        </td>
                                    </tr>

</tbody>

 </table>
                        </div>

</div>
                </div>

                <!-- Helpful Reminder -->
<div class="alert alert-info">
                    <i class="bi bi-lightbulb me-2"></i>

<strong>Have you found something?</strong>
                    Help another student by reporting the item through
                    the Post Found Item page.
                </div>
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
