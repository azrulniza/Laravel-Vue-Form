<script setup>
import { ref } from 'vue';
defineProps({
    simple: {
        type: Boolean,
        default: false
    }
});

const visible = ref(false);

const onConfigButtonClick = () => {
    visible.value = !visible.value;
};
</script>

<template>
    <div v-if="visible">
        <p>Here is the configuration content for your component.</p>
        <!-- Add your template content here -->
        <button @click="onConfigButtonClick">Toggle Config</button>
    </div>
</template>

<style lang="scss" scoped>
/* Add your styles here */
</style>
