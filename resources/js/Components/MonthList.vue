<script setup>
import { provide, ref } from 'vue'

const currentDate = ref(new Date())
provide ('currentDate', currentDate)
</script>
<template>
    <div>
        <slot></slot>
    </div>
</template>