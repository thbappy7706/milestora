<?php

use App\Enums\TrackingMode;
use App\Models\NodeType;
use Database\Seeders\NodeTypeSeeder;

it('seeds the eight system node types', function () {
    $this->seed(NodeTypeSeeder::class);

    $types = NodeType::query()->whereNull('user_id')->get();

    expect($types->pluck('key')->all())->toEqualCanonicalizing([
        'phase',
        'section',
        'topic',
        'task',
        'project',
        'checkpoint',
        'milestone',
        'note',
    ]);

    expect($types->firstWhere('key', 'task')->default_tracking_mode)->toBe(TrackingMode::Checkbox);
});

it('is idempotent when seeded more than once', function () {
    $this->seed(NodeTypeSeeder::class);
    $this->seed(NodeTypeSeeder::class);

    expect(NodeType::query()->count())->toBe(8);
});

it('keeps the note type out of progress rollups', function () {
    $this->seed(NodeTypeSeeder::class);

    $note = NodeType::query()->where('key', 'note')->sole();

    expect($note->counts_toward_progress)->toBeFalse()
        ->and($note->isSystem())->toBeTrue();
});
