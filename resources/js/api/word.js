import { get, post, patch, destroy } from './fetch.js';

const getRoute = (dictId, wordId = '') => `/api/dictionary/${dictId}/word` + (wordId == '' ? '' : `/${wordId}`);
const getTranslationRoute = (dictId, translationId = '') => `/api/dictionary/${dictId}/translation` + (translationId == '' ? '' : `/${translationId}`);

const getWords = (dictionaryId, params = {}) => {
    return get(getRoute(dictionaryId), params);
};

const storeWord = (form, button) => {
    if (form.id) {
        return patch(getRoute(form.dictionary_id, form.id), { model: form, button: button })
    }
    return post(getRoute(form.dictionary_id), { model: form });
};

const destroyWord = (word) => {
    return destroy(getRoute(word.dictionary_id, word.id));
};

const storeTranslation = (form, button = null) => {
    if (form.id) {
        return patch(getTranslationRoute(form.dictionary_id, form.id), { model: form, button: button })
    }
};

const destroyTranslation = (translation) => {
    return destroy(getTranslationRoute(translation.dictionary_id, translation.id));
};

export {
    getWords,
    storeWord,
    destroyWord,
    storeTranslation,
    destroyTranslation,
};
