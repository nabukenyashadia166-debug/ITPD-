@if (session()->has('item_matches'))
    <section class="alert alert-info" aria-live="polite">
        <h5 class="alert-heading">Possible {{ $matchType }} item matches</h5>
        @if (session('item_matches'))
            <p>We found these reports with similar details. Similarity is an estimate; please confirm the item before making a claim.</p>
            <div class="list-group">
                @foreach (session('item_matches') as $match)
                    <article class="list-group-item">
                        <div class="d-flex justify-content-between gap-3">
                            <strong>{{ $match['name'] }}</strong>
                            <span class="badge text-bg-primary">{{ $match['score'] }}% similar</span>
                        </div>
                        <div class="small text-muted">
                            {{ $match['category'] }} · {{ $match['location'] }} · {{ $match['date'] }}
                        </div>
                        <p class="mb-0 mt-2">{{ $match['description'] }}</p>
                    </article>
                @endforeach
            </div>
        @else
            <p class="mb-0">No close matches were found yet. If someone reports a similar item later, its match can be checked by submitting a new report.</p>
        @endif
    </section>
@endif
