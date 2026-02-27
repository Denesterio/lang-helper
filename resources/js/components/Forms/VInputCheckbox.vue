<script setup lang="ts">
    const { id, label = '' } = defineProps<{
        id: string
        label?: string
    }>();

    const emit = defineEmits<{
        input: [boolean]
    }>();

    const model = defineModel<boolean>();

    function update(event: Event) {
        model.value = (event.target as HTMLInputElement).checked;
        emit('input', model.value);
    };
</script>

<template>
    <div>
        <input type="checkbox" :id="id" @change="update">
        <label :for="id">{{ label }}</label>
    </div>
</template>
