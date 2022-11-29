<script setup>
import { inject } from 'vue'

const props = defineProps(['refMonth'])
const { currentDate, addMonth } = inject('date')

function getMonth(refMonth) {
    return new Date(this.currentDate.date.getFullYear(), this.currentDate.date.getMonth()+refMonth, 1).toLocaleString('pt-BR', { year: 'numeric', month: '2-digit' })
}

function isCurrentMonth(refMonth) {
    var current = this.currentDate.date.toLocaleString('pt-BR', { year: 'numeric', month: '2-digit' })
    var newDate = new Date(this.currentDate.date.getFullYear(), this.currentDate.date.getMonth()+props.refMonth, 1).toLocaleString('pt-BR', { year: 'numeric', month: '2-digit' })
    return current === newDate
}
</script>
<template>
    <div :class="{ 'bg-indigo-200 font-bold text-indigo-700': isCurrentMonth(refMonth) }" class="text-gray-400">
        <button class="tabular-nums" @click="addMonth(props.refMonth)">{{ getMonth(props.refMonth) }}</button>
    </div>
</template>