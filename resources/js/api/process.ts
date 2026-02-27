import { get } from './fetch.ts';
import type { Button, OptionValue } from '../types/types.ts';

type ProcessParams = {
    reverse?: boolean
    dictionaryId: OptionValue
};

const route = '/api/process';

const getButtons = (): Promise<{ data: Button[] }> => {
    return get(`${route}/buttons`);
};

const getProcessWords = (params: ProcessParams) => {
    if (params.reverse) {
        return get(`${route}/translations`, params);
    }
    return get(`${route}/words`, params);
};

export {
    getButtons,
    getProcessWords,
}
