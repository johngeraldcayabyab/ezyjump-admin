import './bootstrap';

import Alpine from 'alpinejs';
import 'flowbite';


window.Alpine = Alpine;
Alpine.data('table', (obj) => ({
    loading: true,
    data: [],
    links: [],
    meta: {},
    fields: obj.fields,
    init() {
        this.fetchData(obj.route).then((response) => {
            this.data = response.data;
            this.links = response.links;
            this.meta = response.meta;
            this.loading = false;
        });
    },
    fetchData(url, params = {}) {
        let queryString = null;
        if ((params.value && params.value.length) || (params.dateFrom && params.dateTo) || params.status) {
            queryString = objectToQueryString(params);
        }
        if (url.includes('cursor') && queryString) {
            url = `${url}&${queryString}`;
        } else if (!url.includes('cursor') && queryString) {
            url = `${url}?${queryString}`;
        }
        return fetch(url).then(response => response.json());
    }
}));
Alpine.start();
// import { Modal } from 'flowbite';
//
//
// document.querySelectorAll('.modalist').forEach(element=>{
//     new Modal(element);
// });
