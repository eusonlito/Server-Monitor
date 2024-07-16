import Ajax from './ajax';

(function () {
    function sortableStop(event) {
        console.log(event);
        const container = event.detail.event.newContainer,
            dataset = container.dataset;

        if (!dataset || !dataset.draggableUrl) {
            return;
        }

        let ids = [];

        container.querySelectorAll('[data-draggable-element]').forEach(element => {
            ids.push(parseInt(element.dataset.draggableElement));
        });

        ids = ids.filter(id => !isNaN(id));

        if (!ids.length) {
            return;
        }

        new Ajax(dataset.draggableUrl)
            .setMethod('POST')
            .setBody({ ids })
            .send();
    }

    let timeout;

    document.addEventListener('sortable:start', event => {
        clearTimeout(timeout);
    });

    document.addEventListener('sortable:stop', event => {
        timeout = setTimeout(() => sortableStop(event), 2000);
    });
})();
