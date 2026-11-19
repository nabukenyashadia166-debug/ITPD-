<?php

namespace App\Services;

use App\Models\PostFoundItems;
use App\Models\PostItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ItemSimilarityMatcher
{
    private const MINIMUM_SCORE = 35;

    public function findFoundMatches(PostItem $lostItem): array
    {
        $lost = [
            'name' => $lostItem->item_name,
            'category' => $lostItem->category,
            'description' => $lostItem->description,
            'location' => $lostItem->location,
            'date' => $lostItem->date_lost,
        ];

        return $this->rank(
            PostFoundItems::query()->where('status', 'active')->get(),
            $lost,
            fn (PostFoundItems $item) => [
                'name' => $item->item_name,
                'category' => $item->category,
                'description' => $item->description,
                'location' => $item->found_location,
                'date' => $item->found_date,
            ]
        );
    }

    public function findLostMatches(PostFoundItems $foundItem): array
    {
        $found = [
            'name' => $foundItem->item_name,
            'category' => $foundItem->category,
            'description' => $foundItem->description,
            'location' => $foundItem->found_location,
            'date' => $foundItem->found_date,
        ];

        return $this->rank(
            PostItem::query()->where('status', 'active')->get(),
            $found,
            fn (PostItem $item) => [
                'name' => $item->item_name,
                'category' => $item->category,
                'description' => $item->description,
                'location' => $item->location,
                'date' => $item->date_lost,
            ]
        );
    }

    public function findPotentialMatches(): array
    {
        $lostItems = PostItem::query()->where('status', 'active')->get();
        $foundItems = PostFoundItems::query()->where('status', 'active')->get();
        $matches = [];

        foreach ($lostItems as $lostItem) {
            $lostDetails = [
                'name' => $lostItem->item_name,
                'category' => $lostItem->category,
                'description' => $lostItem->description,
                'location' => $lostItem->location,
                'date' => $lostItem->date_lost,
            ];

            foreach ($foundItems as $foundItem) {
                $foundDetails = [
                    'name' => $foundItem->item_name,
                    'category' => $foundItem->category,
                    'description' => $foundItem->description,
                    'location' => $foundItem->found_location,
                    'date' => $foundItem->found_date,
                ];
                $score = $this->score($lostDetails, $foundDetails);

                if ($score < self::MINIMUM_SCORE) {
                    continue;
                }

                $matches[] = [
                    'lost_id' => $lostItem->id,
                    'lost_name' => $lostItem->item_name,
                    'found_id' => $foundItem->id,
                    'found_name' => $foundItem->item_name,
                    'score' => $score,
                ];
            }
        }

        usort($matches, fn (array $left, array $right) => $right['score'] <=> $left['score']);

        return $matches;
    }

    private function rank(Collection $candidates, array $report, callable $candidateDetails): array
    {
        return $candidates
            ->map(function ($candidate) use ($report, $candidateDetails) {
                $details = $candidateDetails($candidate);
                $score = $this->score($report, $details);

                if ($score < self::MINIMUM_SCORE) {
                    return null;
                }

                return [
                    'name' => $details['name'],
                    'category' => $details['category'],
                    'location' => $details['location'],
                    'date' => $details['date'],
                    'description' => $details['description'],
                    'score' => $score,
                ];
            })
            ->filter()
            ->sortByDesc('score')
            ->take(5)
            ->values()
            ->all();
    }

    private function score(array $report, array $candidate): int
    {
        $score = 0;

        if (Str::lower(trim($report['category'])) === Str::lower(trim($candidate['category']))) {
            $score += 30;
        }

        $score += (int) round($this->tokenSimilarity($report['name'], $candidate['name']) * 35);
        $score += (int) round($this->tokenSimilarity($report['description'], $candidate['description']) * 20);
        $score += (int) round($this->tokenSimilarity($report['location'], $candidate['location']) * 10);

        $daysApart = abs(strtotime($report['date']) - strtotime($candidate['date'])) / 86400;
        if ($daysApart <= 30) {
            $score += (int) round(5 * (1 - ($daysApart / 31)));
        }

        return min($score, 100);
    }

    private function tokenSimilarity(string $left, string $right): float
    {
        $leftTokens = $this->tokens($left);
        $rightTokens = $this->tokens($right);

        if ($leftTokens === [] || $rightTokens === []) {
            return 0;
        }

        $intersection = count(array_intersect($leftTokens, $rightTokens));
        $union = count(array_unique(array_merge($leftTokens, $rightTokens)));

        return $union === 0 ? 0 : $intersection / $union;
    }

    private function tokens(string $text): array
    {
        preg_match_all('/[\p{L}\p{N}]+/u', Str::lower($text), $matches);

        return array_values(array_unique($matches[0]));
    }
}
