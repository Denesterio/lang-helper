<template>
    <div class="title-bar">
        <div class="title-bar-text">
            <slot name="text"></slot>
        </div>
        <div v-if="$slots.controls" class="title-bar-controls">
            <slot name="controls"></slot>
        </div>
    </div>
</template>
