<script setup>
    const model = defineModel();

    defineProps({
        options: {
            type: Array,
            default: [],
        },
        label: {
            type: String,
        },
        id: {
            type: String,
            required: true,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits([
        'input',
    ]);

    function updateCurrentTab(event) {
        model.value  = event.target.value;
        emit('input', event.target.value);
    };
</script>

<template>
    <div>
        <label v-if="label" :for="id">{{ label }}</label>
        <select
            :value="model"
            class="w-100 mt-1"
            :disabled="disabled"
            @change="updateCurrentTab"
        >
            <option
                :key="0"
                value=""
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
