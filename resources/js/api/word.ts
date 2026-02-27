import { get, post, patch, destroy } from './fetch';
import type { Word, WordTemplate, Button, ResponseData } from '../types/types.js';

const getRoute = (dictId: number, wordId: string|number = ''): string => `/api/dictionary/${dictId}/word` + (wordId == '' ? '' : `/${wordId}`);
const getTranslationRoute = (dictId: number, translationId: string|number = ''): string => `/api/dictionary/${dictId}/translation` + (translationId == '' ? '' : `/${translationId}`);

const getWords = (dictionaryId: number, params = {}): Promise<{ data: Word[] }> => {
    return get(getRoute(dictionaryId), params);
};

const storeWord = (form: WordTemplate, button?: Button['value']): Promise<ResponseData<Word>> => {
    if (!form.dictionary_id) {
        return Promise.resolve({ error: 'Словарь не выбран' });
    }
    if (form.id) {
        return patch(getRoute(form.dictionary_id, form.id), { model: form, button: button })
    }
    return post(getRoute(form.dictionary_id), { model: form });
};

const destroyWord = (word: Word): Promise<ResponseData<{ success: boolean }>> => {
    return destroy(getRoute(word.dictionary_id, word.id));
};

const storeTranslation = (form: Word, button: Button['value']|null = null) => {
    if (form.id) {
        return patch(getTranslationRoute(form.dictionary_id, form.id), { model: form, button: button })
    }
};

const destroyTranslation = (translation: Word) => {
    return destroy(getTranslationRoute(translation.dictionary_id, translation.id));
};

export {
    getWords,
    storeWord,
    destroyWord,
    storeTranslation,
    destroyTranslation,
};
