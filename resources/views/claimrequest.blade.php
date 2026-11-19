<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Claim Request - Lost and Found Hub</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light site-image-background">

    <!-- Navigation Bar -->
    
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">

<a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                    <img src="{{ asset('frontend/images/logo.jpg') }}" alt="" class="site-brand-logo">
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

        <!-- Page Heading -->
<div class="mb-4">
            <h2 class="fw-bold">Claim Request</h2>

<p class="text-muted">
                Complete the form below to request an item
                you believe belongs to you.
            </p>
        </div>

<div class="row g-4">

            <!-- Claim Form -->
<div class="col-lg-8">

<div class="card shadow-sm border-0">

 <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Item Claim Form</h5>
                    </div>

<div class="card-body p-4">

 <form action="{{ route('claimRequest.store') }}" method="post"
                              enctype="multipart/form-data"
                              autocomplete="off">
                            @csrf

                            <!-- Item Information -->
<h5 class="fw-bold mb-3">
                                Item Information
                            </h5>

 <div class="mb-3">
                                <label for="itemId" class="form-label">
                                    Item Reference Number
                                </label>

 <input type="text"
                                       class="form-control"
                                       id="itemId"
                                       name="itemId"
                                       placeholder="e.g. LF001"
                                       required>
                            </div>
<div class="mb-3">
                                <label for="itemName" class="form-label">
                                    Item Name
                                </label>

 <input type="text"
                                       class="form-control"
                                       id="itemName"
                                       name="itemName"
                                       placeholder="Enter the item name"
                                       required>
                            </div>

 <div class="mb-3">
                                <label for="itemCategory" class="form-label">
                                    Item Category
                                </label>

 <select class="form-select"
                                        id="itemCategory"
                                        name="itemCategory"
                                        required>

<option value="" selected disabled>
                                        Select item category
                                    </option>

<option value="electronics">
                                        Electronics
                                    </option>

<option value="bags">
                                        Bags and Accessories
                                    </option>

 <option value="documents">
                                        Documents and Identity Cards
                                    </option>

<option value="clothing">
                                        Clothing
                                    </option>

<option value="books">
                                        Books and Stationery
                                    </option>

<option value="keys">
                                        Keys
                                    </option>

<option value="other">
                                        Other
                                    </option>

</select>
                            </div>

 <hr class="my-4">

                            <!-- Claimant Information -->
<h5 class="fw-bold mb-3">
                                Claimant Information
                            </h5>

<div class="mb-3">
                                <label for="fullName" class="form-label">
                                    Full Name
                                </label>

 <input type="text"
                                       class="form-control"
                                       id="fullName"
                                       name="fullName"
                                       placeholder="Enter your full name"
                                       autocomplete="name"
                                       required>
                            </div>

 <div class="row">

<div class="col-md-6 mb-3">
                                    <label for="studentId" class="form-label">
                                        Student or Staff ID
                                    </label>

 <input type="text"
                                           class="form-control"
                                           id="studentId"
                                           name="studentId"
                                           placeholder="Enter your ID">
                                </div>

<div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        Phone Number
                                    </label>
                                     <input type="tel"
                                           class="form-control"
                                           id="phone"
                                           name="phone"
                                           placeholder="e.g. 07XXXXXXXX"
                                           autocomplete="tel"
                                           required>
                                </div>

                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    Email Address
                                </label>

                                <input type="email"
                                       class="form-control"
                                       id="email"
                                       name="email"
                                       placeholder="Enter your email address"
                                       autocomplete="email"
                                       required>
                            </div>

                            <hr class="my-4">

                            <!-- Proof of Ownership -->
                            <h5 class="fw-bold mb-3">
                                Proof of Ownership
                            </h5>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    Describe the Item
                                </label>

                                <textarea class="form-control"
                                          id="description"
                                          name="description"
                                          rows="4"
                                          placeholder="Describe unique features that can help verify ownership..."
                                          required></textarea>

                                <div class="form-text">
                                    Include identifying details that are not
                                    already visible in the public listing.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="lostLocation" class="form-label">
                                    Where Did You Lose the Item?
                                </label>

                                <input type="text"
                                       class="form-control"
                                       id="lostLocation"
                                       name="lostLocation"
                                       placeholder="Enter the location">
                            </div>

                            <div class="mb-3">
                                <label for="proofFile" class="form-label">
                                    Upload Supporting Evidence (Optional)
                                </label>

                                <input type="file"
                                       class="form-control"
                                       id="proofFile"
                                       name="proofFile"
                                       accept="image/*,.pdf">

                                <div class="form-text">
                                    You may upload a suitable photograph
                                    or document. Do not upload passwords,
                                    PINs, or unnecessary sensitive information.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="additionalInfo" class="form-label">
                                    Additional Information
                                </label>

                                <textarea class="form-control"
                                          id="additionalInfo"
                                          name="additionalInfo"
                                          rows="3"
                                          placeholder="Provide any other information that may help verify your claim."></textarea>
                            </div>

                            <hr class="my-4">

                            <!-- Declaration -->
                            <div class="form-check mb-4">

                                <input class="form-check-input"
                                       type="checkbox"
                                       id="declaration"
                                       name="declaration"
                                       value="confirmed"
                                       required>

                                <label class="form-check-label"
                                       for="declaration">
                                    I confirm that the information provided
                                    is true and that I have a genuine reason
                                    to claim this item.
                                </label>

                            </div>

                            <!-- Form Buttons -->
                            <div class="d-flex flex-wrap gap-2">

                                <button type="submit"
                                        class="btn btn-primary">
                                    Submit Claim Request
                                </button>

                                <button type="reset"
                                        class="btn btn-outline-secondary">
                                    Clear Form
                                </button>

                                <a href="item-details.html"
                                   class="btn btn-outline-danger">
                                    Cancel
                                </a>

                            </div>

                        </form>

                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">

                <!-- Instructions -->
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">How to Claim an Item</h5>
                    </div>

                    <div class="card-body">

                        <ol class="ps-3 mb-0">

                            <li class="mb-2">
                                Enter the item's reference number and name.
                            </li>

                            <li class="mb-2">
                                Provide your correct contact information.
                            </li>

                            <li class="mb-2">
                                Describe the item and provide evidence
                                that can help verify ownership.
                            </li>

                            <li class="mb-2">
                                Submit your claim for review.
                            </li>

                            <li>
                                Wait for confirmation from the authorised
                                person before collecting the item.
                            </li>

                        </ol>

                    </div>
                </div>

                <!-- Claim Status -->
                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Claim Status</h5>
                    </div>

                    <div class="card-body">

                        <span class="badge bg-warning text-dark fs-6">
                            No Request Yet
                        </span>

                        <p class="text-muted mt-3 mb-0">
                            You have not submitted a claim yet. Once you
                            send this form, your request will appear as Pending
                            while an administrator reviews it.
                        </p>

                    </div>
                </div>

                <!-- Safety Notice -->
                <div class="alert alert-info" role="alert">

                    <h6 class="fw-bold">Safety Notice</h6>

                    <p class="mb-0">
                        Your claim must be verified before an item
                        is handed over. Do not share your password,
                        bank PIN, or other confidential information.
                    </p>

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
