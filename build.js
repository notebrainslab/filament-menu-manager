const esbuild = require('esbuild');
const path = require('path');
const fs = require('fs');

// Ensure dist directory exists
const distDir = path.join(__dirname, 'resources', 'dist');
if (!fs.existsSync(distDir)) fs.mkdirSync(distDir, { recursive: true });

// Bundle JS (includes SortableJS)
esbuild.build({
    entryPoints: ['resources/js/menu-builder.js'],
    bundle: true,
    minify: true,
    outfile: 'resources/dist/filament-menu-manager.js',
    format: 'iife',
    globalName: 'FilamentMenuManager',
    external: ['alpinejs', 'livewire'], // Filament supplies these
}).then(() => {
    console.log('✅  JS bundled → resources/dist/filament-menu-manager.js');
}).catch(() => process.exit(1));

// Copy CSS (no Tailwind build needed — vanilla CSS)
fs.copyFileSync(
    path.join(__dirname, 'resources', 'css', 'menu-manager.css'),
    path.join(distDir, 'filament-menu-manager.css')
);
console.log('✅  CSS copied → resources/dist/filament-menu-manager.css');
