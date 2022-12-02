<script setup>
import MonthList from "@/Components/MonthList.vue";
import MonthItem from "@/Components/MonthItem.vue";
import { Head } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";

function reload(month) {
    Inertia.visit('/expenses?month='+month.date.getFullYear()+'-'+(month.date.getMonth()+1).toString().padStart(2, '0'), {
        only: ['expenses', 'stats'],
        preserveState: true
    })
}
</script>
<template>
    <month-list class="flex mb-8 divide-x divide-slate-300" @on-month-changed="reload">
        <button
            class="px-3 w-full flex justify-center items-center bg-slate-200 hover:bg-indigo-300 rounded-l-md tabular-nums" >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
            </svg>
        </button>
        <month-item
            :refMonth="0"
            class="px-3 py-2 bg-slate-200 hover:bg-indigo-300 tabular-nums"
        ></month-item>
        <month-item
            :refMonth="1"
            class="px-3 py-1 bg-slate-200 hover:bg-indigo-300 tabular-nums"
        ></month-item>
        <month-item
            :refMonth="2"
            class="px-3 py-1 bg-slate-200 hover:bg-indigo-300 tabular-nums"
        ></month-item>
        <month-item
            :refMonth="3"
            class="px-3 py-1 bg-slate-200 hover:bg-indigo-300 tabular-nums"
        ></month-item>
        <month-item
            :refMonth="4"
            class="px-3 py-1 bg-slate-200 hover:bg-indigo-300 tabular-nums"
        ></month-item>
        <month-item
            :refMonth="5"
            class="px-3 py-1 bg-slate-200 hover:bg-indigo-300 tabular-nums"
        ></month-item>
        <month-item
            :refMonth="6"
            class="px-3 py-1 bg-slate-200 hover:bg-indigo-300 tabular-nums"
        ></month-item>
        <month-item
            :refMonth="7"
            class="px-3 py-1 bg-slate-200 hover:bg-indigo-300 tabular-nums"
        ></month-item>
        <month-item
            :refMonth="8"
            class="px-3 py-1 bg-slate-200 hover:bg-indigo-300 tabular-nums"
        ></month-item>
        <month-item
            :refMonth="9"
            class="px-3 py-1 bg-slate-200 hover:bg-indigo-300 tabular-nums"
        ></month-item>
        <month-item
            :refMonth="10"
            class="px-3 py-1 bg-slate-200 hover:bg-indigo-300 tabular-nums"
        ></month-item>
        <month-item
            :refMonth="11"
            class="px-3 py-1 bg-slate-200 hover:bg-indigo-300 tabular-nums"
        ></month-item>
        <button
            class="px-3 py-1 w-full flex justify-center items-center bg-slate-200 hover:bg-indigo-300 rounded-r-md tabular-nums" >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" />
            </svg>
        </button>
    </month-list>
</template>
