
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account | Campus Lost &amp; Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('frontend/style.css')}}">
</head>
<body class="bg-body-tertiary">
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
        <section class="row g-0 login-shell w-100 bg-white rounded-4 shadow overflow-hidden">
            <div class="col-12 col-lg-6 login-photo-panel position-relative overflow-hidden p-0">
                <img src="{{ asset('frontend/images/download.jpg') }}"
                     alt="Campus building" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
                <div class="position-absolute top-0 bottom-0 start-0 end-0 p-4 p-xl-5 text-white bg-dark bg-opacity-10 d-flex flex-column justify-content-center">
                    <i class="bi bi-geo-alt-fill display-5 mb-3" aria-hidden="true"></i>
                    <h1 class="h2 fw-bold mb-2 register-photo-title">Campus Lost &amp; Found</h1>
                    <p class="fs-5 mb-0">Reuniting people with their belongings one item at a time.</p>
                </div>
            </div>

 <div class="col-12 col-lg-6">
                <div class="p-4 p-md-5">
                    <div class="mb-4">
                        <img src="{{ asset('frontend/images/logo.jpg') }}" alt="Campus Lost &amp; Found logo"
                             class="d-block mb-3" style="max-width: 100px; max-height: 80px; object-fit: contain;">
                        <h2 class="h3 fw-bold text-primary mb-1">Campus Lost &amp; Found</h2>
                        <p class="text-secondary mb-0">Intelligent Hub</p>
                    </div>

<h3 class="h5 fw-semibold mb-1">Create your account</h3>
                    <p class="text-secondary mb-4">Join the campus community and help belongings find their way home.</p>

                    <form action="{{ route('register.submit') }}" method="POST">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                        @endif
                        <div class="mb-3">
                            <label for="roleFilter" class="form-label">Account type</label>
                            <select name="role" id="roleFilter" class="form-select" required>
                                <option value="" selected disabled>Choose your account type</option>
                                <option value="student">Student</option>
                                <option value="staff">Staff</option>
                            </select>
                            <div class="form-text">Administrator access is assigned by the system administrator.</div>
                        </div>

                        <div id="studentFields" class="row g-3 mb-3 d-none">
                            <div class="col-md-6">
                                <label for="course" class="form-label">Course or programme</label>
                                <input type="text" class="form-control" id="course" name="course" value="{{ old('course') }}"
                                       placeholder="e.g. Business Computing">
                            </div>
                            <div class="col-md-6">
                                <label for="yearOfStudy" class="form-label">Year of study</label>
                                <select class="form-select" id="yearOfStudy" name="year_of_study">
                                    <option value="" selected>Choose year</option>
                                    <option value="1">Year 1</option>
                                    <option value="2">Year 2</option>
                                    <option value="3">Year 3</option>
                                    <option value="4">Year 4</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="campus" class="form-label">Campus</label>
                                <input type="text" class="form-control" id="campus" name="campus" value="{{ old('campus') }}"
                                       placeholder="Enter your campus name">
                            </div>
                        </div>

<div class="mb-3">
                            <label for="name" class="form-label">Full name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                                   placeholder="Enter your full name" autocomplete="name" required>
                        </div>

<div class="mb-3">
                            <label for="Email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="Email" name="email" value="{{ old('email') }}"
                                   placeholder="you@example.com" autocomplete="email" required>
                        </div>

<div class="mb-3">
                            <label for="telephone" class="form-label">Phone number</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" value="{{ old('telephone') }}"
                                   placeholder="Enter your phone number" autocomplete="tel" required>
                        </div>

 <div class="mb-3">
                            <label for="registerPassword" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="registerPassword" name="password"
                                       placeholder="At least 8 characters" autocomplete="new-password" minlength="8" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword" aria-label="Show password">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

<div class="mb-4">
                            <label for="confirmPassword" class="form-label">Confirm password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" name="password_confirmation"
                                       placeholder="Enter your password again" autocomplete="new-password" minlength="8" required>
                                <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword" aria-label="Show password">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

 <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-person-plus me-2" aria-hidden="true"></i>Create account
                        </button>
                    </form>

 <p class="text-center text-secondary mt-4 mb-0">
                        Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Log in</a>
                    </p>
                </div>
            </div>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('frontend/script.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleFilter = document.getElementById('roleFilter');
            const studentFields = document.getElementById('studentFields');
            const studentInputs = studentFields.querySelectorAll('input, select');

            function toggleStudentFields() {
                const isStudent = roleFilter.value === 'student';
                studentFields.classList.toggle('d-none', !isStudent);
                studentInputs.forEach(function (field) {
                    field.disabled = !isStudent;
                });
            }

            roleFilter.addEventListener('change', toggleStudentFields);
            toggleStudentFields();
        });
    </script>
</body>
</html>
