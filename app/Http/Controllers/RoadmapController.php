<?php

namespace App\Http\Controllers;

use App\Actions\Roadmap\CreateRoadmap;
use App\Enums\RoadmapKind;
use App\Http\Requests\Roadmap\StoreRoadmapRequest;
use App\Models\Roadmap;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class RoadmapController extends Controller
{
    use AuthorizesRequests;

    /**
     * List the current user's roadmaps.
     */
    public function index(Request $request): Response
    {
        $roadmaps = $request->user()->roadmaps()
            ->withCount('nodes')
            ->latest()
            ->get();

        return Inertia::render('roadmaps/index', [
            'roadmaps' => $roadmaps
                ->map(fn (Roadmap $roadmap): array => $this->summary($roadmap))
                ->all(),
        ]);
    }

    /**
     * Show the form for creating a roadmap.
     */
    public function create(): Response
    {
        return Inertia::render('roadmaps/create', [
            'kinds' => array_map(
                fn (RoadmapKind $kind): array => [
                    'value' => $kind->value,
                    'label' => Str::headline($kind->value),
                ],
                RoadmapKind::cases(),
            ),
        ]);
    }

    /**
     * Store a newly created roadmap.
     */
    public function store(StoreRoadmapRequest $request, CreateRoadmap $createRoadmap): RedirectResponse
    {
        /** @var array{title: string, description?: string|null, kind: string, daily_goal_minutes: int, start_date?: string|null, target_end_date?: string|null} $attributes */
        $attributes = $request->validated();

        $roadmap = $createRoadmap->handle($request->user(), $attributes);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Roadmap created.')]);

        return to_route('roadmaps.show', $roadmap);
    }

    /**
     * Display the roadmap.
     */
    public function show(Roadmap $roadmap): Response
    {
        $this->authorize('view', $roadmap);

        return Inertia::render('roadmaps/show', [
            'roadmap' => [
                ...$this->summary($roadmap),
                'nodes_count' => $roadmap->nodes()->count(),
                'daily_goal_minutes' => $roadmap->daily_goal_minutes,
            ],
        ]);
    }

    /**
     * Build the serialized roadmap summary shared by the index and show pages.
     *
     * @return array<string, mixed>
     */
    private function summary(Roadmap $roadmap): array
    {
        return [
            'id' => $roadmap->public_id,
            'title' => $roadmap->title,
            'kind' => $roadmap->kind,
            'status' => $roadmap->status,
            'description' => $roadmap->description,
            'progress' => $roadmap->progress_cached,
            'nodes_count' => $roadmap->nodes_count ?? 0,
            'start_date' => $roadmap->start_date === null ? null : Carbon::parse($roadmap->start_date)->toDateString(),
            'target_end_date' => $roadmap->target_end_date === null ? null : Carbon::parse($roadmap->target_end_date)->toDateString(),
        ];
    }
}
