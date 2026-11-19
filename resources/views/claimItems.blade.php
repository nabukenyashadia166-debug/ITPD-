
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Claim Items | Campus Lost & Found</title>

    <!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">
    <link rel="stylesheet" href="{{asset('frontend/style.css')}}">
</head>

<body class="bg-light site-image-background">

    <!-- Top Navigation -->
<nav class="navbar navbar-light bg-white border-bottom p-3">
 <div class="container-fluid">

 <a href="{{ route('dashboard') }}"
               class="navbar-brand fw-bold text-primary">
               <img src="{{asset('frontend/images/logo.jpg')}}" alt="" class="site-brand-logo">
                <i class="bi bi-geo-alt-fill"></i>
                Campus Lost & Found
            </a>

 <div class="d-flex align-items-center gap-2">
                <span class="d-none d-sm-inline">Welcome</span>
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
                        <a href="{{ route('claimItems') }}"
                           class="nav-link active bg-white text-primary rounded">
                            <i class="bi bi-clipboard-check me-2"></i>
                            Claim Requests
                        </a>
                    </li>

 <li class="nav-item">
                        <a href="{{ route('searchItems') }}" class="nav-link text-white">
                            <i class="bi bi-search me-2"></i>
                            Search Items
                        </a>
                    </li>

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

                </ul>
            </aside>

            <!-- Main Content -->
<main class="col-md-9 col-lg-10 p-4">

                <!-- Page Heading -->
<div class="mb-4">
                    <h2 class="fw-bold text-primary">
                        Claim Your Item
                    </h2>

 <p class="text-secondary">
                        Found something that belongs to you?
                        Submit a claim and provide information to help
                        us verify that you are the rightful owner.
                    </p>
                </div>

                <!-- Information Alert -->
<div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Please provide accurate information. An administrator
                    will review your claim before an item is released.
                </div>

<div class="row g-4">

                    <!-- Claim Form -->
<div class="col-lg-7">

<div class="card shadow-sm h-100">

 <div class="card-header bg-white p-3">
                                <h5 class="mb-1">
                                    <i class="bi bi-clipboard-check text-primary"></i>
                                    Claim Request Form
                                </h5>

 <p class="text-secondary small mb-0">
                                    Complete the details below to claim an item.
                                </p>
                            </div>

<div class="card-body p-4">

  <form action="{{ route('claim.store') }}" method="post"
                                      enctype="multipart/form-data"
                                      autocomplete="off">
                                    @csrf

                                    <!-- Select Item -->
 <div class="mb-3">
                                        <label for="itemName" class="form-label">
                                            Select Found Item *
                                        </label>

 <select id="itemName"
                                                name="found_item_id"
                                                class="form-select"
                                                required>

                                            @if ($foundItems->isEmpty())
                                                <option value="" selected disabled>No found items are available yet</option>
                                            @else
                                                <option value="" selected disabled>Select a found item</option>
                                            @endif
                                            @foreach ($foundItems as $foundItem)
                                                <option value="{{ $foundItem->id }}" @selected(old('found_item_id') == $foundItem->id)>{{ $foundItem->item_name }} ({{ $foundItem->category }})</option>
                                            @endforeach
</select>
                                    </div>

                                    <!-- Claimant Name -->
<div class="mb-3">
                                        <label for="fullName" class="form-label">
                                            Full Name *
                                        </label>

 <input type="text"
                                               id="fullName"
                                               name="fullName"
                                               class="form-control"
                                               placeholder="Enter your full name"
                                               required>
                                    </div>

                                    <!-- Student Number -->
 <div class="mb-3">
                                        <label for="studentNumber" class="form-label">
                                            Student or Staff ID *
                                        </label>

<input type="text"
                                               id="studentNumber"
                                               name="studentNumber"
                                               class="form-control"
                                               placeholder="Enter your registration or staff number"
                                               required>
                                    </div>

                                    <!-- Contact -->
<div class="mb-3">
                                        <label for="contact" class="form-label">
                                            Phone Number or Email *
                                        </label>

<input type="text"
                                               id="contact"
                                               name="contact"
                                               class="form-control"
                                               placeholder="How can we contact you?"
                                               required>
                                    </div>

                                    <!-- Proof of Ownership -->
<div class="mb-3">
                                        <label for="proof" class="form-label">
                                            Proof of Ownership *
                                        </label>

<input type="file"
                                               id="proof"
                                               name="proof"
                                               class="form-control"
                                               accept="image/*,.pdf"
                                               required>

<div class="form-text">
                                            Upload evidence such as a receipt,
                                            a previous photo of the item, or
                                            another relevant document.
                                        </div>
                                    </div>

                                    <!-- Additional Details -->
 <div class="mb-3">
                                        <label for="description" class="form-label">
                                            Additional Information *
                                        </label>

  <textarea id="description"
                                                  name="description"
                                                  class="form-control"
                                                  rows="4"
                                                  placeholder="Describe unique features, contents, marks or other details that prove ownership."
                                                  required></textarea>
                                    </div>

                                    <!-- Declaration -->
 <div class="form-check mb-4">
                                        <input type="checkbox"
                                               id="declaration"
                                               name="declaration"
                                               class="form-check-input"
                                               required>

 <label for="declaration"
                                               class="form-check-label">
                                            I confirm that the information I have
                                            provided is correct.
                                        </label>
                                    </div>

                                    <!-- Form Buttons -->
<div class="d-flex justify-content-between
                                                flex-wrap gap-2">

 <button type="reset"
                                                class="btn btn-outline-secondary">
                                            Clear Form
                                        </button>

<button type="submit"
                                                class="btn btn-primary">
                                            <i class="bi bi-send me-1"></i>
                                            Submit Claim
                                        </button>

</div>

 </form>

 </div>
 </div>
                    </div>

                    <!-- Claim Instructions -->
 <div class="col-lg-5">

<div class="card shadow-sm mb-4">

<div class="card-header bg-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-info-circle text-primary"></i>
                                    How to Claim an Item
                                </h5>
                            </div>

<div class="card-body">

<div class="d-flex gap-3 mb-4">
                                    <i class="bi bi-search fs-3 text-primary"></i>

 <div>
                                        <h6>1. Find your item</h6>

 <p class="text-secondary small mb-0">
                                            Search for the item you lost and
                                            select the matching found item.
                                        </p>
                                    </div>
                                </div>

 <div class="d-flex gap-3 mb-4">
                                    <i class="bi bi-file-earmark-check fs-3 text-primary"></i>

 <div>
                                        <h6>2. Provide proof</h6>

 <p class="text-secondary small mb-0">
                                            Give enough information to show
                                            that the item belongs to you.
                                        </p>
                                    </div>
                                </div>

<div class="d-flex gap-3 mb-4">
                                    <i class="bi bi-shield-check fs-3 text-primary"></i>

<div>
                                        <h6>3. Wait for verification</h6>

<p class="text-secondary small mb-0">
                                            An administrator will examine your
                                            evidence and review your request.
                                        </p>
                                    </div>
                                </div>

 <div class="d-flex gap-3">
                                    <i class="bi bi-check-circle fs-3 text-success"></i>

<div>
 <h6>4. Collect your item</h6>

<p class="text-secondary small mb-0">
                                            If approved, follow the instructions
                                            provided by the authorised staff.
                                        </p>
                                    </div>
                                </div>

</div>
                        </div>

                        <!-- Important Notice -->
 <div class="card shadow-sm">

 <div class="card-body">
                                <h6>
                                    <i class="bi bi-shield-lock text-primary"></i>
                                    Your Information Is Important
                                </h6>

 <p class="text-secondary small mb-0">
                                    Your claim details should only be used
                                    for ownership verification. Avoid uploading
                                    passwords or unnecessary sensitive documents.
                                </p>
                            </div>

                        </div>

                    </div>
                </div>

                <!-- Previous Claims -->
 <div class="card shadow-sm mt-4">

 <div class="card-header bg-white p-3">
                        <h5 class="mb-1">
                            <i class="bi bi-clock-history text-primary"></i>
                            My Previous Claims
                        </h5>

<p class="text-secondary small mb-0">
                            Check the progress of your submitted requests.
                        </p>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>

 <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary py-4">
                                            No claim requests have been submitted yet.
                                        </td>
                                    </tr>

 </tbody>

</table>

 </div>
 </div>
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

<script src="{{ asset('frontend/script.js') }}"></script>
</body>
</html>
