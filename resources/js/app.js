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
    search: obj.search,
    init() {
        this.fetchData(obj.route);
    },
    fetchData(route) {
        const search = this.search;
        const dateRange = this.getDateRange();
        const params = {...search, ...dateRange};
        let queryString = null;
        if ((params.value && params.value.length) || (params.dateFrom && params.dateTo) || params.status) {
            queryString = objectToQueryString(params);
        }
        if (route.includes('cursor') && queryString) {
            route = `${route}&${queryString}`;
        } else if (!route.includes('cursor') && queryString) {
            route = `${route}?${queryString}`;
        }
        return fetch(route)
            .then(response => response.json())
            .then((response) => {
                this.data = response.data;
                this.links = response.links;
                this.meta = response.meta;
                this.loading = false;
            });
    },
    getDateRange() {
        const dateRange = {
            dateFrom: null,
            dateTo: null
        };
        const dateFrom = document.querySelector('#date_from');
        const dateTo = document.querySelector('#date_to');
        if (!dateFrom || !dateTo) {
            return dateRange;
        }
        const dateFromValue = dateFrom.value.trim();
        const dateToValue = dateTo.value.trim();
        if (isValidDateFormat(dateFromValue)) {
            dateRange.dateFrom = convertDateFormat(dateFromValue);
        }
        if (isValidDateFormat(dateToValue)) {
            dateRange.dateTo = convertDateFormat(dateToValue);
        }
        return dateRange;
    }
}));
Alpine.start();
// import { Modal } from 'flowbite';
//
//
// document.querySelectorAll('.modalist').forEach(element=>{
//     new Modal(element);
// });
