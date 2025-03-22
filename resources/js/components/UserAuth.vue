<script setup>
    import AuthTab from './Tabs/TabAuth.vue';
    import TabsSection from './Tabs/TabsSection.vue';
    import RegisterTab from './Tabs/TabRegister.vue';
    import TheTitleBar from './TheTitleBar.vue';
    import { ref } from 'vue';

    const tabs = [
        { id: 'auth', label: 'Авторизация' },
        { id: 'register', label: 'Регистрация' },
    ];
    const currentTab = ref('auth');

    function switchTab(tab) {
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
