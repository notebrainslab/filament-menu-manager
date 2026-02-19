<?php

use SolutionForest\FilamentMenuManager\Models\Menu;
use SolutionForest\FilamentMenuManager\Models\MenuItem;
use SolutionForest\FilamentMenuManager\Models\MenuLocation;
use SolutionForest\FilamentMenuManager\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

// -------------------------------------------------------------------------
// MenuLocation Tests
// -------------------------------------------------------------------------

describe('MenuLocation', function () {

    it('can create a menu location', function () {
        $location = MenuLocation::create([
            'handle' => 'primary',
            'name'   => 'Primary Navigation',
        ]);

        expect($location->handle)->toBe('primary');
        expect($location->name)->toBe('Primary Navigation');
    });

    it('enforces unique handles', function () {
        MenuLocation::create(['handle' => 'primary', 'name' => 'Primary']);
        expect(fn () => MenuLocation::create(['handle' => 'primary', 'name' => 'Duplicate']))
            ->toThrow(\Illuminate\Database\QueryException::class);
    });

    it('has many menus', function () {
        $location = MenuLocation::create(['handle' => 'footer', 'name' => 'Footer']);
        Menu::create(['menu_location_id' => $location->id, 'name' => 'Footer Menu 1']);
        Menu::create(['menu_location_id' => $location->id, 'name' => 'Footer Menu 2']);

        expect($location->menus()->count())->toBe(2);
    });
});

// -------------------------------------------------------------------------
// Menu Tests
// -------------------------------------------------------------------------

describe('Menu', function () {

    it('can build a nested tree', function () {
        $location = MenuLocation::create(['handle' => 'main', 'name' => 'Main']);
        $menu     = Menu::create(['menu_location_id' => $location->id, 'name' => 'Main Menu']);

        $parent = MenuItem::create([
            'menu_id' => $menu->id,
            'title'   => 'Parent',
            'url'     => '/parent',
            'order'   => 1,
        ]);

        MenuItem::create([
            'menu_id'   => $menu->id,
            'parent_id' => $parent->id,
            'title'     => 'Child',
            'url'       => '/child',
            'order'     => 1,
        ]);

        $tree = $menu->getTree();

        expect($tree)->toHaveCount(1);
        expect($tree[0]['title'])->toBe('Parent');
        expect($tree[0]['children'])->toHaveCount(1);
        expect($tree[0]['children'][0]['title'])->toBe('Child');
    });
});

// -------------------------------------------------------------------------
// MenuItem Tests
// -------------------------------------------------------------------------

describe('MenuItem', function () {

    it('resolves url from url field for custom type', function () {
        $location = MenuLocation::create(['handle' => 'test', 'name' => 'Test']);
        $menu     = Menu::create(['menu_location_id' => $location->id, 'name' => 'Test']);

        $item = MenuItem::create([
            'menu_id' => $menu->id,
            'title'   => 'About',
            'url'     => '/about',
            'type'    => 'custom',
            'order'   => 1,
        ]);

        expect($item->getResolvedUrl())->toBe('/about');
    });

    it('can toggle enabled state', function () {
        $location = MenuLocation::create(['handle' => 'tog', 'name' => 'Tog']);
        $menu     = Menu::create(['menu_location_id' => $location->id, 'name' => 'T']);

        $item = MenuItem::create([
            'menu_id' => $menu->id,
            'title'   => 'Contact',
            'url'     => '/contact',
            'order'   => 1,
            'enabled' => true,
        ]);

        expect($item->enabled)->toBeTrue();
        $item->update(['enabled' => false]);
        expect($item->fresh()->enabled)->toBeFalse();
    });
});
