<script setup lang="ts">
    import AuthTab from './Tabs/TabAuth.vue';
    import TabsSection from './Tabs/TabsSection.vue';
    import RegisterTab from './Tabs/TabRegister.vue';
    import TheTitleBar from './TheTitleBar.vue';
    import { ref, Ref } from 'vue';
    import type { Option } from '../types/types.ts';

    const tabs = [
        { id: 'auth', label: 'Авторизация' },
        { id: 'register', label: 'Регистрация' },
    ] as const satisfies readonly Option[];
    type TabId = typeof tabs[number]['id'];

    const currentTab: Ref<TabId> = ref('auth');

    function switchTab(tab: TabId): void {
        currentTab.value = tab;
    };
</script>

<template>
    <div class="window container">
        <the-title-bar :tabs="tabs" @switch-tab="switchTab">
            <template v-slot:text>Lang-Helper</template>
        </the-title-bar>
        <tabs-section :tabs="tabs" :current-tab="currentTab" @switch-tab="switchTab">
            <template v-slot:content>
                <auth-tab v-show="currentTab === 'auth'" role="tabpanel" id="tab-auth" />
                <register-tab v-show="currentTab === 'register'" role="tabpanel" id="tab-register" />
            </template>
        </tabs-section>
    </div>
</template>
