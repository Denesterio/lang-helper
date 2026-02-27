import { get } from './fetch.js';
import type { Language } from '../types/types.ts';

const route = '/api/language';

const getLanguages = (): Promise<{ data: Language[] }> => {
    return get(route);
};

export {
    getLanguages
}
