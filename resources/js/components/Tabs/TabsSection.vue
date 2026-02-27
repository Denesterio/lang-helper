<script setup lang="ts" generic="T extends Option">
import { toRefs, computed } from 'vue';
import type { Option } from '../../types/types.ts';

const props = defineProps<{
    tabs: readonly T[],
    currentTab: T['id'],
}>();

const emit = defineEmits<{
    (e: 'switch-tab', tab: T['id']): void,
}>();

const { tabs } = toRefs(props);

function switchTab(tab: T['id']) {
    emit('switch-tab', tab);
}

const currentTabLabel = computed<T['label']|''>((): T['label']|'' => tabs.value.find((item: T) => item.id === props.currentTab)?.label ?? '');

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
            <label>{{ currentTabLabel }}</label>
        </div>
        <slot name="content"></slot>
    </section>
</template>
