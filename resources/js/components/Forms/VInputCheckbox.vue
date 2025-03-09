<script setup>
    defineProps({
        id: {
            type: String,
            required: true,
        },
        label: {
            type: String,
            default: "",
        },
    });

    const emit = defineEmits([
        'input',
    ]);

    const model = defineModel();

    function update(event) {
        model.value = event.target.checked;
        emit('input', event.target.checked);
    };
</script>

<template>
    <div>
        <input type="checkbox" :id="id" @change="update">
        <label :for="id">{{ label }}</label>
    </div>
</template>
