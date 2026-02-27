<script setup lang="ts">
    import VInputText from '../Forms/VInputText.vue';
    import VInputSelect from '../Forms/VInputSelect.vue';
    import VInputTextarea from '../Forms/VInputTextarea.vue';
    import { useTemplateRef, nextTick, ref, Ref, computed } from 'vue';
    import { getWords, storeWord, destroyWord  } from '../../api/word';
    import createTransliterator from '../../createTransliterator.js';
    import { isMobile } from '../../utils';
    import { Dictionary, Option, Word, WordTemplate } from '@/types/types';

    const props = defineProps<{
        dictionaryOptions: Option<Dictionary['id']>[]
        dictionaries: Dictionary[]
    }>();

    const isMobileAgent = isMobile();

    const translationInput = useTemplateRef<InstanceType<typeof VInputTextarea>>('translation-input');
    const wordInput = useTemplateRef<InstanceType<typeof VInputText>>('word-input');
    const translationAdd = useTemplateRef<HTMLInputElement>('translation-add');
    const translationChange = useTemplateRef<HTMLInputElement>('translation-change');
    const translationDelete = useTemplateRef<HTMLInputElement>('translation-delete');

    const wordDefault: WordTemplate = {
        id: null,
        word: null,
        dictionary_id: null,
        show_at: null,
        translation_text: '',
    };
    const currentDictionary: Ref<Dictionary|null> = ref(null);
    const currentDictionaryId: Ref<Dictionary['id']|null> = ref(null);
    const words: Ref<Record<string, Word[]>> = ref({});
    const currentWord: Ref<WordTemplate | Word> = ref({ ...wordDefault });

    const errorText: Ref<string> = ref('');

    const addButtonDisabled = computed<boolean>(() => {
        return currentDictionaryId.value === null
            || currentWord.value.word === null
            || currentWord.value.translation_text.length === 0;
    });

    let transliterate: (str: string, symbol?: string|null, index?: number) => string;

    function addWordToWords(words: Record<string, Word[]>, wordObject: Word) {
        const firstLetter = wordObject.word[0].toLowerCase();
        if (words[firstLetter]) {
            words[firstLetter] = words[firstLetter].filter((item) => item.word !== wordObject.word);
            words[firstLetter].push(wordObject);
        } else {
            words[firstLetter] = [wordObject];
        }
        return words;
    };

    function transliterateInput(value: string|null, event: InputEvent) {
        if (!translationInput.value?.inputRef) return;

        const cursorPosition = translationInput.value.inputRef.selectionEnd ?? currentWord.value.translation_text.length;
        const index = cursorPosition - 1;
        currentWord.value.translation_text = transliterate(
            currentWord.value.translation_text,
            event.data,
            index
        );

        nextTick().then(() => {
            if (!translationInput.value?.inputRef) return;

            translationInput.value.inputRef.setSelectionRange(
                cursorPosition,
                cursorPosition
            );
        });
    };

    async function getWordOptions() {
        if (!currentDictionaryId.value) return;

        const response = await getWords(currentDictionaryId.value);
        words.value = response.data.reduce(addWordToWords, {});
    };

    function checkCurrentWord(wordPart: string|null) {
        if (!wordPart) {
            currentWord.value.id = null;
            currentWord.value.translation_text = '';
            return;
        };

        const firstLetter = wordPart[0].toLowerCase();
        if (firstLetter in words.value) {
            const foundWord = words.value[firstLetter].find((item) => item.word.toLocaleLowerCase() === wordPart.toLocaleLowerCase());
            if (foundWord) {
                currentWord.value.id = foundWord.id;
                currentWord.value.translation_text = foundWord.translation_text;
            } else {
                currentWord.value.id = null;
                currentWord.value.translation_text = '';
            }
        }
    };

    async function saveWord() {
        currentWord.value.dictionary_id = currentDictionaryId.value;
        if (wordInput.value?.inputRef) {
            wordInput.value.inputRef.focus();
        }
        const response = await storeWord(currentWord.value);
        if (response.data?.id) {
            addWordToWords(words.value, response.data);
            currentWord.value = { ...wordDefault };
        } else if (response.error) {
            errorText.value = response.error;
        }
    };

    async function deleteWord() {
        if (!currentWord.value?.id) return;

        const response = await destroyWord(currentWord.value as Word);
        if (response.data?.success) {
            const firstLetter = (currentWord.value as Word).word[0].toLowerCase();
            words.value[firstLetter].filter((item) => item.id !== currentWord.value.id);
            currentWord.value = { ...wordDefault };
        }
    }

    async function setCurrentDictionary(id: Dictionary['id']|null) {
        currentDictionary.value = props.dictionaries.find((item) => item.id === Number(id)) ?? null;
        currentDictionaryId.value = props.dictionaryOptions.find((item) => item.id === Number(id))?.id ?? null;
        if (currentDictionaryId.value) {
            await getWordOptions();
            if (isMobileAgent) {
                transliterate = function (value: string): string {
                    return value;
                };
            } else {
                transliterate = createTransliterator(
                    (currentDictionary.value as Dictionary).language_from.slug,
                    (currentDictionary.value as Dictionary).language_to.slug
                );
            }
        }
    };

    function switchInput(ref: string, event: KeyboardEvent) {
        switch (ref) {
            case 'word-input':
                if (event.shiftKey && translationAdd.value) {
                    translationAdd.value.focus();
                }  else if (event.shiftKey && currentWord.value?.id && translationDelete.value) {
                    translationDelete.value.focus();
                } else if (translationInput.value?.inputRef) {
                    translationInput.value.inputRef.focus();
                }
                break;
            case 'translation-input':
                if (event.shiftKey && wordInput.value?.inputRef) {
                    wordInput.value.inputRef.focus();
                } else if (translationAdd.value) {
                    translationAdd.value.focus();
                } else if (currentWord.value?.id && translationChange.value) {
                    translationChange.value.focus();
                }
                break;
            case 'translation-add':
                if (event.shiftKey && translationInput.value?.inputRef) {
                    translationInput.value.inputRef.focus();
                } else if (wordInput.value?.inputRef) {
                    wordInput.value.inputRef.focus();
                }
                break;
            case 'translation-change':
                if (event.shiftKey && translationInput.value?.inputRef) {
                    translationInput.value.inputRef.focus();
                } else if (translationDelete.value) {
                    translationDelete.value.focus();
                }
                break;
            case 'translation-delete':
                if (event.shiftKey && translationChange.value) {
                    translationChange.value.focus();
                } else if (wordInput.value?.inputRef) {
                    wordInput.value?.inputRef.focus();
                }
                break;
        }
    };
</script>

<template>
    <div class="d-flex">
        <div class="d-inputs">
            <v-input-select
                v-model="currentDictionaryId"
                :options="dictionaryOptions"
                id="dictionaries"
                label="Список словарей"
                class="mb-2"
                @input="setCurrentDictionary"
            />
            <v-input-text
                v-model="currentWord.word"
                ref="word-input"
                id="word"
                label="Слово"
                class="mb-2 input-dropdown"
                :readonly="currentDictionaryId == null"
                @input="checkCurrentWord"
                @keydown.tab.prevent="switchInput('word-input', $event)"
            />
            <v-input-textarea
                v-model="currentWord.translation_text"
                ref="translation-input"
                id="translation"
                label="Перевод"
                class="mb-2"
                :readonly="currentDictionaryId == null"
                @input="transliterateInput"
                @keydown.tab.prevent="switchInput('translation-input', $event)"
            />
            <button
                v-if="!currentWord?.id"
                ref="translation-add"
                :disabled="addButtonDisabled"
                @click.prevent="saveWord"
                @keydown.tab.prevent="switchInput('translation-add', $event)"
            >
                Добавить
            </button>
            <template v-else>
                <button
                    ref="translation-change"
                    @click.prevent="saveWord"
                    @keydown.tab.prevent="switchInput('translation-change', $event)"
                >
                    Изменить
                </button>
                <button
                    ref="translation-delete"
                    @click.prevent="deleteWord"
                    @keydown.tab.prevent="switchInput('translation-delete', $event)"
                >
                    Удалить
                </button>
            </template>
        </div>

        <div v-if="errorText.length" class="error-block">{{ errorText }}</div>
    </div>
</template>

<style scoped>
input-dropdown {
    position: relative;
}

dropdown-input {
    position: absolute;
}

.d-flex {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.d-inputs {
    width: 100%;
}

.error-block {
    color: red;
    padding: .5rem;
    font-size: 1.2rem;
    opacity: .8;
}
</style>
