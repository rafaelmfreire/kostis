<script setup>
import { Inertia } from '@inertiajs/inertia'
import { provide, ref } from 'vue'

const currentDate = ref({
    date: new Date()
})

function addMonth(add) {
    this.currentDate.date = new Date(this.currentDate.date.getFullYear(), this.currentDate.date.getMonth()+add, 1)
    console.log(
        this.currentDate.date.getFullYear()+'-'+(this.currentDate.date.getMonth()+1).toString().padStart(2, '0')
    )
    Inertia.get('/expenses', { month: this.currentDate.date.getFullYear()+'-'+(this.currentDate.date.getMonth()+1).toString().padStart(2, '0')})
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