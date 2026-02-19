<?php

namespace NoteBrainsLab\FilamentMenuManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getTable(): string
    {
        return config('filament-menu-manager.table_prefix', 'fmm_') . 'menus';
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(
            config('filament-menu-manager.models.menu_location', MenuLocation::class),
            'menu_location_id'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            config('filament-menu-manager.models.menu_item', MenuItem::class)
        )->orderBy('order');
    }

    /**
     * Returns only root-level items (no parent).
     */
    public function rootItems(): HasMany
    {
        return $this->items()->whereNull('parent_id');
    }

    /**
     * Build a nested tree array from flat DB records.
     */
    public function getTree(): array
    {
        $items = $this->items()->get()->keyBy('id');

        $tree = [];
        foreach ($items as $item) {
            if (is_null($item->parent_id)) {
                $tree[] = $this->buildNode($item, $items);
            }
        }

        return $tree;
    }

    protected function buildNode($item, $allItems): array
    {
        $node = $item->toArray();
        $node['children'] = [];

        foreach ($allItems as $child) {
            if ($child->parent_id === $item->id) {
                $node['children'][] = $this->buildNode($child, $allItems);
            }
        }

        usort($node['children'], fn($a, $b) => $a['order'] <=> $b['order']);

        return $node;
    }
}
