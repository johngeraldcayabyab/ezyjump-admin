import './bootstrap';

import Alpine from 'alpinejs';
import 'flowbite';


window.Alpine = Alpine;
Alpine.data('table', (obj) => ({
    ...obj,
    loading: true,
    data: [],
    links: [],
    meta: {},
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
            dateFrom: '',
            dateTo: ''
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
    },
    tagColor(status) {
        console.log(status);
        let bgColor = 'bg-slate-200';
        let textColor = 'text-gray-700';
        if (status === 'CANCELED') {
            bgColor = 'bg-red-200';
            textColor = 'text-red-700';
        } else if (status === 'EXECUTED') {
            bgColor = 'bg-green-200';
            textColor = 'text-green-700';
        } else if (status === 'EXPIRED') {
            bgColor = 'bg-slate-200';
            textColor = 'text-gray-700';
        } else if (status === 'FAILED') {
            bgColor = 'bg-orange-200';
            textColor = 'text-orange-700';
        } else if (status === 'INITIAL') {
            bgColor = 'bg-blue-200';
            textColor = 'text-blue-700';
        } else if (status === 'PENDING') {
            bgColor = 'bg-orange-200';
            textColor = 'text-orange-700';
        } else if (status === 'REJECTED') {
            bgColor = 'bg-red-200';
            textColor = 'text-red-700';
        } else if (status === 'SETTLED') {
            bgColor = 'bg-green-200';
            textColor = 'text-green-700';
        } else if (status === 'FOR_ARCHIVE') {
            bgColor = 'bg-slate-200';
            textColor = 'text-gray-700';
        } else if (status === 'SUCCESS') {
            bgColor = 'bg-green-200';
            textColor = 'text-green-700';
        } else if (status === 'PROCESSING') {
            bgColor = 'bg-blue-200';
            textColor = 'text-blue-700';
        }
        return `${bgColor} ${textColor}`;
    }
}));
Alpine.start();
// import { Modal } from 'flowbite';
//
//
// document.querySelectorAll('.modalist').forEach(element=>{
//     new Modal(element);
// });
