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

    const inputRef = ref(null);

    defineExpose({ inputRef });

    const emit = defineEmits(['input']);

    const model = defineModel();

    function update(event) {
        model.value = event.target.value;
        emit('input', event.target.value, event);
    }
</script>

<template>
    <div class="field-row-stacked">
        <label :for="id">{{ label }}</label>
        <textarea
            :value="model"
            ref="inputRef"
            :id="id"
            :type="type"
            :readonly="readonly"
            rows="10"
            @input="update"
        />
    </div>
</template>
