<script setup>
    import VInputText from '../Forms/VInputText.vue';
    import VInputSelect from '../Forms/VInputSelect.vue';
    import { ref, onMounted } from 'vue';
    import { storeDictionary, destroyDictionary  } from '../../api/dictionary.js';
    import { getLanguages } from '../../api/language.js';

    defineProps({
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

    const emit = defineEmits(['update-dictionary']);

    const dictionaryDefault = {
        name: null,
        language_1_id: null,
        language_2_id: null,
    };
    const languageOptions = ref([]);
    const languages = ref([]);
    const currentDictionaryId = ref(null);
    const currentDictionary = ref({ ...dictionaryDefault });

    onMounted(() => {
        getLanguageOptions();
    });

    async function getLanguageOptions() {
        const response = await getLanguages();
        languages.value = response.data;
        languageOptions.value = response.data.map((lang) => ({ id: lang.id, label: lang.name }));
    };

    async function saveDictionary() {
        const response = await storeDictionary(currentDictionary.value);
        if (response.data.id) {
            currentDictionary.value = { ...dictionaryDefault };
            currentDictionaryId.value = null;
            emit('update-dictionary');
        }
    };

    async function deleteDictionary() {
        const response = await destroyDictionary(currentDictionaryId.value);
        if (response.data.success) {
            currentDictionary.value = { ...dictionaryDefault };
            currentDictionaryId.value = null;
            emit('update-dictionary');
        }
    }

    function setCurrentDictionary(id) {
        currentDictionary.value = props.dictionaries.find((item) => item.id === Number(id)) ?? {};
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
            v-model="currentDictionary.name"
            id="dictionary_name"
            label="Добавить словарь"
            class="mb-2"
        />
        <v-input-select
            v-model="currentDictionary.language_1_id"
            :options="languageOptions"
            id="language1"
            label="Язык 1"
            class="mb-2"
            :disabled="Boolean(currentDictionary.id)"
        />
        <v-input-select
            v-model="currentDictionary.language_2_id"
            :options="languageOptions"
            id="language2"
            label="Язык 2"
            class="mb-2"
            :disabled="Boolean(currentDictionary.id)"
        />
        <button
            v-if="!currentDictionary?.id"
            @click.prevent="saveDictionary"
        >
            Добавить
        </button>
        <template v-else>
            <button @click.prevent="saveDictionary">Переименовать</button>
            <button @click.prevent="deleteDictionary">Удалить</button>
        </template>
    </div>
</template>
