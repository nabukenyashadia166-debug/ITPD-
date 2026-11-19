
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Post Lost Item | Campus Lost & Found</title>

    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">
    <link rel="stylesheet" href="{{asset('frontend/style.css')}}">
</head>

<body class="bg-light site-image-background">

    <!-- Navigation Bar -->
<nav class="navbar navbar-light bg-white border-bottom p-3">
        <div class="container-fluid">

  <a href="{{ route('dashboard') }}"
               class="navbar-brand fw-bold text-primary">
               <img src="{{asset('frontend/images/logo.jpg')}}" alt="" class="site-brand-logo">
                <i class="bi bi-geo-alt-fill"></i>
                Campus Lost & Found
            </a>

<div class="d-flex align-items-center gap-2">
                <span>Hello, Shadia</span>
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
                        <a href="{{ route('dashboard') }}"
                           class="nav-link text-white">
                            <i class="bi bi-grid me-2"></i>
                            Dashboard
                        </a>
                    </li>

 <li class="nav-item">
                        <a href="{{ route('postItem') }}"
                           class="nav-link active bg-white text-primary rounded">
                            <i class="bi bi-plus-square me-2"></i>
                            Post Lost Item
                        </a>
                    </li>

<li class="nav-item">
                        <a href="{{ route('myItems') }}"
                           class="nav-link text-white">
                            <i class="bi bi-bag me-2"></i>
                            My Items
                        </a>
                    </li>

<li class="nav-item">
                        <a href="{{ route('claimItems') }}"
                           class="nav-link text-white">
                            <i class="bi bi-clipboard-check me-2"></i>
                            Claim Requests
                        </a>
                    </li>

<li class="nav-item">
                        <a href="{{ route('searchItems') }}"
                           class="nav-link text-white">
                            <i class="bi bi-search me-2"></i>
                            Search Items
                        </a>
                    </li>

<li class="nav-item">
                        <a href="{{ route('profile') }}"
                           class="nav-link text-white">
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

                <!-- Breadcrumb -->
<nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>

<li class="breadcrumb-item active">
                            Post Lost Item
                        </li>
                    </ol>
                </nav>

                <!-- Page Heading -->
<h2 class="fw-bold text-primary">
                    Post a Lost Item
                </h2>

<p class="text-secondary">
                    Lost something on campus? Tell us about it so we can
                    help you find it.
                </p>

                <!-- Form Card -->
 <div class="card shadow-sm">

<div class="card-header bg-white p-3">
                        <h5 class="mb-1">
                            <i class="bi bi-clipboard2-plus text-primary"></i>
                            Item Details
                        </h5>

<p class="text-secondary mb-0">
                            Provide information about the item you lost.
                        </p>
                    </div>

<div class="card-body p-4">

@include('partials.item-matches', ['matchType' => 'found'])

 @if (session('success'))
     <div class="alert alert-success" role="status">{{ session('success') }}</div>
 @endif
 @if ($errors->any())
     <div class="alert alert-danger" role="alert">
         <p class="mb-1">Your report could not be saved. Please correct these fields:</p>
         <ul class="mb-0">
             @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
             @endforeach
         </ul>
     </div>
 @endif

 <form id="lostItemForm" action="{{ route('postItem.store') }}" method="POST" enctype="multipart/form-data">
 @csrf

 <div class="row g-4">

                                <!-- Item Information -->
<div class="col-lg-7">

                                    <!-- Category -->
 <div class="mb-3">
                                        <label for="category"
                                               class="form-label">
                                            Item Category *
                                        </label>

  <select id="category"
                                                name="category"
                                                class="form-select"
                                                required>

  <option value="" @selected(old('category') === null)>
                                                Select Category
                                            </option>

  <option value="Electronics" @selected(old('category') === 'Electronics')>
                                                Electronics
                                            </option>

 <option value="Bags" @selected(old('category') === 'Bags')>
                                                Bags
                                            </option>

 <option value="Books" @selected(old('category') === 'Books')>
                                                Books and Stationery
                                            </option>

<option value="IDs" @selected(old('category') === 'IDs')>
                                                Identity Cards
                                            </option>

 <option value="Clothing" @selected(old('category') === 'Clothing')>
                                                Clothing
                                            </option>

<option value="Keys" @selected(old('category') === 'Keys')>
                                                Keys
                                            </option>

<option value="Wallets" @selected(old('category') === 'Wallets')>
                                                Money and Wallets
                                            </option>

<option value="Other" @selected(old('category') === 'Other')>
                                                Other
                                            </option>

 </select>
                                    </div>

                                    <!-- Item Name -->
 <div class="mb-3">
                                        <label for="itemName"
                                               class="form-label">
                                            Item Name *
                                        </label>

<input type="text"
                                               id="itemName"
                                               name="item_name"
                                               class="form-control"
                                               placeholder="e.g. Black backpack"
                                               maxlength="100"
                                               value="{{ old('item_name') }}"
                                               required>
                                    </div>

                                    <!-- Description -->
 <div class="mb-3">
                                        <label for="description"
                                               class="form-label">
                                            Item Description *
                                        </label>

 <textarea id="description"
                                                  name="description"
                                                  class="form-control"
                                                  rows="4"
                                                  maxlength="1000"
                                                  placeholder="Describe the colour, brand, size or unique features..."
                                                  required>{{ old('description') }}</textarea>
                                    </div>

                                    <!-- Location -->
<div class="mb-3">
                                        <label for="location"
                                               class="form-label">
                                            Location Lost *
                                        </label>

 <select id="location"
                                                name="location"
                                                class="form-select"
                                                required>

  <option value="">
                                                Select Location
                                            </option>

 <option value="Main Library" @selected(old('location') === 'Main Library')>Main Library</option>
                                            <option value="Lecture Hall" @selected(old('location') === 'Lecture Hall')>Lecture Hall</option>
                                            <option value="ADB" @selected(old('location') === 'ADB')>ADB</option>
                                            <option value="small gate" @selected(old('location') === 'small gate')>small gate</option>
                                            <option value="Cafeteria" @selected(old('location') === 'Cafeteria')>Cafeteria</option>
                                            <option value="Hostel" @selected(old('location') === 'Hostel')>Hostel</option>
                                            <option value="Sports Ground" @selected(old('location') === 'Sports Ground')>Sports Ground</option>
                                            <option value="Other" @selected(old('location') === 'Other')>Other</option>

</select>
                                    </div>

                                    <!-- Date Lost -->
 <div class="mb-3">
                                        <label for="dateLost"
                                               class="form-label">
                                            Date Lost *
                                        </label>

<input type="date"
                                               id="dateLost"
                                               name="date_lost"
                                               class="form-control"
                                               value="{{ old('date_lost') }}"
                                               required>
                                    </div>

                                    <!-- Contact -->
 <div class="mb-3">
                                        <label for="contact"
                                               class="form-label">
                                            Contact Information
                                        </label>

<input type="text"
                                               id="contact"
                                               name="contact"
                                               class="form-control"
                                               placeholder="Phone number or email (optional)"
                                               value="{{ old('contact') }}">
                                    </div>

</div>

                                <!-- Photo Section -->
 <div class="col-lg-5">

 <label class="form-label">
                                        Add Photo *
                                    </label>

<div class="card bg-light text-center p-4">

 <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>

<p class="mt-2">
                                            Upload a photo of your lost item.
                                        </p>

<input type="file"
                                               id="itemPhoto"
                                               name="item_photo"
                                               class="form-control"
                                               accept="image/jpeg,image/png,image/webp"
                                               required>

 <small class="text-secondary mt-2">
                                            JPG, PNG or WEBP. Maximum 5 MB.
                                        </small>

 <!-- Photo Preview -->
 <img id="photoPreview"
                                             src=""
                                             alt="Item photo preview"
                                             class="img-fluid rounded mt-3 d-none">

 <button type="button"
                                                id="removePhoto"
                                                class="btn btn-outline-danger btn-sm mt-3">
                                            <i class="bi bi-trash"></i>
                                            Remove Photo
                                        </button>

                                    </div>

                                    <!-- Camera -->
 <div class="card mt-3 p-3">

 <h6>
                                            <i class="bi bi-camera text-primary"></i>
                                            Take a Photo
                                        </h6>

  <video id="cameraVideo"
                                               class="w-100 d-none"
                                               autoplay
                                               playsinline></video>

 <canvas id="cameraCanvas"
                                                class="d-none"></canvas>

<div class="d-grid gap-2 mt-2">

<button type="button"
                                                    id="cameraButton"
                                                    class="btn btn-outline-primary">
                                                <i class="bi bi-camera"></i>
                                                Open Camera
                                            </button>

<button type="button"
                                                    id="captureButton"
                                                    class="btn btn-primary"
                                                    disabled>
                                                Capture Photo
                                            </button>

<button type="button"
                                                    id="closeCamera"
                                                    class="btn btn-outline-secondary">
                                                Close Camera
                                            </button>

</div>

 <small id="cameraMessage"
                                               class="text-secondary mt-2"></small>

</div>

                                    <!-- Privacy Notice -->
<div class="alert alert-info mt-3">
                                        <i class="bi bi-shield-check"></i>

                                        Your information will help us identify
                                        and recover your lost item.
</div>

 </div>

 </div>

                            <!-- Buttons -->
<hr class="my-4">

<div class="d-flex justify-content-between
                                        flex-wrap gap-2">

<button type="reset"
                                        id="cancelButton"
                                        class="btn btn-outline-secondary">
                                    Cancel
                                </button>

 <button type="submit"
                                        class="btn btn-primary">
                                    <i class="bi bi-send"></i>
                                    Submit Item
                                </button>

 </div>

 </form>

</div>
                </div>

 <p class="text-center text-secondary mt-4 small">
                    Campus Lost & Found Intelligent Hub
                </p>

 </main>
        </div>
    </div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    
 </script>

 <script src="{{asset('frontend/script.js')}}"></script>
</body>
</html>
