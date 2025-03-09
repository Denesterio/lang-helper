import { get, post, patch, destroy } from './fetch.js';

const route = '/api/dictionary';

const getDictionaries = () => {
    return get(route);
};

const storeDictionary = (form) => {
    if (form.id) {
        return patch(`${route}/${form.id}`, { model: form })
    }
    return post(route, { model: form });
};

const destroyDictionary = (id) => {
    return destroy(`${route}/${id}`);
};

export {
    getDictionaries,
    storeDictionary,
    destroyDictionary,
};
