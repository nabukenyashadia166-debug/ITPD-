
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Profile | Campus Lost & Found</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">
    <link rel="stylesheet" href="{{asset('frontend/style.css')}}">
</head>

<body class="bg-light site-image-background">
    @php
        $user = auth()->user();
        $isAdminProfile = (bool) ($user->is_admin ?? false);
        $profileRole = $isAdminProfile ? 'Administrator' : (($user->role === 'staff') ? 'Staff' : 'Student');
        $studentRoleOnly = ! $isAdminProfile && $user->role === 'student';
    @endphp

    <!-- Top Navigation -->
 <nav class="navbar navbar-light bg-white border-bottom p-3">
        <div class="container-fluid">

<a href="{{ route('dashboard') }}"
               class="navbar-brand fw-bold text-primary">
               <img src="{{asset('frontend/images/logo.jpg')}}" alt="" class="site-brand-logo">
                <i class="bi bi-geo-alt-fill"></i>
                Campus Lost & Found
                <small class="d-block text-secondary fw-normal">
                    Intelligent Hub
                </small>
            </a>

<div class="d-flex align-items-center gap-2">
                <span class="d-none d-sm-inline">Hello, {{ $profileRole }}</span>
                <i class="bi bi-person-circle fs-2 text-primary"></i>
            </div>

 </div>
    </nav>

 <div class="container-fluid">
        <div class="row">

            <!-- Sidebar Navigation -->
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
                        <a href="{{ route('claimItems') }}" class="nav-link text-white">
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
                        <a href="{{ route('profile') }}"
                           class="nav-link active bg-white text-primary rounded">
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

            <!-- Main Profile Content -->
 <main class="col-md-9 col-lg-10 p-4">

                <!-- Page Heading -->
<div class="mb-4">
                    <h2 class="fw-bold text-primary">My Profile</h2>

<p class="text-secondary">
                        Manage your personal information and account settings
                        so we can contact you about your lost or found items.
                    </p>
                </div>

<div class="row g-4">

                    <!-- Profile Summary -->
  <div class="col-lg-4">

 <div class="card shadow-sm">

  <div class="card-body text-center p-4">

  <i class="bi bi-person-circle text-primary"
                                   style="font-size: 100px;"></i>

 <h4 class="mt-3 mb-1">Profile</h4>

<p class="text-secondary mb-2">
                                    {{ $profileRole }}
                                </p>

 <span class="badge text-bg-success">
                                    Active Account
                                </span>

   <hr>

 <div class="text-start">

 <p>
                                  <i class="bi bi-envelope text-primary me-2"></i>
                                    {{ $user->email ?: 'Email not added' }}
                                    </p>

 @if ($studentRoleOnly)
 <p>
                                        <i class="bi bi-mortarboard text-primary me-2"></i>
                                        {{ $user->course ?: 'Course not provided' }}
                                    </p>

 <p class="mb-0">
                                        <i class="bi bi-calendar-check text-primary me-2"></i>
                                        {{ $user->year_of_study ? 'Year ' . $user->year_of_study : 'Year not provided' }}
                                    </p>
 @elseif ($user->role === 'staff')
 <p class="mb-0">
                                        <i class="bi bi-shield-fill-check text-primary me-2"></i>
                                        Staff account
                                    </p>
 @else
 <p class="mb-0">
                                        <i class="bi bi-shield-fill-check text-primary me-2"></i>
                                        Administrator account
                                    </p>
 @endif

  </div>

  </div>
                        </div>

                        <!-- Account Information -->
   <div class="card shadow-sm mt-4">

   <div class="card-header bg-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-shield-check text-primary"></i>
                                    Account Information
                                </h5>
                            </div>

  <div class="card-body">

   <p class="text-secondary small">
                                    Keep your contact details up to date
                                    so that the campus team can reach you
                                    when there is an update about your items.
                                </p>

<div class="d-flex flex-column gap-2 mt-3">
    <p class="mb-0"><strong>Account type:</strong> {{ $profileRole }}</p>
    @if ($user->student_number)
        <p class="mb-0"><strong>Registration number:</strong> {{ $user->student_number }}</p>
    @endif
    @if ($user->course)
        <p class="mb-0"><strong>Course:</strong> {{ $user->course }}</p>
    @endif
    @if ($user->year_of_study)
        <p class="mb-0"><strong>Year of study:</strong> {{ $user->year_of_study }}</p>
    @endif
    @if ($user->campus)
        <p class="mb-0"><strong>Campus:</strong> {{ $user->campus }}</p>
    @endif
</div>

 </div>
                        </div>

</div>

                    <!-- Edit Profile Form -->
 <div class="col-lg-8">

 <div class="card shadow-sm mb-4">

 <div class="card-header bg-white p-3">
                                <h5 class="mb-1">
                                    <i class="bi bi-person-lines-fill text-primary"></i>
                                    Personal Information
                                </h5>

 <p class="text-secondary small mb-0">
                                    Update your details below.
                                </p>
                            </div>

 <div class="card-body p-4">

  @php($profileName = explode(' ', $user->name, 2))
  @if (session('success'))
      <div class="alert alert-success" role="status">{{ session('success') }}</div>
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

  <form action="{{ route('profile.update') }}" method="post">
  @csrf
  @method('PUT')

  <div class="row g-3">

                                        <!-- First Name -->
 <div class="col-md-6">
                                            <label for="firstName"
                                                   class="form-label">
                                                First Name *
                                            </label>

 <input type="text"
                                                   id="firstName"
                                                   name="firstName"
                                                   class="form-control"
                                                   placeholder="Enter your first name"
                                                   value="{{ old('firstName', $profileName[0]) }}"
                                                   required>
                                        </div>

                                        <!-- Last Name -->
<div class="col-md-6">
                                            <label for="lastName"
                                                   class="form-label">
                                                Last Name *
                                            </label>

 <input type="text"
                                                   id="lastName"
                                                   name="lastName"
                                                   class="form-control"
                                                   placeholder="Enter your last name"
                                                   value="{{ old('lastName', $profileName[1] ?? '') }}"
                                                   required>
                                        </div>

 <!-- Email -->
 <div class="col-md-6">
                                            <label for="email"
                                                   class="form-label">
                                                Email Address *
                                            </label>

 <input type="email"
                                                   id="email"
                                                   name="email"
                                                   class="form-control"
                                                   placeholder="Enter your email"
                                                   value="{{ old('email', $user->email) }}"
                                                   required>
                                        </div>

                                        <!-- Phone -->
<div class="col-md-6">
                                            <label for="phone"
                                                   class="form-label">
                                                Phone Number *
                                            </label>

 <input type="tel"
                                                   id="phone"
                                                   name="phone"
                                                   class="form-control"
                                                   placeholder="Enter your phone number"
                                                   value="{{ old('phone', $user->telephone) }}"
                                                   required>
                                        </div>

                                        <!-- Role -->
 <div class="col-md-6">
                                            <label for="role"
                                                   class="form-label">
                                                Account Role
                                            </label>

<select id="role"
                                                    name="role"
                                                    class="form-select"
                                                    {{ $isAdminProfile ? 'disabled' : '' }}>

  <option value="student" {{ (! $isAdminProfile && $user->role === 'student') ? 'selected' : '' }}>
                                                    Student
                                                </option>

<option value="staff" {{ (! $isAdminProfile && $user->role === 'staff') ? 'selected' : '' }}>
                                                    Staff
                                                </option>

 </select>
                                        </div>

                                        @if (! $isAdminProfile)
                                        <!-- Student Number -->
  <div class="col-md-6">
                                            <label for="studentNumber"
                                                   class="form-label">
                                                Student Registration Number
                                            </label>

 <input type="text"
                                                   id="studentNumber"
                                                   name="studentNumber"
                                                   class="form-control"
                                                   placeholder="Enter registration number"
                                                   value="{{ old('studentNumber', $user->student_number) }}">
                                        </div>

                                        @if ($user->role === 'student')
                                        <!-- Course -->
<div class="col-md-6">
                                            <label for="course"
                                                   class="form-label">
                                                Course or Programme
                                            </label>

<input type="text"
                                                   id="course"
                                                   name="course"
                                                   class="form-control"
                                                   value="{{ old('course', $user->course) }}">
                                        </div>

                                        <!-- Year -->
 <div class="col-md-6">
                                            <label for="year"
                                                   class="form-label">
                                                Year of Study
                                            </label>

 <select id="year"
                                                    name="year"
                                                    class="form-select">

 <option value="">Select Year</option>
                                                <option value="1" {{ old('year', $user->year_of_study) == '1' ? 'selected' : '' }}>Year 1</option>
                                                <option value="2" {{ old('year', $user->year_of_study) == '2' ? 'selected' : '' }}>Year 2</option>
                                                <option value="3" {{ old('year', $user->year_of_study) == '3' ? 'selected' : '' }}>Year 3</option>
                                                <option value="4" {{ old('year', $user->year_of_study) == '4' ? 'selected' : '' }}>Year 4</option>

  </select>
                                        </div>

                                        <!-- Campus -->
<div class="col-12">
                                            <label for="campus"
                                                   class="form-label">
                                                Campus
                                            </label>

 <input type="text"
                                                   id="campus"
                                                   name="campus"
                                                   class="form-control"
                                                   placeholder="Enter your campus name"
                                                   value="{{ old('campus', $user->campus) }}">
                                        </div>
                                        @endif
                                        @endif

 </div>

  <hr class="my-4">

<div class="d-flex justify-content-end gap-2">

<button type="reset"
                                                class="btn btn-outline-secondary">
                                            Reset
                                        </button>

 <button type="submit"
                                                class="btn btn-primary">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Save Changes
                                        </button>

</div>

 </form>

</div>
                        </div>

                        <!-- Change Password -->
 <div class="card shadow-sm">

 <div class="card-header bg-white p-3">
                                <h5 class="mb-1">
                                    <i class="bi bi-lock text-primary"></i>
                                    Change Password
                                </h5>

 <p class="text-secondary small mb-0">
                                    Use a strong password to protect your account.
                                </p>
                            </div>

 <div class="card-body p-4">

  @if ($errors->has('currentPassword') || $errors->has('newPassword') || $errors->has('confirmPassword'))
      <div class="alert alert-danger" role="alert">
          <ul class="mb-0">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  <form action="{{ route('password.update') }}" method="post">

 @csrf
 @method('PUT')

 <div class="mb-3">
                                        <label for="currentPassword"
                                               class="form-label">
                                            Current Password
                                        </label>

 <input type="password"
                                               id="currentPassword"
                                               name="currentPassword"
                                               class="form-control"
                                               autocomplete="current-password"
                                               placeholder="Enter current password"
                                               required>
                                    </div>

 <div class="mb-3">
                                        <label for="newPassword"
                                               class="form-label">
                                            New Password
                                        </label>

 <input type="password"
                                               id="newPassword"
                                               name="newPassword"
                                               class="form-control"
                                               autocomplete="new-password"
                                               minlength="8"
                                               placeholder="At least 8 characters"
                                               required>
                                    </div>

<div class="mb-3">
                                        <label for="confirmPassword"
                                               class="form-label">
                                            Confirm New Password
                                        </label>

 <input type="password"
                                               id="confirmPassword"
                                               name="confirmPassword"
                                               class="form-control"
                                               autocomplete="new-password"
                                               minlength="8"
                                               placeholder="Enter the new password again"
                                               required>
                                    </div>

<button type="submit"
                                            class="btn btn-outline-primary">
                                        <i class="bi bi-key me-1"></i>
                                        Update Password
                                    </button>

</form>

</div>
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
<script src="{{asset('frontend/script.js')}}"></script>
</body>
</html>
