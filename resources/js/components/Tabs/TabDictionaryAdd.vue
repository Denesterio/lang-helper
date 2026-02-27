<script setup lang="ts">
    import VInputText from '../Forms/VInputText.vue';
    import VInputSelect from '../Forms/VInputSelect.vue';
    import { ref, onMounted, computed } from 'vue';
    import type { Ref } from 'vue';
    import { storeDictionary, destroyDictionary  } from '../../api/dictionary';
    import { getLanguages } from '../../api/language';
    import type { Dictionary, ModelOption, Language, DictionaryTemplate } from '@/types/types';

    const props = defineProps<{
        dictionaryOptions: ModelOption[]
        dictionaries: Dictionary[]
    }>();

    const emit = defineEmits(['update-dictionary']);

    const dictionaryDefault: DictionaryTemplate = {
        id: null,
        name: null,
        language_1_id: null,
        language_2_id: null,
    };
    const languageOptions: Ref<ModelOption[]> = ref([]);
    const languages: Ref<Language[]> = ref([]);
    const currentDictionaryId: Ref<Dictionary['id']|null> = ref(null);
    const currentDictionary: Ref<Dictionary|DictionaryTemplate> = ref({ ...dictionaryDefault });

    onMounted(() => {
        getLanguageOptions();
    });

    const addButtonDisabled = computed<boolean>(() => {
        return currentDictionary.value.name === null
            || currentDictionary.value.language_1_id === null
            || currentDictionary.value.language_2_id === null;
    });

    const inputNameLabel = computed(() => {
        return currentDictionaryId.value
            ? 'Переименовать словарь'
            : 'Добавить словарь';
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
        if (currentDictionaryId.value === null) return;

        const response = await destroyDictionary(currentDictionaryId.value);
        if (response.data.success) {
            currentDictionary.value = { ...dictionaryDefault };
            currentDictionaryId.value = null;
            emit('update-dictionary');
        }
    }

    function setCurrentDictionary(id: Dictionary['id']|null) {
        currentDictionary.value = props.dictionaries.find((item) => item.id === Number(id)) ?? { ...dictionaryDefault };
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
            :label="inputNameLabel"
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
            :disabled="addButtonDisabled"
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
