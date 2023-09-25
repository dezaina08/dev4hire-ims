<script setup>
import { onMounted, ref } from 'vue';

defineProps(['modelValue', 'rows', 'placeholder']);

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <textarea
        :value="modelValue"
        id="about"
        :rows="rows"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm placeholder:text-gray-400"
        :placeholder="placeholder"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    ></textarea>
</template>
