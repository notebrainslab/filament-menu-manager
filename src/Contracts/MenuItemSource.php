<?php

namespace NoteBrainsLab\FilamentMenuManager\Contracts;

interface MenuItemSource
{
    /**
     * The label shown in the menu panel item list.
     */
    public function getMenuLabel(): string;

    /**
     * The URL this item resolves to when rendered in the menu.
     */
    public function getMenuUrl(): string;

    /**
     * The link target (_self, _blank, etc.).
     */
    public function getMenuTarget(): string;

    /**
     * Optional icon string (Heroicon name or null).
     */
    public function getMenuIcon(): ?string;
}
