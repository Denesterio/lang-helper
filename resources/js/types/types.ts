type Option<T extends string|number = string|number> = {
    id: T;
    label: string;
};

type ModelOption = Option<number>;

type OptionValue = Option['id']|null;

type Language = {
    id: number,
    name: string,
    slug: string,
};

type DictionaryItem = {
    id: number,
    name: string,
    language_1_id: number,
    language_2_id: number,
    user_id: number,
};

type Dictionary = DictionaryItem & {
    language_from: Language,
    language_to: Language,
};

type DictionaryTemplate = Nullable<Pick<DictionaryItem, 'id'|'name'|'language_1_id'|'language_2_id'>>;

type Word = {
    id: number,
    word: string,
    letter: string,
    dictionary_id: number,
    show_at: string,
    translations: Word[],
    translation_text: string,
};

type WordTemplate = Nullable<Pick<Word, 'id'|'word'|'dictionary_id'|'show_at'>> & { translation_text : Word['translation_text'] };

type Button = {
    value: number,
    name: string,
};

type Nullable<T> = {
    [P in keyof T]: T[P] | null
};

type ResponseData<T> = {
    data?: T,
    error?: string|null,
};

export type {
    Option,
    ModelOption,
    OptionValue,
    Language,
    DictionaryItem,
    Dictionary,
    Word,
    WordTemplate,
    Button,
    Nullable,
    DictionaryTemplate,
    ResponseData,
}