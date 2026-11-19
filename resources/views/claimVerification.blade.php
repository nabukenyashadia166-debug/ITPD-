<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Verification | Campus Lost &amp; Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/style.css') }}">
</head>
<body class="bg-body-tertiary site-image-background">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid px-lg-4">
            <a href="{{ route('AdminDashboard') }}" class="navbar-brand fw-bold">
                <img src="{{ asset('frontend/images/logo.jpg') }}" alt="" class="site-brand-logo">Campus Lost &amp; Found
            </a>
            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <span class="nav-link text-white-50">Administrator</span>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Log out</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-0">
        <div class="row g-0">
            <aside class="col-12 col-lg-2 bg-primary min-vh-100">
                <nav class="p-3 p-lg-4" aria-label="Admin navigation">
                    <p class="small text-uppercase fw-semibold text-white-50 mb-3">Workspace</p>
                    <div class="nav nav-pills flex-row flex-lg-column gap-2">
                        <a href="{{ route('AdminDashboard') }}" class="nav-link text-white">Dashboard</a>
                        <a href="{{ route('searchItems') }}" class="nav-link text-white">Manage items</a>
                        <a href="{{ route('itemArchive') }}" class="nav-link text-white">Item archive</a>
                        <a href="{{ route('claimVerification') }}" class="nav-link active bg-white text-primary" aria-current="page">Claim requests</a>
                        <a href="{{ route('profile') }}" class="nav-link text-white">My profile</a>
                    </div>
                </nav>
            </aside>

            <main class="col-12 col-lg-10 px-3 px-md-4 px-xl-5 py-4 py-lg-5">
                <div class="mb-4">
                    <p class="text-primary text-uppercase fw-semibold small mb-1">Claim review</p>
                    <h1 class="h2 fw-bold mb-2">Claim verification</h1>
                    <p class="text-secondary mb-0">Review the claimant's information and evidence before deciding.</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success" role="status">{{ session('success') }}</div>
                @endif
                @if ($errors->has('status'))
                    <div class="alert alert-danger" role="alert">{{ $errors->first('status') }}</div>
                @endif

                <section class="card border-0 shadow-sm mb-4" aria-labelledby="claimsHeading">
                    <div class="card-header bg-white border-0 px-3 px-md-4 pt-4">
                        <h2 id="claimsHeading" class="h5 fw-bold mb-0">Submitted claims</h2>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        @forelse ($claims as $claim)
                            <a href="{{ route('claimVerification.show', $claim) }}" class="d-flex flex-column flex-sm-row justify-content-between gap-2 py-3 border-bottom text-decoration-none">
                                <div>
                                    <div class="fw-semibold">{{ $claim->item_name }}</div>
                                    <div class="small text-secondary">Claimed by {{ $claim->claimant_name }} · {{ $claim->created_at->format('M j, Y') }}</div>
                                </div>
                                <span class="badge {{ $claim->status === 'pending' ? 'text-bg-warning' : ($claim->status === 'approved' ? 'text-bg-success' : 'text-bg-secondary') }} align-self-sm-start">{{ ucfirst($claim->status) }}</span>
                            </a>
                        @empty
                            <p class="text-secondary mb-0">There are no claim requests yet.</p>
                        @endforelse

                        @if ($claims->hasPages())
                            <div class="mt-3">{{ $claims->links() }}</div>
                        @endif
                    </div>
                </section>

                @if ($selectedClaim)
                    <section class="card border-0 shadow-sm" aria-labelledby="selectedClaimHeading">
                        <div class="card-header bg-white border-0 px-3 px-md-4 pt-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                                <div>
                                    <p class="text-primary text-uppercase fw-semibold small mb-1">Claim #{{ $selectedClaim->id }}</p>
                                    <h2 id="selectedClaimHeading" class="h4 fw-bold mb-0">{{ $selectedClaim->item_name }}</h2>
                                </div>
                                <span class="badge {{ $selectedClaim->status === 'pending' ? 'text-bg-warning' : ($selectedClaim->status === 'approved' ? 'text-bg-success' : 'text-bg-secondary') }}">{{ ucfirst($selectedClaim->status) }}</span>
                            </div>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <dl class="row mb-4">
                                <dt class="col-sm-4">Category</dt>
                                <dd class="col-sm-8">{{ $selectedClaim->category ?: 'Not provided' }}</dd>
                                <dt class="col-sm-4">Claimant</dt>
                                <dd class="col-sm-8">{{ $selectedClaim->claimant_name }}</dd>
                                <dt class="col-sm-4">Student or staff ID</dt>
                                <dd class="col-sm-8">{{ $selectedClaim->student_id ?: 'Not provided' }}</dd>
                                <dt class="col-sm-4">Contact</dt>
                                <dd class="col-sm-8">{{ $selectedClaim->contact }}</dd>
                                @if ($selectedClaim->email)
                                    <dt class="col-sm-4">Email</dt>
                                    <dd class="col-sm-8">{{ $selectedClaim->email }}</dd>
                                @endif
                                <dt class="col-sm-4">Claim details</dt>
                                <dd class="col-sm-8">{{ $selectedClaim->description }}</dd>
                                @if ($selectedClaim->lost_location)
                                    <dt class="col-sm-4">Where it was lost</dt>
                                    <dd class="col-sm-8">{{ $selectedClaim->lost_location }}</dd>
                                @endif
                                @if ($selectedClaim->additional_info)
                                    <dt class="col-sm-4">Additional information</dt>
                                    <dd class="col-sm-8">{{ $selectedClaim->additional_info }}</dd>
                                @endif
                                @if ($selectedClaim->foundItem)
                                    <dt class="col-sm-4">Found item location</dt>
                                    <dd class="col-sm-8">{{ $selectedClaim->foundItem->found_location }}</dd>
                                @endif
                                <dt class="col-sm-4">Submitted</dt>
                                <dd class="col-sm-8">{{ $selectedClaim->created_at->format('M j, Y g:i A') }}</dd>
                            </dl>

                            @if ($selectedClaim->proof_path)
                                <a href="{{ route('claimVerification.evidence', $selectedClaim) }}" class="btn btn-outline-primary mb-4">Download ownership evidence</a>
                            @else
                                <p class="text-secondary mb-4">No evidence file was attached to this claim.</p>
                            @endif

                            @if ($selectedClaim->status === 'pending')
                                <div class="alert alert-light border" role="note">
                                    Verify the claimant's identity and ask them about details that are not visible in the public listing. Never ask for passwords or PINs.
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <form action="{{ route('claimVerification.update', $selectedClaim) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-success">Approve claim</button>
                                    </form>
                                    <form action="{{ route('claimVerification.update', $selectedClaim) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-outline-danger">Reject claim</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </section>
                @else
                    <div class="alert alert-info" role="note">Select a claim above to review its details and evidence.</div>
                @endif

                <footer class="text-center text-secondary small pt-4 mt-3">
                    <p class="mb-0">&copy; 2026 Campus Lost &amp; Found</p>
                </footer>
            </main>
        </div>
    </div>
</body>
</html>
