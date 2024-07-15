'use strict';

export default class {
    static get(name, ...parameters) {
        return this.replace(this.list()[name] || '', parameters || []);
    }

    static list() {
        return {
            'dashboard.index': '/',
        };
    }

    static replace(route, parameters) {
        parameters.forEach((value, key) => {
            route = route.replace('/' + key + '/', '/' + value + '/');
        });

        return route;
    }
};
