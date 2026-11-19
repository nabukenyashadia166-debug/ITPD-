
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FIND IT HUB</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/style.css') }}">
</head>
<body class="bg-body-tertiary">
  <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
    <section class="row g-0 login-shell w-100 bg-white rounded-4 shadow overflow-hidden">
      <div class="col-12 col-lg-6 login-photo-panel position-relative overflow-hidden p-0">
        <img src="{{ asset('frontend/images/download.jpg') }}"
             alt="Campus building" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
        <div class="position-absolute top-0 bottom-0 start-0 end-0 p-4 p-xl-5 text-white bg-dark bg-opacity-25 d-flex flex-column justify-content-end">
          <i class="bi bi-geo-alt-fill display-5 mb-3"></i>
          <h1 class="h2 fw-bold mb-2">Campus Lost &amp; Found</h1>
          <p class="fs-5 mb-0">Reuniting people with their belongings one item at a time.</p>
        </div>
      </div>

<div class="col-lg-6">
        <div class="p-4 p-md-5">
          <div class="mb-4">
            <img src="{{ asset('frontend/images/logo.jpg') }}" alt="Campus Lost &amp; Found logo"
                 class="d-block mb-3" style="max-width: 100px; max-height: 80px; object-fit: contain;">
            <h2 class="h3 fw-bold text-primary mb-1">Campus Lost &amp; Found</h2>
            <p class="text-secondary mb-0">Intelligent Hub</p>
          </div>

   <h3 class="h5 fw-semibold mb-4">Login</h3>
          <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            @if ($errors->any())
              <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
            @endif
            <div class="mb-3">
              <label for="name" class="form-label">Email address</label>
              <input type="email" class="form-control form-control-lg" id="name" name="email"
                     placeholder="Enter your email" autocomplete="username" required>
            </div>

   <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-group input-group-lg">
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Enter password" autocomplete="current-password" required>
                <button type="button" class="btn btn-outline-secondary" id="togglePassword"
                        aria-label="Show password">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

   <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                <label class="form-check-label" for="rememberMe">Remember me</label>
              </div>
              <a href="#" class="text-decoration-none">Forgot password?</a>
            </div>

  <button type="submit" class="btn btn-primary btn-lg w-100">
              <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </button>
          </form>

  <p class="text-center text-secondary mt-4 mb-0">
            Don't have an account? <a href="{{route('register')}}" class="text-decoration-none">Register</a>
          </p>
          <p class="text-center mt-2 mb-0">
            <a href="{{ route('admin.login') }}" class="small text-decoration-none">Administrator login</a>
          </p>
        </div>
      </div>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('frontend/script.js') }}"></script>
</body>
</html>
