<?php

namespace NoteBrainsLab\FilamentMenuManager\Concerns;

/**
 * Trait HasMenuItems
 *
 * Add this trait to any Eloquent model to make it usable as a menu item source
 * inside the Filament Menu Manager panel.
 *
 * Override methods as needed to customise the label, URL, target, or icon.
 */
trait HasMenuItems
{
    /**
     * Label shown in the menu panel. Defaults to the model's title/name attribute.
     */
    public function getMenuLabel(): string
    {
        return (string) ($this->title ?? $this->name ?? $this->label ?? $this->getKey());
    }

    /**
     * URL this item links to. Defaults to the model's url attribute or route.
     */
    public function getMenuUrl(): string
    {
        if (property_exists($this, 'url') && ! empty($this->url)) {
            return (string) $this->url;
        }

        // Attempt a common route convention: {model}.show / {models}.show
        $name = strtolower(class_basename(static::class));
        if (\Illuminate\Support\Facades\Route::has($name . '.show')) {
            return route($name . '.show', $this->getKey());
        }
        if (\Illuminate\Support\Facades\Route::has($name . 's.show')) {
            return route($name . 's.show', $this->getKey());
        }

        return '#';
    }

    /**
     * Link target. Override to return '_blank' for external links.
     */
    public function getMenuTarget(): string
    {
        return '_self';
    }

    /**
     * Optional Heroicon name (e.g. 'heroicon-o-document').
     */
    public function getMenuIcon(): ?string
    {
        return null;
    }
}
