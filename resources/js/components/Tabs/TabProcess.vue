<script setup>
    import VInputSelect from '../Forms/VInputSelect.vue';
    import VInputCheckbox from '../Forms/VInputCheckbox.vue';
    import { computed, onMounted, onUnmounted, ref } from 'vue';
    import { storeWord, storeTranslation, destroyWord, destroyTranslation } from '../../api/word.js';
    import { getButtons, getProcessWords } from '../../api/process.js';

    const props = defineProps({
        dictionaryOptions: {
            type: Array,
            required: false,
            default: () => [],
        },
    });

    const mode = ref('stopped');
    const buttonValues = ref([]);
    const buttonCodes = ref([]);
    const currentDictionaryId = ref(null);
    const reverseDictionary = ref(false);

    const words = ref([]);
    const currentWord = ref({});
    const currentWordClosed = ref(true);
    const currentIndex = ref(null);

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

    function getRandomIndex(max) {
        return Math.floor(Math.random() * max);
    };

    function buttonHandler(event) {
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

    function end(msg) {
        mode.value = 'stopped';
        deleteButtonHandler();
        currentIndex.value = null;
        currentWord.value = {};
        words.value = [];
    };

    function open(event) {
        currentWordClosed.value = false;
    };

    async function getWordOptions() {
        const response = await getProcessWords({ dictionaryId: currentDictionaryId.value, reverse: reverseDictionary.value });
        words.value = response.data;
    };

    async function setCurrentDictionary(id) {
        currentDictionaryId.value = props.dictionaryOptions.find((item) => item.id === Number(id))?.id;
        if (currentDictionaryId.value) {
            getWordOptions(currentDictionaryId.value);
        }
    };

    async function updateReverse() {
        if (currentDictionaryId.value) {
            getWordOptions(currentDictionaryId.value);
        }
    }

    async function saveWord(button) {
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
                        Не запомнил (1)
                    </button>
                </div>
                <div v-for="(button, index) in buttonValues" :key="button.value" class="process-button">
                    <button @click.prevent="saveWord(button.value)">
                        {{ `${button.name} (${index + 2})` }}
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