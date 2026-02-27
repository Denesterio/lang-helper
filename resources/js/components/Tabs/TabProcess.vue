<script setup lang="ts">
    import VInputSelect from '../Forms/VInputSelect.vue';
    import VInputCheckbox from '../Forms/VInputCheckbox.vue';
    import { computed, onMounted, onUnmounted, Ref, ref } from 'vue';
    import { storeWord, storeTranslation, destroyWord, destroyTranslation } from '../../api/word.ts';
    import { getButtons, getProcessWords } from '../../api/process.ts';
    import { isMobile } from '../../utils.ts';
    import type { Option, OptionValue, Button, Word } from '../../types/types.ts';

    type Mode = 'stopped' | 'started';

    const emptyWord = {
        id: 0,
        word: '',
        letter: '',
        dictionary_id: 0,
        show_at: '',
        translations: [],
        translation_text: '',
    };

    const { dictionaryOptions } = defineProps<{
        dictionaryOptions: Option[]
    }>();

    const isMobileAgent: boolean = isMobile();

    const mode: Ref<Mode> = ref('stopped');
    const buttonValues: Ref<Button[]> = ref([]);
    const buttonCodes: Ref<Button['value'][]> = ref([]);
    const currentDictionaryId: Ref<OptionValue> = ref(null);
    const reverseDictionary: Ref<boolean> = ref(false);

    const words: Ref<Word[]> = ref([]);
    const currentWord: Ref<Word> = ref(emptyWord);
    const currentWordClosed: Ref<boolean> = ref(true);
    const currentIndex: Ref<number|null> = ref(null);

    const message = 'Словарь пуст';

    onMounted(() => {
        getButtons().then((response) => {
            buttonValues.value = response.data;
            buttonCodes.value = buttonValues.value.map((item) => item.value);
        });
    });

    onUnmounted(() => {
        deleteButtonHandler();
    });

    const isDictionaryEmpty = computed(() => {
        return words.value.length === 0;
    });

    function getRandomIndex(max: number) {
        return Math.floor(Math.random() * max);
    };

    function buttonHandler(event: KeyboardEvent) {
        const codeIndex = Number.parseInt(event.key) - 2;
        if (event.code === 'Space') {
            open();
        } else if (event.code === 'Delete') {
            deleteWord()
        } else if (event.key === '1') {
            next(false);
        } else if (buttonCodes.value[codeIndex]) {
            saveWord(buttonCodes.value[codeIndex]);
        }
    }

    function addButtonHandler() {
        document.addEventListener('keypress', buttonHandler);
    }

    function deleteButtonHandler() {
        document.removeEventListener('keypress', buttonHandler);
    };

    function start() {
        mode.value = 'started';
        addButtonHandler();
        next();
    };

    function next(splice = true) {
        currentWordClosed.value = true;
        if (splice && currentIndex.value !== null) {
            words.value.splice(currentIndex.value, 1);
        }
        if (isDictionaryEmpty.value) {
            end('Словарь пуст');
            return;
        }
        currentIndex.value = getRandomIndex(words.value.length - 1);
        currentWord.value = { ...words.value[currentIndex.value] };
    };

    function end(msg: string) {
        mode.value = 'stopped';
        deleteButtonHandler();
        currentIndex.value = null;
        currentWord.value = emptyWord;
        words.value = [];
    };

    function open() {
        currentWordClosed.value = false;
    };

    async function getWordOptions() {
        const response = await getProcessWords({ dictionaryId: currentDictionaryId.value, reverse: reverseDictionary.value });
        words.value = response.data;
    };

    async function setCurrentDictionary(id: OptionValue) {
        currentDictionaryId.value = dictionaryOptions.find((item: Option): boolean => item.id === Number(id))?.id ?? null;
        if (currentDictionaryId.value) {
            getWordOptions();
        }
    };

    async function updateReverse() {
        if (currentDictionaryId.value) {
            getWordOptions();
        }
    }

    async function saveWord(button: Button['value']) {
        const response = reverseDictionary.value
            ? await storeTranslation(currentWord.value, button)
            : await storeWord(currentWord.value, button);
        next();
    }
    async function deleteWord() {
        const response = reverseDictionary.value
            ? await destroyTranslation(currentWord.value)
            : await destroyWord(currentWord.value);
        next();
    };
</script>

<template>
    <div>
        <template v-if="mode === 'stopped'">
            <v-input-select
                v-model="currentDictionaryId"
                :options="dictionaryOptions"
                id="dictionaries"
                label="Список словарей"
                class="mb-2"
                @input="setCurrentDictionary"
            />
            <v-input-checkbox
                v-model="reverseDictionary"
                id="reverse"
                label="Обратный словарь"
                class="mb-2"
                @input="updateReverse"
            />
            <div v-if="currentDictionaryId && isDictionaryEmpty" class="process-translation-block">
                <p>{{ message }}</p>
            </div>
            <button v-if="currentDictionaryId && !isDictionaryEmpty" @click.prevent="start">Начать</button>
        </template>

        <template v-if="mode === 'started'">
            <div class="process-word-block">
                <p>{{ currentWord.word }}</p>
            </div>
            <div class="process-translation-block">
                <p :class="{ 'opacity-0': currentWordClosed }">{{ currentWord.translation_text }}</p>
            </div>
            <div class="button-container">
                <div class="process-button">
                    <button @click.prevent="next(false)">
                        {{ 'Не запомнил' + (isMobileAgent ? '' : ' (1)') }}
                    </button>
                </div>
                <div v-for="(button, index) in buttonValues" :key="button.value" class="process-button">
                    <button @click.prevent="saveWord(button.value)">
                        {{ button.name + (isMobileAgent ? '' : ` (${index + 2})`) }}
                    </button>
                </div>
                <div class="process-button">
                    <button @click.prevent="open">
                        Показать
                    </button>
                </div>
                <div v-if="!reverseDictionary" class="process-button">
                    <button @click.prevent="deleteWord">
                        Удалить
                    </button>
                </div>
                <div class="process-button">
                    <button @click.prevent="end('Тренировка остановлена')">
                        Завершить
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>
