<script setup>
import { inject } from 'vue'

const props = defineProps(['refMonth'])
const { currentDate, addMonth } = inject('date')

function getMonth(refMonth) {
    return new Date(this.currentDate.date.getFullYear(), refMonth, 1).toLocaleString('pt-BR', { year: 'numeric', month: '2-digit' })
}

function isCurrentMonth(refMonth) {
    var current = this.currentDate.date.toLocaleString('pt-BR', { year: 'numeric', month: '2-digit' })
    var newDate = new Date(this.currentDate.date.getFullYear(), props.refMonth, 1).toLocaleString('pt-BR', { year: 'numeric', month: '2-digit' })
    return current === newDate
}
</script>
<template>
        <button :class="{ 'bg-indigo-400 font-bold': isCurrentMonth(refMonth) }" @click="addMonth(props.refMonth)">{{ getMonth(props.refMonth) }}</button>
</template>