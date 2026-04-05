<?php

namespace App\Services\Professional;

use App\Repositories\ProfessionRepository;
use App\Repositories\ProfessionalRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProfessionalListingService
{
    private const PER_PAGE = 12;

    public function __construct(
        private readonly ProfessionRepository $professionRepository,
        private readonly ProfessionalRepository $professionalRepository,
    ) {}

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
        $filterProfessions = $this->professionRepository->orderedForProfessionalsFilter();

        $selectedProfessionIds = array_values(array_filter(array_map(
            'intval',
            (array) $request->input('profession_id', [])
        )));

        $categoriaSlug = $request->string('categoria')->trim()->toString();
        if ($categoriaSlug !== '' && $selectedProfessionIds === []) {
            $pid = $this->professionRepository->findIdBySlug($categoriaSlug);
            if ($pid !== null) {
                $selectedProfessionIds = [$pid];
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

        $minAvg = $this->resolveMinAverageRating($rating5, $rating4);
        $weekDays = $availWeek ? $this->weekdaysFromTodayThroughEndOfWeek() : [];

        $professionals = $this->professionalRepository->paginateForListing(
            self::PER_PAGE,
            $q,
            $selectedProfessionIds,
            $minAvg,
            $maxPriceReais * 100,
            $availToday,
            $availWeek,
            $avail24h,
            (int) now()->dayOfWeek,
            $weekDays,
            $sort,
        );

        return [
            'professionals' => $professionals,
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
}
