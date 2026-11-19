<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Login | Campus Lost &amp; Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/style.css') }}">
</head>
<body class="bg-body-tertiary">
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
        <section class="card border-0 shadow-sm w-100" style="max-width: 460px;">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('frontend/images/logo.jpg') }}"
                         alt="Campus Lost &amp; Found logo"
                         class="mb-3"
                         style="max-width: 100px; max-height: 80px; object-fit: contain;">
                    <h1 class="h3 fw-bold text-primary">Administrator Login</h1>
                    <p class="text-secondary mb-0">Sign in with an existing administrator account.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email"
                               class="form-control"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               autocomplete="username"
                               required
                               autofocus>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   name="password"
                                   autocomplete="current-password"
                                   required>
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword"
                                    aria-label="Show password">
                                <i class="bi bi-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Sign in as administrator</button>
                </form>

                <p class="text-center mt-4 mb-0">
                    <a href="{{ route('login') }}" class="text-decoration-none">Back to regular login</a>
                </p>
            </div>
        </section>
    </main>
    <script src="{{ asset('frontend/script.js') }}"></script>
</body>
</html>
