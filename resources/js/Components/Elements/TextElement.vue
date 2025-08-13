<script setup>
defineProps({
    content: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <div>
    <div v-html="content.text"></div>
        dadadad
    </div>
</template>
