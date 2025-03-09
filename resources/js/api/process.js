import { get } from './fetch.js';

const route = '/api/process';

const getButtons = () => {
    return get(`${route}/buttons`);
};

const getProcessWords = (params = {}) => {
    if (params.reverse) {
        return get(`${route}/translations`, params);
    }
    return get(`${route}/words`, params);
};

export {
    getButtons,
    getProcessWords,
}
