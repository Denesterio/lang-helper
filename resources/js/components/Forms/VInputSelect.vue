<script setup lang="ts" generic="T extends Option['id']">
import type { Option } from '../../types/types.ts';

    const model = defineModel<T|null>({ default: null });

    const { options = [], label, id, disabled = false } = defineProps<{
        id: string
        label: string
        options?: Option<T>[]
        disabled?: boolean
    }>();

    const emit = defineEmits<{
        input: [T|null]
    }>();

    function updateCurrentTab() {
        emit('input', model.value ?? null);
    };
</script>

<template>
    <div>
        <label v-if="label" :for="id">{{ label }}</label>
        <select
            v-model="model"
            class="w-100 mt-1"
            :disabled="disabled"
            @change="updateCurrentTab"
        >
            <option
                :value="null"
                selected
            >
                Выберите...
            </option>
            <option
                v-for="option in options"
                :key="option.id"
                :value="option.id"
            >
                {{ option.label }}
            </option>
        </select>
    </div>
</template>
