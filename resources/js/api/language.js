import { get } from './fetch.js';

const route = '/api/language';

const getLanguages = () => {
    return get(route);
};

export {
    getLanguages
}
