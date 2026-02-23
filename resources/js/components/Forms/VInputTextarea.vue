<script setup lang="ts">
    import { Ref, ref } from 'vue';

    const { id, label = '', readonly = false } = defineProps<{
        id: string
        label?: string
        readonly?: boolean
    }>();

    const inputRef: Ref<HTMLTextAreaElement|null> = ref(null);

    defineExpose({ inputRef });

    const emit = defineEmits<{
        input: [value: string|null, event: InputEvent]
    }>();

    const model = defineModel<string>();

    function update(event: Event) {
        emit('input', model.value ?? null, event as InputEvent);
    }
</script>

<template>
    <div class="field-row-stacked">
        <label :for="id">{{ label }}</label>
        <textarea
            v-model="model"
            ref="inputRef"
            :id="id"
            :readonly="readonly"
            rows="10"
            @input="update"
        />
    </div>
</template>
