{{-- ============================================================
     Menu Builder Livewire Component
============================================================ --}}

<div>

@if(!$hasMenu)
    <div class="fmm-builder-card">
        <div class="fmm-empty">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
            </svg>
            <p class="text-sm font-semibold">No menu selected</p>
            <p class="text-xs">Create or select a menu above to start building.</p>
        </div>
    </div>
@else
    <div class="fmm-builder-card">

        {{-- Header --}}
        <div class="fmm-builder-header">
            <span>Menu Items</span>

            @if($autoSave)
                <span style="font-size:0.7rem;font-weight:500;color:var(--fmm-muted);">
                    ● Auto-save enabled
                </span>
            @endif
        </div>

        {{-- Sortable Tree --}}
        <div class="fmm-root-list fmm-nested-sortable" id="fmm-root-list">
            @forelse($items as $item)
                @include('filament-menu-manager::components.menu-item', ['item' => $item, 'depth' => 0])
            @empty
                <div class="fmm-empty" style="padding:2rem 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <p class="text-xs">Add items from the panel on the right.</p>
                </div>
            @endforelse
        </div>

    </div>

    {{-- ===================== SortableJS Init ===================== --}}
    <script>
    (function() {
        function initSortableAll() {
            document.querySelectorAll('.fmm-nested-sortable').forEach(function(el) {
                if (el._sortable) el._sortable.destroy();

                el._sortable = Sortable.create(el, {
                    group:      { name: 'menu-items', pull: true, put: true },
                    animation:  150,
                    handle:     '.fmm-drag-handle',
                    ghostClass: 'fmm-ghost',
                    chosenClass:'fmm-chosen',
                    dragClass:  'fmm-dragging',
                    swapThreshold: 0.65,
                    fallbackOnBody: true,

                    onEnd: function() {
                        var tree = extractTree(
                            document.getElementById('fmm-root-list')
                        );
                        @this.call('updateOrder', tree);
                    }
                });
            });
        }

        function extractTree(container) {
            var result = [];
            var children = container.querySelectorAll(':scope > .fmm-item-row');
            children.forEach(function(el) {
                var id = parseInt(el.dataset.id, 10);
                var nested = el.querySelector(':scope > .fmm-nested-list');
                result.push({
                    id: id,
                    children: nested ? extractTree(nested) : []
                });
            });
            return result;
        }

        document.addEventListener('DOMContentLoaded', initSortableAll);
        document.addEventListener('livewire:navigated', initSortableAll);

        Livewire.hook('commit', function({ component, commit, respond, succeed, fail }) {
            succeed(function({ snapshot, effect }) {
                queueMicrotask(initSortableAll);
            });
        });
    })();
    </script>
@endif

</div>
