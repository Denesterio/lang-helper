<script setup>
    import { toRefs } from 'vue';

    const props = defineProps({
        tabs: Array,
        currentTab: String,
    });

    const emit = defineEmits([
        'switch-tab',
    ]);

    const { tabs } = toRefs(props);

    function switchTab(tab) {
        emit('switch-tab', tab);
    }
</script>

<template>
    <section class="window-body tabs">
        <div class="navigation">
            <menu role="tablist" aria-label="App Tabs" class="tablist">
                <button
                    v-for="tab in tabs"
                    :key="tab.id"
                    class="tab"
                    role="tab"
                    :aria-selected="currentTab === tab.id"
                    :aria-controls="`tab-${tab.id}`"
                    @click="switchTab(tab.id)"
                >
                    {{ tab.label }}
                </button>
            </menu>
            <label>{{ tabs.find((item) => item.id === currentTab).label }}</label>
        </div>
        <slot name="content"></slot>
    </section>
</template>
