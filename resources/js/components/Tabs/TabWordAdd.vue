<script setup>
    import VInputText from '../Forms/VInputText.vue';
    import VInputSelect from '../Forms/VInputSelect.vue';
    import VInputTextarea from '../Forms/VInputTextarea.vue';
    import { useTemplateRef, nextTick, ref } from 'vue';
    import { getWords, storeWord, destroyWord  } from '../../api/word.js';
    import createTransliterator from '../../createTransliterator.js';

    const props = defineProps({
        dictionaryOptions: {
            type: Array,
            required: false,
            default: () => [],
        },
        dictionaries: {
            type: Array,
            required: false,
            default: () => [],
        },
    });

    const translationInput = useTemplateRef('translation-input');

    const wordDefault = {
        word: null,
        dictionary_id: null,
        show_at: null,
        translation_text: null,
    };
    const currentDictionary = ref(null);
    const currentDictionaryId = ref(null);
    const words = ref([]);
    const currentWord = ref({ ...wordDefault });

    let transliterate;

    function addWordToWords(words, wordObject) {
        const firstLetter = wordObject.word[0].toLowerCase();
        if (words[firstLetter]) {
            words[firstLetter].push(wordObject);
        } else {
            words[firstLetter] = [wordObject];
        }
        return words;
    };

    function transliterateInput(value, event) {
        const cursorPosition = translationInput.value.inputRef.selectionEnd;
        const index = cursorPosition - 1;
        currentWord.value.translation_text = transliterate(
            currentWord.value.translation_text,
            event.data,
            index
        );

        nextTick().then(() => {
            translationInput.value.inputRef.setSelectionRange(
            cursorPosition,
            cursorPosition
          );
        });
    };

    async function getWordOptions() {
        const response = await getWords(currentDictionaryId.value);
        words.value = response.data.reduce(addWordToWords, {});
    };

    function checkCurrentWord(wordPart) {
        if (!wordPart) return;

        const firstLetter = wordPart[0].toLowerCase();
        if (firstLetter in words.value) {
            const foundWord = words.value[firstLetter].find((item) => item.word === wordPart);
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
        const response = await storeWord(currentWord.value);
        if (response.data.id) {
            addWordToWords(words.value, response.data);
            currentWord.value = { ...wordDefault };
        }
    };

    async function deleteWord() {
        const response = await destroyWord(currentWord.value);
        if (response.data.success) {
            const firstLetter = currentWord.value.word[0].toLowerCase();
            words.value[firstLetter].filter((item) => item.id !== currentWord.value.id);
            currentWord.value = { ...wordDefault };
        }
    }

    async function setCurrentDictionary(id) {
        currentDictionary.value = props.dictionaries.find((item) => item.id === Number(id));
        currentDictionaryId.value = props.dictionaryOptions.find((item) => item.id === Number(id))?.id;
        if (currentDictionaryId.value) {
            await getWordOptions();
            transliterate = createTransliterator(
                currentDictionary.value.language_from.slug,
                currentDictionary.value.language_to.slug
            );
        }
    };
</script>

<template>
    <div>
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
            id="word"
            label="Слово"
            class="mb-2 input-dropdown"
            :readonly="currentDictionaryId == null"
            @input="checkCurrentWord"
        />
        <v-input-textarea
            v-model="currentWord.translation_text"
            ref="translation-input"
            id="translation"
            label="Перевод"
            class="mb-2"
            :readonly="currentDictionaryId == null"
            @input="transliterateInput"
        />
        <button
            v-if="!currentWord?.id"
            @click.prevent="saveWord"
        >
            Добавить
        </button>
        <template v-else>
            <button @click.prevent="saveWord">Изменить</button>
            <button @click.prevent="deleteWord">Удалить</button>
        </template>
    </div>
</template>

<style scoped>
input-dropdown {
    position: relative;
}

dropdown-input {
    position: absolute;
}
</style>
