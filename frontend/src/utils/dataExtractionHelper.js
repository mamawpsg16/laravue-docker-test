export function getValues(filters){
    const filterData = Object.keys(filters).reduce((obj, key) => {
        if (Array.isArray(filters[key])) {
            if (filters[key].every(item => item && typeof item === 'object' && 'value' in item)) {
                obj[key] = filters[key].map(item => item.value);
            } else {
                obj[key] = filters[key];
            }
        } else if (!(filters[key] instanceof Object)) {
            obj[key] = filters[key];
        } else {
            obj[key] = filters[key].value;
        }
        return obj;
    }, {});

    return filterData;
}