<script setup lang="ts">
    import { Ref, ref } from 'vue';

    const { id, label = '', type = 'text', readonly = false } = defineProps<{
        id: string
        label?: string
        type?: string
        readonly?: boolean
    }>();

    const emit = defineEmits<{
        input: [value: string]
    }>();

    const inputRef: Ref<HTMLInputElement | null> = ref(null);

    defineExpose({ inputRef });

    const model = defineModel<string|null>();

    function update(event: Event) {
        model.value = (event.target as HTMLInputElement).value;
        emit('input', (event.target as HTMLInputElement).value);
    }
</script>

<template>
    <div class="field-row-stacked">
        <label :for="id">{{ label }}</label>
        <input ref="inputRef" :value="model" :id="id" :type="type" :readonly="readonly" @input="update" />
    </div>
</template>

