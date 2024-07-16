import { Sortable } from '@shopify/draggable';

(function () {
    let draggable;

    document.querySelectorAll('[data-draggable]').forEach((container) => {
        draggable = new Sortable(container, {
            draggable: '[data-draggable-element]',
            handle: '[data-draggable-element] [data-draggable-handle]',
            mirror: {
                appendTo: '[data-draggable="' + container.dataset.draggable + '"]',
                constrainDimensions: true
            }
        });

        draggable.on('sortable:start', (event) => {
            document.dispatchEvent(new CustomEvent('sortable:start', {
                detail: { draggable, event }
            }));
        });

        draggable.on('sortable:stop', (event) => {
            if (event.newIndex !== event.oldIndex) {
                document.dispatchEvent(new CustomEvent('sortable:stop', {
                    detail: { draggable, event }
                }));
            }
        });
    });
})();
