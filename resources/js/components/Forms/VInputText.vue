<script setup>
    import { ref } from 'vue';

    defineProps({
        id: {
            type: String,
            required: true,
        },
        label: {
            type: String,
            default: "",
        },
        type: {
            type: String,
            default: "text",
        },
        readonly: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits(['input']);

    const inputRef = ref(null);

    defineExpose({ inputRef });

    const model = defineModel();

    function update(event) {
        model.value = event.target.value;
        emit('input', event.target.value);
    }
</script>

<template>
    <div class="field-row-stacked">
        <label :for="id">{{ label }}</label>
        <input ref="inputRef" :value="model" :id="id" :type="type" :readonly="readonly" @input="update" />
    </div>
</template>
