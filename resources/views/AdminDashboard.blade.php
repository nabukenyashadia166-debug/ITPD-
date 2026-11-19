<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Campus Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('frontend/style.css')}}">
</head>
<body class="bg-body-tertiary site-image-background">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid px-lg-4">
            <a class="navbar-brand fw-bold" href="{{ route('AdminDashboard') }}"><img src="{{asset('frontend/images/logo.jpg')}}" alt="" class="site-brand-logo">Campus Lost & Found</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNav">
                <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <span class="nav-link text-white-50">Administrator</span>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm ms-lg-2">Log out</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
<div class="container-fluid px-0">
        <div class="row g-0">
            <aside class="col-12 col-lg-2 bg-primary min-vh-100">
                <nav class="p-3 p-lg-4" aria-label="Admin navigation">
                    <p class="small text-uppercase fw-semibold text-white-50 mb-3">Workspace</p>
                    <div class="nav nav-pills flex-row flex-lg-column gap-2">
                        <a href="{{ route('AdminDashboard') }}" class="nav-link active bg-white text-primary" aria-current="page">Dashboard</a>
                        <a href="{{ route('searchItems') }}" class="nav-link text-white">Manage items</a>
                        <a href="{{ route('itemArchive') }}" class="nav-link text-white">Item archive</a>
                        <a href="{{ route('claimVerification') }}" class="nav-link text-white">Claim requests</a>
                        <a href="{{ route('profile') }}" class="nav-link text-white">My profile</a>
                    </div>
                </nav>
            </aside>

 <main class="col-12 col-lg-10 px-3 px-md-4 px-xl-5 py-4 py-lg-5">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4 mb-lg-5">
   <div>
                        <p class="text-primary text-uppercase fw-semibold small mb-1">Overview</p>
                        <h1 class="h2 fw-bold mb-2">Admin dashboard</h1>
                        <p class="text-secondary mb-0">A clear view of items and claim requests as they come in.</p>
                    </div>
                    <a href="{{ route('postItem') }}" class="btn btn-primary px-4">+ Post an item</a>
                </div>

   <section aria-labelledby="summaryHeading" class="mb-4 mb-lg-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 id="summaryHeading" class="h5 fw-bold mb-0">At a glance</h2>
                        <span class="small text-secondary">Current reports</span>
                    </div>
  <div class="row g-3">
                        <div class="col-6 col-xl-3">
                            <div class="card h-100 border-0 shadow-sm"><div class="card-body p-3 p-md-4">
                                <p class="small text-secondary mb-2">All items</p><p class="h2 fw-bold mb-1" data-dashboard-count="total">{{ $lostCount + $foundCount }}</p><p class="small text-secondary mb-0">Lost and found reports</p>
                            </div></div>
                        </div>
  <div class="col-6 col-xl-3">
                            <div class="card h-100 border-0 shadow-sm"><div class="card-body p-3 p-md-4">
                                <p class="small text-secondary mb-2">Lost items</p><p class="h2 fw-bold text-danger mb-1" data-dashboard-count="lost">{{ $lostCount }}</p><p class="small text-secondary mb-0">Waiting to be reunited</p>
                            </div></div>
                        </div>
   <div class="col-6 col-xl-3">
                            <div class="card h-100 border-0 shadow-sm"><div class="card-body p-3 p-md-4">
                                <p class="small text-secondary mb-2">Found items</p><p class="h2 fw-bold text-success mb-1" data-dashboard-count="found">{{ $foundCount }}</p><p class="small text-secondary mb-0">Ready to be claimed</p>
                            </div></div>
                        </div>
 <div class="col-6 col-xl-3">
                            <div class="card h-100 border-0 shadow-sm"><div class="card-body p-3 p-md-4">
                                <p class="small text-secondary mb-2">Open claims</p><p class="h2 fw-bold text-warning mb-1" data-dashboard-count="claims">{{ $openClaimCount }}</p><p class="small text-secondary mb-0">Waiting for review</p>
                            </div></div>
                        </div>
                        <div class="col-6 col-xl-3">
                            <div class="card h-100 border-0 shadow-sm"><div class="card-body p-3 p-md-4">
                                <p class="small text-secondary mb-2">Potential matches</p><p class="h2 fw-bold text-primary mb-1">{{ $potentialMatchCount }}</p><p class="small text-secondary mb-0">Lost/found pairs scoring 35% or higher</p>
                            </div></div>
                        </div>
                    </div>
                    <p id="dashboard-count-status" class="small text-secondary mt-2 mb-0" aria-live="polite">Counts update automatically.</p>
                </section>

 <section class="card border-0 shadow-sm mb-4" aria-labelledby="matchesHeading">
                    <div class="card-header bg-white border-0 px-3 px-md-4 pt-4 pb-2">
                        <div>
                            <h2 id="matchesHeading" class="h5 fw-bold mb-1">Potential lost and found matches</h2>
                            <p class="small text-secondary mb-0">Top matches use the existing name, category, description, location, and date score.</p>
                        </div>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        @forelse ($potentialMatches as $match)
                            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 py-3 border-bottom">
                                <div>
                                    <div class="fw-semibold">
                                        <a href="{{ route('itemDetails', ['type' => 'lost', 'id' => $match['lost_id']]) }}" class="text-decoration-none">{{ $match['lost_name'] }}</a>
                                        <span class="text-secondary mx-1">&harr;</span>
                                        <a href="{{ route('itemDetails', ['type' => 'found', 'id' => $match['found_id']]) }}" class="text-decoration-none">{{ $match['found_name'] }}</a>
                                    </div>
                                    <div class="small text-secondary">Lost report matched with found report</div>
                                </div>
                                <span class="badge text-bg-primary align-self-sm-start">{{ $match['score'] }}% match</span>
                            </div>
                        @empty
                            <p class="text-secondary mb-0">No potential lost/found matches meet the 35% threshold yet.</p>
                        @endforelse
                    </div>
                </section>

 <section class="card border-0 shadow-sm mb-4" aria-labelledby="itemsHeading">
                    <div class="card-header bg-white border-0 px-3 px-md-4 pt-4 pb-2">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                            <div><h2 id="itemsHeading" class="h5 fw-bold mb-1">Recently reported items</h2><p class="small text-secondary mb-0">New listings will show up here.</p></div>
                            <a href="{{ route('searchItems') }}" class="link-primary text-decoration-none small">View all items &rarr;</a>
                        </div>
                    </div>
  <div class="card-body p-3 p-md-4">
                        @forelse ($recentItems as $item)
                            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 py-3 border-bottom">
                                <div>
                                    <a href="{{ route('itemDetails', ['type' => strtolower($item['type']), 'id' => $item['id']]) }}" class="fw-semibold text-decoration-none">{{ $item['name'] }}</a>
                                    <div class="small text-secondary">{{ $item['type'] }} item &middot; {{ $item['location'] }}</div>
                                </div>
                                <div class="small text-secondary text-sm-end">
                                    <div>Reported {{ $item['reported_at']->format('M j, Y') }}</div>
                                    <span class="badge text-bg-secondary">{{ ucfirst($item['status']) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center rounded-3 bg-body-tertiary py-5 px-3">
                                <div class="d-inline-flex rounded-circle bg-primary-subtle text-primary align-items-center justify-content-center mb-3" style="width: 3.5rem; height: 3.5rem;" aria-hidden="true"><span class="fs-4">+</span></div>
                                <h3 class="h6 fw-bold">No items have been reported</h3>
                                <p class="text-secondary small mb-3">When someone reports a lost or found item, you’ll see it here.</p>
                                <a href="{{ route('postItem') }}" class="btn btn-outline-primary btn-sm">Post the first item</a>
                            </div>
                        @endforelse
                    </div>
                </section>

  <section class="card border-0 shadow-sm" aria-labelledby="claimsHeading">
                    <div class="card-header bg-white border-0 px-3 px-md-4 pt-4 pb-2">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                            <div><h2 id="claimsHeading" class="h5 fw-bold mb-1">Claim requests</h2><p class="small text-secondary mb-0">Requests will be ready for review here.</p></div>
                            <a href="{{ route('claimVerification') }}" class="link-primary text-decoration-none small">Manage claims &rarr;</a>
                        </div>
                    </div>
  <div class="card-body p-3 p-md-4">
      @forelse ($recentClaims as $claim)
          <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 py-3 border-bottom">
              <div>
                  <a href="{{ route('claimVerification.show', $claim) }}" class="fw-semibold text-decoration-none">{{ $claim->item_name }}</a>
                  <div class="small text-secondary">Claimed by {{ $claim->claimant_name }}</div>
              </div>
              <span class="badge text-bg-secondary align-self-sm-start">{{ ucfirst($claim->status) }}</span>
          </div>
      @empty
          <div class="text-center rounded-3 bg-body-tertiary py-5 px-3">
              <div class="d-inline-flex rounded-circle bg-success-subtle text-success align-items-center justify-content-center mb-3" style="width: 3.5rem; height: 3.5rem;" aria-hidden="true"><span class="fs-4">&#10003;</span></div>
              <h3 class="h6 fw-bold">No claim requests yet</h3>
              <p class="text-secondary small mb-0">There are no requests waiting for your attention.</p>
          </div>
      @endforelse
  </div>
                </section>

                <footer class="text-center text-secondary small pt-4 mt-4">
                    <p class="mb-0">&copy; 2026 Campus Lost &amp; Found</p>
                </footer>
            </main>
        </div>
    </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('frontend/script.js')}}"></script>
    <script>
        (() => {
            const status = document.getElementById('dashboard-count-status');
            const countElements = {
                lost: document.querySelector('[data-dashboard-count="lost"]'),
                found: document.querySelector('[data-dashboard-count="found"]'),
                claims: document.querySelector('[data-dashboard-count="claims"]'),
                total: document.querySelector('[data-dashboard-count="total"]'),
            };

            async function refreshDashboardCounts() {
                try {
                    const response = await fetch(@json(route('AdminDashboard.counts')), {
                        cache: 'no-store',
                        headers: { Accept: 'application/json' },
                    });

                    if (!response.ok) {
                        throw new Error(`Count request failed with status ${response.status}`);
                    }

                    const counts = await response.json();
                    countElements.lost.textContent = counts.lost;
                    countElements.found.textContent = counts.found;
                    countElements.claims.textContent = counts.claims;
                    countElements.total.textContent = counts.lost + counts.found;
                    status.textContent = `Counts updated at ${new Date().toLocaleTimeString()}.`;
                    status.classList.remove('text-danger');
                    status.classList.add('text-secondary');
                } catch (error) {
                    console.error('Unable to refresh admin dashboard counts.', error);
                    status.textContent = 'Could not refresh counts. Check your connection and reload the page.';
                    status.classList.remove('text-secondary');
                    status.classList.add('text-danger');
                }
            }

            refreshDashboardCounts();
            window.setInterval(refreshDashboardCounts, 10000);
        })();
    </script>
</body>
</html>
