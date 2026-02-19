<?php

namespace SolutionForest\FilamentMenuManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MenuItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
        'data'    => 'array',
    ];

    public function getTable(): string
    {
        return config('filament-menu-manager.table_prefix', 'fmm_') . 'menu_items';
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(
            config('filament-menu-manager.models.menu', Menu::class)
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            config('filament-menu-manager.models.menu_item', static::class),
            'parent_id'
        );
    }

    public function children(): HasMany
    {
        return $this->hasMany(
            config('filament-menu-manager.models.menu_item', static::class),
            'parent_id'
        )->orderBy('order');
    }

    /**
     * Polymorphic relation to any Eloquent model (Post, Page, etc.)
     */
    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Resolve the final URL for this menu item.
     */
    public function getResolvedUrl(): string
    {
        if ($this->type === 'model' && $this->linkable) {
            return $this->linkable->getMenuUrl();
        }

        return $this->url ?? '#';
    }

    /**
     * Resolve the display title for this menu item.
     */
    public function getResolvedTitle(): string
    {
        return $this->title;
    }
}
