<?php

namespace App\Models;

use App\Enums\RoadmapKind;
use App\Enums\RoadmapStatus;
use Database\Factories\RoadmapFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable([
    'user_id',
    'title',
    'slug',
    'description',
    'kind',
    'status',
    'start_date',
    'target_end_date',
    'completed_at',
    'daily_goal_minutes',
    'active_weekdays',
    'timezone',
    'scoring_config',
    'source_import_id',
    'is_template',
    'is_public',
    'share_token',
    'meta',
])]
class Roadmap extends Model
{
    /** @use HasFactory<RoadmapFactory> */
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
            'kind' => RoadmapKind::class,
            'status' => RoadmapStatus::class,
            'start_date' => 'date',
            'target_end_date' => 'date',
            'completed_at' => 'datetime',
            'daily_goal_minutes' => 'integer',
            'active_weekdays' => 'array',
            'progress_cached' => 'float',
            'total_estimate_minutes' => 'integer',
            'total_logged_minutes' => 'integer',
            'scoring_config' => 'array',
            'is_template' => 'boolean',
            'is_public' => 'boolean',
            'meta' => 'array',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Roadmap $roadmap): void {
            $roadmap->public_id ??= (string) Str::ulid();
            $roadmap->slug ??= Str::slug($roadmap->title).'-'.Str::lower(Str::random(6));
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Node, $this>
     */
    public function nodes(): HasMany
    {
        return $this->hasMany(Node::class);
    }

    /**
     * @return HasMany<Node, $this>
     */
    public function rootNodes(): HasMany
    {
        return $this->nodes()->whereNull('parent_id');
    }

    /**
     * @return HasMany<NodeType, $this>
     */
    public function nodeTypes(): HasMany
    {
        return $this->hasMany(NodeType::class);
    }

    /**
     * @return HasMany<ActivityLog, $this>
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}
