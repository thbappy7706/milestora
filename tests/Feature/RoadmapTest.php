<?php

use App\Enums\RoadmapKind;
use App\Enums\RoadmapStatus;
use App\Models\Roadmap;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected from roadmaps', function () {
    $this->get(route('roadmaps.index'))->assertRedirect(route('login'));
});

test('users only see their own roadmaps', function () {
    $user = User::factory()->create();
    Roadmap::factory()->for($user)->create(['title' => 'Mine']);
    Roadmap::factory()->create(['title' => 'Theirs']);

    $this->actingAs($user)
        ->get(route('roadmaps.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('roadmaps/index')
            ->has('roadmaps', 1)
            ->where('roadmaps.0.title', 'Mine'));
});

test('users can create a roadmap', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('roadmaps.store'), [
        'title' => 'LLM/NLP Engineer',
        'description' => 'Nine month plan',
        'kind' => RoadmapKind::Learning->value,
        'daily_goal_minutes' => 90,
        'start_date' => '2026-12-01',
        'target_end_date' => '2027-09-01',
    ]);

    $roadmap = Roadmap::query()->sole();

    $response->assertRedirect(route('roadmaps.show', $roadmap));

    expect($roadmap->user->is($user))->toBeTrue()
        ->and($roadmap->status)->toBe(RoadmapStatus::Draft)
        ->and($roadmap->slug)->not->toBeEmpty()
        ->and($roadmap->public_id)->toHaveLength(26);
});

test('creating a roadmap validates its input', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('roadmaps.create'))
        ->post(route('roadmaps.store'), [
            'title' => '',
            'kind' => 'not-a-kind',
            'daily_goal_minutes' => 5000,
            'start_date' => '2026-12-10',
            'target_end_date' => '2026-12-01',
        ])
        ->assertRedirect(route('roadmaps.create'))
        ->assertSessionHasErrors(['title', 'kind', 'daily_goal_minutes', 'target_end_date']);

    expect(Roadmap::query()->count())->toBe(0);
});

test('users cannot view another users roadmap', function () {
    $roadmap = Roadmap::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('roadmaps.show', $roadmap))
        ->assertForbidden();
});

test('owners can view their roadmap', function () {
    $user = User::factory()->create();
    $roadmap = Roadmap::factory()->for($user)->create(['title' => 'My plan']);

    $this->actingAs($user)
        ->get(route('roadmaps.show', $roadmap))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('roadmaps/show')
            ->where('roadmap.title', 'My plan')
            ->where('roadmap.nodes_count', 0));
});
