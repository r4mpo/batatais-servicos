<?php

namespace App\Repositories;

use App\Models\Professional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProfessionalRepository
{
    private const AVG_RATING_SUBQUERY = '(SELECT AVG(rating) FROM professional_reviews WHERE professional_reviews.professional_id = professionals.id)';

    /**
     * @param  list<int>  $professionIds
     * @param  list<int>  $weekDayOfWeeks
     */
    public function paginateForListing(
        int $perPage,
        string $q,
        array $professionIds,
        ?float $minAvgRating,
        int $maxHourlyRateCents,
        bool $availToday,
        bool $availWeek,
        bool $avail24h,
        int $todayDayOfWeek,
        array $weekDayOfWeeks,
        string $sort,
    ): LengthAwarePaginator {
        $query = Professional::query()
            ->with(['user', 'profession'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        $this->applyListingFilters(
            $query,
            $q,
            $professionIds,
            $minAvgRating,
            $maxHourlyRateCents,
            $availToday,
            $availWeek,
            $avail24h,
            $todayDayOfWeek,
            $weekDayOfWeeks
        );

        $this->applyListingSort($query, $sort, $q);
        $query->orderBy('professionals.id');

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * @param  list<int>  $professionIds
     * @param  list<int>  $weekDayOfWeeks
     */
    private function applyListingFilters(
        Builder $query,
        string $q,
        array $professionIds,
        ?float $minAvgRating,
        int $maxHourlyRateCents,
        bool $availToday,
        bool $availWeek,
        bool $avail24h,
        int $todayDayOfWeek,
        array $weekDayOfWeeks,
    ): void {
        if ($q !== '') {
            $term = '%' . $q . '%';
            $query->where(function (Builder $sub) use ($term) {
                $sub->where('professionals.title', 'like', $term)
                    ->orWhere('professionals.description', 'like', $term)
                    ->orWhereHas('user', function (Builder $uq) use ($term) {
                        $uq->where('name', 'like', $term);
                    });
            });
        }

        if ($professionIds !== []) {
            $query->whereIn('profession_id', $professionIds);
        }

        if ($minAvgRating !== null) {
            $query->whereRaw(
                self::AVG_RATING_SUBQUERY . ' >= ?',
                [$minAvgRating]
            );
        }

        $query->where('hourly_rate_cents', '<=', $maxHourlyRateCents);

        if ($availToday) {
            $query->whereHas('availabilities', function (Builder $aq) use ($todayDayOfWeek) {
                $aq->where('day_of_week', $todayDayOfWeek);
            });
        }

        if ($availWeek) {
            $query->whereHas('availabilities', function (Builder $aq) use ($weekDayOfWeeks) {
                $aq->whereIn('day_of_week', $weekDayOfWeeks);
            });
        }

        if ($avail24h) {
            $query->whereHas('availabilities', function (Builder $aq) {
                $aq->where('is_full_day', true);
            });
        }
    }

    private function applyListingSort(Builder $query, string $sort, string $q): void
    {
        $avgSub = self::AVG_RATING_SUBQUERY;

        switch ($sort) {
            case 'rating':
                $query->orderByRaw($avgSub . ' IS NULL')
                    ->orderByRaw($avgSub . ' DESC');
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
                    $like = '%' . $q . '%';
                    $query->orderByRaw(
                        '(CASE WHEN professionals.title LIKE ? OR professionals.description LIKE ? THEN 0 ELSE 1 END)',
                        [$like, $like]
                    );
                }
                $query->orderByDesc('professionals.updated_at');
                break;
        }
    }

    public function existsForUserId(int $userId): bool
    {
        return Professional::query()->where('user_id', $userId)->exists();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Professional
    {
        return Professional::query()->create($attributes);
    }
}
