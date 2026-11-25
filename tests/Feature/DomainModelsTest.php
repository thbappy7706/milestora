<?php

use App\Enums\ActivityType;
use App\Enums\NodeStatus;
use App\Enums\RoadmapKind;
use App\Enums\RoadmapStatus;
use App\Models\ActivityLog;
use App\Models\Node;
use App\Models\Resource;
use App\Models\Roadmap;
use App\Models\User;

it('creates a roadmap owned by a user with a ulid and slug', function () {
    $user = User::factory()->create();
    $roadmap = Roadmap::factory()->for($user)->create(['title' => 'LLM Engineer']);

    expect($roadmap->public_id)->toHaveLength(26)
        ->and($roadmap->slug)->toStartWith('llm-engineer-')
        ->and($roadmap->kind)->toBe(RoadmapKind::Learning)
        ->and($roadmap->status)->toBe(RoadmapStatus::Draft)
        ->and($roadmap->user->is($user))->toBeTrue();
});

it('links nodes into a parent-child tree with materialized paths', function () {
    $roadmap = Roadmap::factory()->create();
    $phase = Node::factory()->for($roadmap)->create(['title' => 'Phase 1']);
    $topic = Node::factory()->for($roadmap)->childOf($phase)->create(['title' => 'Topic']);

    expect($phase->path)->toBe("/{$phase->id}/")
        ->and($topic->path)->toBe("/{$phase->id}/{$topic->id}/")
        ->and($topic->depth)->toBe(1)
        ->and($topic->parent->is($phase))->toBeTrue()
        ->and($phase->children->pluck('id')->all())->toBe([$topic->id])
        ->and($roadmap->rootNodes->pluck('id')->all())->toBe([$phase->id])
        ->and($phase->isLeaf())->toBeFalse()
        ->and($topic->isLeaf())->toBeTrue();
});

it('attaches resources and activity logs to a node', function () {
    $node = Node::factory()->create();
    $resource = Resource::factory()->for($node)->create();
    $log = ActivityLog::factory()->forNode($node)->create(['minutes' => 45]);

    expect($resource->node->is($node))->toBeTrue()
        ->and($log->node->is($node))->toBeTrue()
        ->and($log->roadmap->is($node->roadmap))->toBeTrue()
        ->and($log->user->is($node->roadmap->user))->toBeTrue()
        ->and($log->type)->toBe(ActivityType::Time)
        ->and($node->resources()->count())->toBe(1);
});

it('marks a node as done through the factory state', function () {
    $node = Node::factory()->done()->create();

    expect($node->status)->toBe(NodeStatus::Done)
        ->and($node->completed_at)->not->toBeNull();
});
