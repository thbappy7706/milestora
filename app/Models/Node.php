<?php

namespace App\Models;

use App\Enums\NodeStatus;
use App\Enums\TrackingMode;
use Database\Factories\NodeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable([
    'roadmap_id',
    'parent_id',
    'node_type_id',
    'path',
    'depth',
    'position',
    'title',
    'description',
    'tracking_mode',
    'status',
    'target_value',
    'current_value',
    'unit',
    'estimate_minutes',
    'difficulty',
    'weight',
    'due_date',
    'start_date',
    'completed_at',
    'score_self',
    'evidence_url',
    'import_key',
    'confidence',
    'recurrence',
    'meta',
])]
class Node extends Model
{
    /** @use HasFactory<NodeFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tracking_mode' => TrackingMode::class,
            'status' => NodeStatus::class,
            'target_value' => 'float',
            'current_value' => 'float',
            'estimate_minutes' => 'integer',
            'difficulty' => 'integer',
            'weight' => 'float',
            'due_date' => 'date',
            'start_date' => 'date',
            'completed_at' => 'datetime',
            'progress_cached' => 'float',
            'logged_minutes_cached' => 'integer',
            'score_self' => 'integer',
            'confidence' => 'integer',
            'recurrence' => 'array',
            'meta' => 'array',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Node $node): void {
            $node->public_id ??= (string) Str::ulid();
        });
    }

    /**
     * @return BelongsTo<Roadmap, $this>
     */
    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    /**
     * @return BelongsTo<Node, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany<Node, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('position');
    }

    /**
     * @return BelongsTo<NodeType, $this>
     */
    public function nodeType(): BelongsTo
    {
        return $this->belongsTo(NodeType::class);
    }

    /**
     * @return HasMany<\App\Models\Resource, $this>
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    /**
     * @return HasMany<ActivityLog, $this>
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * @param  Builder<Node>  $query
     */
    public function scopeRoots(Builder $query): void
    {
        $query->whereNull('parent_id');
    }

    /**
     * Determine whether this node has any children.
     */
    public function isLeaf(): bool
    {
        return ! $this->children()->exists();
    }
}
