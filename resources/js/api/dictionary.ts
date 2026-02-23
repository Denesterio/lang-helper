import { get, post, patch, destroy } from './fetch.js';
import type { Dictionary, DictionaryItem, DictionaryTemplate } from '../types/types.ts';

const route = '/api/dictionary';

const getDictionaries = (): Promise<{ data: Dictionary[] }> => {
    return get(route);
};

const storeDictionary = (form: DictionaryTemplate): Promise<{ data: DictionaryItem }> => {
    if (form.id) {
        return patch(`${route}/${form.id}`, { model: form })
    }
    return post(route, { model: form });
};

const destroyDictionary = (id: Dictionary['id']) => {
    return destroy(`${route}/${id}`);
};

export {
    getDictionaries,
    storeDictionary,
    destroyDictionary,
};
