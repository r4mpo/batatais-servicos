<?php

namespace App\Services\Professional;

use App\Models\Profession;
use App\Models\Professional;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProfessionalListingService
{
    private const PER_PAGE = 12;

    /**
     * @return array{
     *     professionals: LengthAwarePaginator,
     *     filterProfessions: \Illuminate\Database\Eloquent\Collection,
     *     selectedProfessionIds: list<int>,
     *     q: string,
     *     sort: string,
     *     rating_5: bool,
     *     rating_4: bool,
     *     max_price_reais: int,
     *     avail_today: bool,
     *     avail_week: bool,
     *     avail_24h: bool
     * }
     */
    public function buildListing(Request $request): array
    {
        $filterProfessions = Profession::query()
            ->orderBy('title')
            ->get(['id', 'title', 'slug']);

        $selectedProfessionIds = array_values(array_filter(array_map(
            'intval',
            (array) $request->input('profession_id', [])
        )));

        $categoriaSlug = $request->string('categoria')->trim()->toString();
        if ($categoriaSlug !== '' && $selectedProfessionIds === []) {
            $pid = Profession::query()->where('slug', $categoriaSlug)->value('id');
            if ($pid) {
                $selectedProfessionIds = [(int) $pid];
            }
        }

        $q = $request->string('q')->trim()->toString();
        $sort = $request->string('sort')->toString();
        if (! in_array($sort, ['relevance', 'rating', 'price_asc', 'price_desc', 'recent'], true)) {
            $sort = 'relevance';
        }

        $rating5 = $request->boolean('rating_5');
        $rating4 = $request->boolean('rating_4');
        $maxPriceReais = min(500, max(0, (int) $request->input('max_price', 500)));
        $availToday = $request->boolean('avail_today');
        $availWeek = $request->boolean('avail_week');
        $avail24h = $request->boolean('avail_24h');

        $query = Professional::query()
            ->with(['user', 'profession'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        if ($q !== '') {
            $term = '%'.$q.'%';
            $query->where(function ($sub) use ($term) {
                $sub->where('professionals.title', 'like', $term)
                    ->orWhere('professionals.description', 'like', $term)
                    ->orWhereHas('user', function ($uq) use ($term) {
                        $uq->where('name', 'like', $term);
                    });
            });
        }

        if ($selectedProfessionIds !== []) {
            $query->whereIn('profession_id', $selectedProfessionIds);
        }

        $minAvg = $this->resolveMinAverageRating($rating5, $rating4);
        if ($minAvg !== null) {
            $query->whereRaw(
                '(SELECT AVG(rating) FROM professional_reviews WHERE professional_reviews.professional_id = professionals.id) >= ?',
                [$minAvg]
            );
        }

        $maxCents = $maxPriceReais * 100;
        $query->where('hourly_rate_cents', '<=', $maxCents);

        if ($availToday) {
            $dow = (int) now()->dayOfWeek;
            $query->whereHas('availabilities', function ($aq) use ($dow) {
                $aq->where('day_of_week', $dow);
            });
        }

        if ($availWeek) {
            $days = $this->weekdaysFromTodayThroughEndOfWeek();
            $query->whereHas('availabilities', function ($aq) use ($days) {
                $aq->whereIn('day_of_week', $days);
            });
        }

        if ($avail24h) {
            $query->whereHas('availabilities', function ($aq) {
                $aq->where('is_full_day', true);
            });
        }

        $this->applySort($query, $sort, $q);

        return [
            'professionals' => $query->paginate(self::PER_PAGE)->withQueryString(),
            'filterProfessions' => $filterProfessions,
            'selectedProfessionIds' => $selectedProfessionIds,
            'q' => $q,
            'sort' => $sort,
            'rating_5' => $rating5,
            'rating_4' => $rating4,
            'max_price_reais' => $maxPriceReais,
            'avail_today' => $availToday,
            'avail_week' => $availWeek,
            'avail_24h' => $avail24h,
        ];
    }

    private function resolveMinAverageRating(bool $rating5, bool $rating4): ?float
    {
        if (! $rating5 && ! $rating4) {
            return null;
        }

        $thresholds = [];
        if ($rating5) {
            $thresholds[] = 4.5;
        }
        if ($rating4) {
            $thresholds[] = 4.0;
        }

        return min($thresholds);
    }

    /**
     * @return list<int>
     */
    private function weekdaysFromTodayThroughEndOfWeek(): array
    {
        $days = [];
        $cursor = now()->startOfDay();
        $end = now()->copy()->endOfWeek(Carbon::SUNDAY)->startOfDay();

        while ($cursor->lte($end)) {
            $days[] = (int) $cursor->dayOfWeek;
            $cursor->addDay();
        }

        return array_values(array_unique($days));
    }

    private function applySort($query, string $sort, string $q): void
    {
        $avgSub = '(SELECT AVG(rating) FROM professional_reviews WHERE professional_reviews.professional_id = professionals.id)';

        switch ($sort) {
            case 'rating':
                $query->orderByRaw($avgSub.' IS NULL')
                    ->orderByRaw($avgSub.' DESC');
                break;
            case 'price_asc':
                $query->orderBy('hourly_rate_cents', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('hourly_rate_cents', 'desc');
                break;
            case 'recent':
                $query->orderByDesc('professionals.created_at');
                break;
            case 'relevance':
            default:
                if ($q !== '') {
                    $like = '%'.$q.'%';
                    $query->orderByRaw(
                        '(CASE WHEN professionals.title LIKE ? OR professionals.description LIKE ? THEN 0 ELSE 1 END)',
                        [$like, $like]
                    );
                }
                $query->orderByDesc('professionals.updated_at');
                break;
        }

        $query->orderBy('professionals.id');
    }
}
