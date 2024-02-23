function objectToQueryString(obj) {
    const params = new URLSearchParams();
    for (const key in obj) {
        if (obj.hasOwnProperty(key)) {
            params.append(key, obj[key]);
        }
    }
    return params.toString();
}

function convertDateFormat(dateString) {
    return dayjs(dateString, 'MM/DD/YYYY').format('YYYY-MM-DD');
}

function isValidDateFormat(dateString) {
    return dayjs(dateString, 'MM/DD/YYYY', true).isValid();
}

function titleCase(str, delimiter) {
    if (!str) {
        return '';
    }
    return str
        .toString()
        .replace(/_/g, ' ') // Replace underscores with spaces
        .split(delimiter)
        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
        .join(' ');
}

function currency(num) {
    let money = (num ? num : 0).toLocaleString('en-US', {maximumFractionDigits: 2});
    return `₱${money}`;
}
