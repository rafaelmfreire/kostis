<script setup>
import { ref } from 'vue'
import { Inertia } from "@inertiajs/inertia";

const props = defineProps(['model'])

const period = ref({
    start: new Date(new Date().getFullYear(), 1, 1),
    current: new Date()
})

function isCurrentMonth(month) {
    return month.getMonth() === period.value.current.getMonth() &&  month.getFullYear() === period.value.current.getFullYear() 
}

function reduce() {
    period.value.start = new Date(period.value.start.getFullYear(), period.value.start.getMonth()-1, period.value.start.getDate())
    console.log(period.value.start);
}

function increase() {
    period.value.start = new Date(period.value.start.getFullYear(), period.value.start.getMonth()+1, period.value.start.getDate())
    console.log(period.value.start);
}

function getMonth(month) {
    return {
        string: new Date(period.value.start.getFullYear(), period.value.start.getMonth()+month, period.value.start.getDate())
                .toLocaleString('pt-BR', { year: 'numeric', month: '2-digit' }),
        date: new Date(period.value.start.getFullYear(), period.value.start.getMonth()+month, period.value.start.getDate())
    }
}

function reload(month) {
    period.value.current = month
    let filter = month.getFullYear()+'-'+(month.getMonth()+1).toString().padStart(2, '0')
    Inertia.visit(`/${props.model}?month=${filter}`, {
        only: [`${props.model}`, 'stats'],
        preserveState: true
    })
}
</script>
<template>
    <div class="flex items-center divide-x divide-slate-400 mb-8">
        <button @click="reduce()"
            class="px-3 py-3 w-full flex justify-center items-center bg-slate-200 hover:bg-indigo-300 rounded-l-md tabular-nums" >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
            </svg>
        </button>
        <div class="flex justify-center xl:hidden">
            <button @click="reload(getMonth(new Date().getMonth()-2).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(new Date().getMonth()-2).date) }" > {{ getMonth(new Date().getMonth()-2).string }} </button>
            <button @click="reload(getMonth(new Date().getMonth()-1).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(new Date().getMonth()-1).date) }" > {{ getMonth(new Date().getMonth()-1).string }}</button>
            <button @click="reload(getMonth(new Date().getMonth()).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(new Date().getMonth()).date) }" > {{ getMonth(new Date().getMonth()).string }}</button>
        </div>
        <div class=" hidden xl:flex justify-center">
            <button @click="reload(getMonth(0).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(0).date) }" > {{ getMonth(0).string }} </button>
            <button @click="reload(getMonth(1).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(1).date) }" > {{ getMonth(1).string }}</button>
            <button @click="reload(getMonth(2).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(2).date) }" > {{ getMonth(2).string }}</button>
            <button @click="reload(getMonth(3).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(3).date) }" > {{ getMonth(3).string }}</button>
            <button @click="reload(getMonth(4).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(4).date) }" > {{ getMonth(4).string }}</button>
            <button @click="reload(getMonth(5).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(5).date) }" > {{ getMonth(5).string }}</button>
            <button @click="reload(getMonth(6).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(6).date) }" > {{ getMonth(6).string }}</button>
            <button @click="reload(getMonth(7).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(7).date) }" > {{ getMonth(7).string }}</button>
            <button @click="reload(getMonth(8).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(8).date) }" > {{ getMonth(8).string }}</button>
            <button @click="reload(getMonth(9).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(9).date) }" > {{ getMonth(9).string }}</button>
            <button @click="reload(getMonth(10).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(10).date) }" > {{ getMonth(10).string }}</button>
            <button @click="reload(getMonth(11).date)" class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums" :class="{ 'bg-indigo-400': isCurrentMonth(getMonth(11).date) }" > {{ getMonth(11).string }}</button>
        </div>
        <button @click="increase()"
            class="px-3 py-3 w-full flex justify-center items-center bg-slate-200 hover:bg-indigo-300 rounded-r-md tabular-nums" >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" />
            </svg>
        </button>
    </div>
</template>
