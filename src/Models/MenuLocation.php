<?php

namespace SolutionForest\FilamentMenuManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuLocation extends Model
{
    protected $guarded = [];

    public function getTable(): string
    {
        return config('filament-menu-manager.table_prefix', 'fmm_') . 'menu_locations';
    }

    public function menus(): HasMany
    {
        return $this->hasMany(
            config('filament-menu-manager.models.menu', Menu::class)
        );
    }

    public function activeMenu()
    {
        return $this->menus()->where('is_active', true)->latest()->first();
    }
}
