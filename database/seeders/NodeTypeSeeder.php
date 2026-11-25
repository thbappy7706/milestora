<?php

namespace Database\Seeders;

use App\Enums\TrackingMode;
use App\Models\NodeType;
use Illuminate\Database\Seeder;

class NodeTypeSeeder extends Seeder
{
    /**
     * Seed the system-provided node types.
     */
    public function run(): void
    {
        $types = [
            [
                'key' => 'phase',
                'label' => 'Phase',
                'icon' => 'layers',
                'color' => '#6366f1',
                'counts_toward_progress' => true,
                'default_tracking_mode' => TrackingMode::Checklist->value,
            ],
            [
                'key' => 'section',
                'label' => 'Section',
                'icon' => 'folder',
                'color' => '#0ea5e9',
                'counts_toward_progress' => true,
                'default_tracking_mode' => TrackingMode::Checklist->value,
            ],
            [
                'key' => 'topic',
                'label' => 'Topic',
                'icon' => 'book-open',
                'color' => '#10b981',
                'counts_toward_progress' => true,
                'default_tracking_mode' => TrackingMode::Checkbox->value,
            ],
            [
                'key' => 'task',
                'label' => 'Task',
                'icon' => 'check-square',
                'color' => '#22c55e',
                'counts_toward_progress' => true,
                'default_tracking_mode' => TrackingMode::Checkbox->value,
            ],
            [
                'key' => 'project',
                'label' => 'Project',
                'icon' => 'rocket',
                'color' => '#f59e0b',
                'counts_toward_progress' => true,
                'default_tracking_mode' => TrackingMode::Checklist->value,
            ],
            [
                'key' => 'checkpoint',
                'label' => 'Checkpoint',
                'icon' => 'flag',
                'color' => '#ef4444',
                'counts_toward_progress' => true,
                'default_tracking_mode' => TrackingMode::Checkbox->value,
            ],
            [
                'key' => 'milestone',
                'label' => 'Milestone',
                'icon' => 'milestone',
                'color' => '#a855f7',
                'counts_toward_progress' => true,
                'default_tracking_mode' => TrackingMode::Checkbox->value,
            ],
            [
                'key' => 'note',
                'label' => 'Note',
                'icon' => 'sticky-note',
                'color' => '#94a3b8',
                'counts_toward_progress' => false,
                'default_tracking_mode' => TrackingMode::Checkbox->value,
            ],
        ];

        foreach ($types as $attributes) {
            NodeType::query()->updateOrCreate(
                ['user_id' => null, 'key' => $attributes['key']],
                $attributes,
            );
        }
    }
}
