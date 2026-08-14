<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 40 40"
        :class="className"
        v-bind="$attrs"
    >
        <path
            fill="currentColor"
            d="M20 2.5a17.5 17.5 0 1 0 0 35 17.5 17.5 0 0 0 0-35Zm-4.8 9.25h9.35a4.7 4.7 0 0 1 0 9.4H18a2.2 2.2 0 1 0 0 4.4h8.65a1.5 1.5 0 1 1 0 3H18a5.2 5.2 0 0 1 0-10.4h6.55a1.7 1.7 0 0 0 0-3.4H15.2a1.5 1.5 0 0 1 0-3Z"
        />
    </svg>
</template>
