<script setup>
    import TabsSection from './Tabs/TabsSection.vue';
    import TabProcess from './Tabs/TabProcess.vue';
    import TabWordAdd from './Tabs/TabWordAdd.vue';
    import TabDictionaryAdd from './Tabs/TabDictionaryAdd.vue';
    import TheTitleBar from './TheTitleBar.vue';
    import VButtonClose from './Forms/VButtonClose.vue';
    import { ref } from 'vue';
    import { onMounted, defineProps } from 'vue';
    import { logoutUser } from '../api/user.js';
    import { getDictionaries  } from '../api/dictionary.js';

    defineProps({
        user: {
            type: Object,
            required: true,
        },
    });

    const dictionaryOptions = ref([]);
    const dictionaries = ref([]);
    
    const tabs = [
        { id: 'process', label: 'Тренировка' },
        { id: 'word', label: 'Слова +' },
        { id: 'dictEdit', label: 'Словари +' },
    ];
    const currentTab = ref('process');

    onMounted(() => {
        getDictionaryOptions();
    });

    async function getDictionaryOptions() {
        const response = await getDictionaries()
        dictionaries.value = response.data;
        dictionaryOptions.value = response.data.map((dict) => ({ id: dict.id, label: dict.name }));
    };

    function switchTab(tab) {
        currentTab.value = tab;
    };

    function logout() {
        logoutUser();
    };
</script>

<template>
    <div class="window container">
        <the-title-bar :tabs="tabs" @switch-tab="switchTab">
            <template v-slot:text>Lang-Helper</template>
            <template v-slot:controls>
                <v-button-close @click.native="logout"></v-button-close>
            </template>
        </the-title-bar>

        <tabs-section :tabs="tabs" :current-tab="currentTab" @switch-tab="switchTab">
            <template v-slot:content>
                <tab-process
                    v-show="currentTab === 'process'"
                    :dictionary-options="dictionaryOptions"
                    role="tabpanel"
                    id="tab-process"
                />
                <tab-word-add
                    v-show="currentTab === 'word'"
                    :dictionary-options="dictionaryOptions"
                    :dictionaries="dictionaries"
                    role="tabpanel" 
                    id="tab-word-add"
                />
                <tab-dictionary-add
                    v-show="currentTab === 'dictEdit'"
                    :dictionary-options="dictionaryOptions"
                    :dictionaries="dictionaries"
                    role="tabpanel"
                    id="tab-dict-add"
                    @update-dictionary="getDictionaryOptions"
                />
            </template>
        </tabs-section>
    </div>
</template>
