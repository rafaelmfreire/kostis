<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import MonthList from "@/Components/MonthList.vue";
import MonthItem from "@/Components/MonthItem.vue";
import ButtonLink from "@/Components/ButtonLink.vue";
import { Head } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    expenses: Object,
    stats: Object,
})

function deleteItem(id) {
    if(! confirm('Are you sure you want to delete this expense?')) {
        return;
    }

    Inertia.delete(`/expenses/${id}`, { 
        preserveState: true,
    })
}

function reload(month) {
    Inertia.visit('/expenses?month='+month.date.getFullYear()+'-'+(month.date.getMonth()+1).toString().padStart(2, '0'), {
        only: ['expenses', 'stats'],
        preserveState: true
    })
}
</script>

<template>
    <Head title="Expenses List" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Expenses List
                </h2>

                <ButtonLink href="/expenses/create">Add new expense</ButtonLink>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="flex items-center w-full divide-x overflow-hidden rounded-md mb-8 drop-shadow">
                    <div class="bg-white rounded-l-md p-8 w-1/4">
                        <h2 class="uppercase text-gray-400 tracking-wider font-semibold mb-2 text-sm">Total Spent</h2>
                        <p class="text-4xl font-semibold text-slate-800">R$ {{ stats.total_cost }}</p>
                    </div>
                    <div class="bg-white p-8 w-1/4">
                        <h2 class="uppercase text-gray-400 tracking-wider font-semibold mb-2 text-sm">Most Expensive</h2>
                        <p class="text-4xl font-semibold text-slate-800">R$ {{ stats.most_expensive }}</p>
                    </div>
                    <div class="bg-white p-8 w-1/4">
                        <h2 class="uppercase text-gray-400 tracking-wider font-semibold mb-2 text-sm">Average</h2>
                        <p class="text-4xl font-semibold text-slate-800">R$ {{ stats.average }}</p>
                    </div>
                    <div class="bg-white rounded-r-md p-8 w-1/4">
                        <h2 class="uppercase text-gray-400 tracking-wider font-semibold mb-2 text-sm">Quantity of Expenses</h2>
                        <p class="text-4xl font-semibold text-slate-800">{{ stats.expenses_quantity }}</p>
                    </div>
                </div>

                <month-list class="flex justify-between mb-8" @on-month-changed="reload">
                    <month-item :refMonth="-5" class="px-3 py-1 bg-slate-200 hover:bg-indigo-700 rounded-md tabular-nums"></month-item>
                    <month-item :refMonth="-4" class="px-3 py-1 bg-slate-200 hover:bg-indigo-700 rounded-md tabular-nums"></month-item>
                    <month-item :refMonth="-3" class="px-3 py-1 bg-slate-200 hover:bg-indigo-700 rounded-md tabular-nums"></month-item>
                    <month-item :refMonth="-2" class="px-3 py-1 bg-slate-200 hover:bg-indigo-700 rounded-md tabular-nums"></month-item>
                    <month-item :refMonth="-1" class="px-3 py-1 bg-slate-200 hover:bg-indigo-700 rounded-md tabular-nums"></month-item>
                    <month-item :refMonth="0" class="px-3 py-1 bg-slate-200 hover:bg-indigo-700 rounded-md tabular-nums"></month-item>
                    <month-item :refMonth="1" class="px-3 py-1 bg-slate-200 hover:bg-indigo-700 rounded-md tabular-nums"></month-item>
                    <month-item :refMonth="2" class="px-3 py-1 bg-slate-200 hover:bg-indigo-700 rounded-md tabular-nums"></month-item>
                    <month-item :refMonth="3" class="px-3 py-1 bg-slate-200 hover:bg-indigo-700 rounded-md tabular-nums"></month-item>
                    <month-item :refMonth="4" class="px-3 py-1 bg-slate-200 hover:bg-indigo-700 rounded-md tabular-nums"></month-item>
                    <month-item :refMonth="5" class="px-3 py-1 bg-slate-200 hover:bg-indigo-700 rounded-md tabular-nums"></month-item>
                </month-list>

                <div class="overflow-hidden border border-slate-400 sm:rounded-lg">
                    <table class="min-w-full">
                        <thead class="bg-slate-200">
                            <tr class="border-b border-gray-300">
                                <th class="px-6 text-left uppercase text-xs tracking-wider font-bold">Source</th>
                                <th class="px-6 py-3 text-right w-1 uppercase text-xs tracking-wider font-bold">Date</th>
                                <th class="px-6 text-right w-1 uppercase text-xs tracking-wider font-bold">Cost</th>
                                <th class="px-6 text-left uppercase text-xs tracking-wider font-bold">Category</th>
                                <th class="px-6 text-left uppercase text-xs tracking-wider font-bold">Description</th>
                                <th class="px-6 text-left uppercase text-xs tracking-wider font-bold">Observation</th>
                                <th class="text-center uppercase text-xs tracking-wider text-gray-300">Delete</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="expense in expenses" :key="expense.id" class="uppercase text-sm bg-white even:bg-slate-50">
                                <td class="px-6"><span :class="`px-3 py-1 rounded font-semibold text-xs bg-${expense.source_color}-200 text-${expense.source_color}-700`">{{ expense.source_name }}</span></td>
                                <td class="px-6 py-3 text-right">{{ expense.formatted_date }}</td>
                                <td class="px-6 text-right whitespace-nowrap font-mono text-base font-semibold text-slate-800">
                                    <span class="text-gray-400 text-xs font-sans font-normal">R$</span> {{ expense.formatted_cost }}
                                </td>
                                <td class="px-6"><span :class="`px-3 py-1 rounded font-semibold text-xs bg-${expense.category_color}-200 text-${expense.category_color}-700`">{{ expense.category_name }}</span></td>
                                <td class="px-6">{{ expense.description }}</td>
                                <td class="px-6">{{ expense.observation }}</td>
                                <td class="text-gray-200 hover:text-red-500 cursor-pointer" @click="deleteItem(expense.id)">
                                    <span class="flex justify-center items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </td>
                            </tr>
                            <tr class="uppercase text-sm bg-slate-300">
                                <td class="p-6"></td>
                                <td class="px-6 py-3 text-right">Total:</td>
                                <td class="px-6 text-right whitespace-nowrap font-mono text-lg font-semibold text-slate-900">
                                    <span class="text-gray-400 text-xs font-sans font-normal">R$</span> {{ stats.total_cost }}
                                </td>
                                <td class="px-6"></td>
                                <td class="px-6"></td>
                                <td class="px-6"></td>
                                <td class=""></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
