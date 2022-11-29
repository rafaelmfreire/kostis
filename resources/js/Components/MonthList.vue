<script setup>
import { Inertia } from '@inertiajs/inertia'
import { provide, ref } from 'vue'

const emit = defineEmits(['onMonthChanged'])

const currentDate = ref({
    date: new Date()
})

function addMonth(add) {
    this.currentDate.date = new Date(this.currentDate.date.getFullYear(), this.currentDate.date.getMonth()+add, 1)

    emit('onMonthChanged', this.currentDate)
}

provide('date', {
    currentDate,
    addMonth
})
</script>
<template>
    <div>
        <slot></slot>
    </div>
</template>