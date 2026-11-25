<?php

namespace App\Models;

use App\Enums\TrackingMode;
use Database\Factories\NodeTypeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'key',
    'label',
    'icon',
    'color',
    'counts_toward_progress',
    'default_tracking_mode',
])]
class NodeType extends Model
{
    /** @use HasFactory<NodeTypeFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'counts_toward_progress' => 'boolean',
            'default_tracking_mode' => TrackingMode::class,
        ];
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
     * Determine whether this is a system-provided node type.
     */
    public function isSystem(): bool
    {
        return $this->user_id === null;
    }
}
