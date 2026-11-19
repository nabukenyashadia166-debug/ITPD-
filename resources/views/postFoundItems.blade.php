
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Found Item | Lost and Found System</title>

    <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link rel="stylesheet" href="{{asset('frontend/style.css')}}">
</head>

<body class="bg-light site-image-background">

    <!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <img src="{{asset('frontend/images/logo.jpg')}}" alt="" class="site-brand-logo">
                Campus Lost & Found
            </a>

  <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarMenu">
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
                        <a class="nav-link active" href="{{ route('postFoundItems') }}">Post Found Item</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('myItems') }}">My Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile') }}">Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Heading -->
  <div class="container mt-4 mb-5">

   <div class="text-center mb-4">
            <h2 class="fw-bold">Report a Found Item</h2>
            <p class="text-muted">
                Found something on campus? Share the details to help
                the rightful owner find it.
            </p>
        </div>

        <!-- Information Message -->
  <div class="alert alert-info">
            <strong>Note:</strong> Please provide accurate information
            about the item and where you found it.
        </div>

        <!-- Form Card -->
<div class="card shadow-sm border-0 mx-auto" style="max-width: 850px;">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0">Found Item Details</h5>
            </div>

 <div class="card-body p-4">

  @include('partials.item-matches', ['matchType' => 'lost'])

  @if (session('success'))
      <div class="alert alert-success" role="alert">{{ session('success') }}</div>
  @endif
  @if ($errors->any())
      <div class="alert alert-danger" role="alert">
          <ul class="mb-0">
            
@foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  <form action="{{ route('postFoundItems.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Item Name -->
   <div class="mb-3">
                        <label for="itemName" class="form-label">
                            Item Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control"
                               id="itemName" name="item_name"
                               placeholder="e.g. Black school bag"
                               value="{{ old('item_name') }}"
                               required>
                    </div>

   <!-- Category and Condition -->
 <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">
                                Item Category <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="category"
                                    name="category" required>
                                <option value="">Select category</option>
                                <option value="Electronics" @selected(old('category') === 'Electronics')>Electronics</option>
                                <option value="Documents" @selected(old('category') === 'Documents')>Documents</option>
                                <option value="Bags" @selected(old('category') === 'Bags')>Bags</option>
                                <option value="Clothing" @selected(old('category') === 'Clothing')>Clothing</option>
                                <option value="Jewellery" @selected(old('category') === 'Jewellery')>Jewellery</option>
                                <option value="Books" @selected(old('category') === 'Books')>Books</option>
                                <option value="Keys" @selected(old('category') === 'Keys')>Keys</option>
                                <option value="Money" @selected(old('category') === 'Money')>Money or Wallet</option>
                                <option value="Other" @selected(old('category') === 'Other')>Other</option>
                            </select>
                        </div>

   <div class="col-md-6 mb-3">
                            <label for="condition" class="form-label">
                                Item Condition
                            </label>
                            <select class="form-select" id="condition"
                                    name="condition">
                                <option value="">Select condition</option>
                                <option value="Excellent" @selected(old('condition') === 'Excellent')>Excellent</option>
                                <option value="Good" @selected(old('condition') === 'Good')>Good</option>
                                <option value="Fair" @selected(old('condition') === 'Fair')>Fair</option>
                                <option value="Damaged" @selected(old('condition') === 'Damaged')>Damaged</option>
                            </select>
                        </div>
                    </div>

                    <!-- Location and Date -->
  <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">
                                Where Did You Find It?
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control"
                                   id="location" name="found_location"
                                   placeholder="e.g. University library"
                                   value="{{ old('found_location') }}"
                                   required>
                        </div>

 <div class="col-md-6 mb-3">
                            <label for="foundDate" class="form-label">
                                Date Found <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control"
                                   id="foundDate" name="found_date"
                                   value="{{ old('found_date') }}"
                                   required>
                        </div>
                    </div>

                    <!-- Description -->
   <div class="mb-3">
                        <label for="description" class="form-label">
                            Item Description
                            <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="description"
                                  name="description" rows="4"
                                  placeholder="Describe the item, including its colour, brand or other identifying features."
                                  required>{{ old('description') }}</textarea>
                    </div>

                    <!-- Upload Photo -->
   <div class="mb-3">
                        <label for="itemPhoto" class="form-label">
                            Upload Item Photo (Optional)
                        </label>
                        <input type="file" class="form-control"
                               id="itemPhoto" name="item_photo"
                               accept="image/*">
                        <div class="form-text">
                            Upload a clear photo of the found item.
                        </div>
                    </div>

                    <!-- Finder Details -->
   <hr>

 <h5 class="mb-3">Your Contact Information</h5>

 <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="finderName" class="form-label">
                                Your Name
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control"
                                   id="finderName" name="finder_name"
                                   placeholder="Enter your name" value="{{ old('finder_name') }}" required>
                        </div>

  <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">
                                Phone Number
                                <span class="text-danger">*</span>
                            </label>
                            <input type="tel" class="form-control"
                                   id="phone" name="phone"
                                   placeholder="e.g. 07XXXXXXXX"
                                   value="{{ old('phone') }}"
                                   required>
                        </div>
                    </div>

 <div class="mb-3">
                        <label for="email" class="form-label">
                            Email Address
                        </label>
                        <input type="email" class="form-control"
                               id="email" name="email"
                               placeholder="Enter your email address" value="{{ old('email') }}">
                    </div>

                    <!-- Agreement -->
  <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox"
                               id="confirmDetails" required>
                        <label class="form-check-label" for="confirmDetails">
                            I confirm that the information provided is correct.
                        </label>
                    </div>

                    <!-- Buttons -->
 <div class="d-flex flex-column flex-sm-row gap-2">
                        <button type="submit" class="btn btn-primary">
                            Post Found Item
                        </button>

 <button type="reset" class="btn btn-outline-secondary">
                            Clear Form
                        </button>

   <a href="#" class="btn btn-light border">
                            Cancel
                        </a>
                    </div>

 </form>
            </div>
        </div>

        <!-- Footer Note -->
<div class="text-center mt-4">
            <p class="text-muted small">
                Thank you for helping reunite lost items with their owners.
            </p>
        </div>

</div>

    <!-- Bootstrap JavaScript Bundle for the navigation menu -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('frontend/script.js')}}"></script>
</body>
</html>
```
