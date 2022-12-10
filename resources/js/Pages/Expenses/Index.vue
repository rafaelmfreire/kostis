<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import MonthFilter from "@/Components/MonthFilter.vue";
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";
import ButtonLink from "@/Components/ButtonLink.vue";

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

                <div class="grid grid-cols-2 xl:grid-cols-4 divide-x overflow-hidden sm:rounded-md mb-8 drop-shadow">
                    <div class="bg-white p-4 md:p-8 w-full">
                        <h2 class="uppercase text-gray-400 tracking-wider font-semibold mb-2 text-sm">Total Spent</h2>
                        <p class="text-2xl md:text-4xl font-semibold text-slate-800">R$ {{ stats.total_cost }}</p>
                    </div>
                    <div class="bg-white p-4 md:p-8 w-full">
                        <h2 class="uppercase text-gray-400 tracking-wider font-semibold mb-2 text-sm">Most Expensive</h2>
                        <p class="text-2xl md:text-4xl font-semibold text-slate-800">R$ {{ stats.most_expensive }}</p>
                    </div>
                    <div class="bg-white p-4 md:p-8 w-full">
                        <h2 class="uppercase text-gray-400 tracking-wider font-semibold mb-2 text-sm">Average</h2>
                        <p class="text-2xl md:text-4xl font-semibold text-slate-800">R$ {{ stats.average }}</p>
                    </div>
                    <div class="bg-white p-4 md:p-8 w-full">
                        <h2 class="uppercase text-gray-400 tracking-wider font-semibold mb-2 text-sm">Quantity</h2>
                        <p class="text-2xl md:text-4xl font-semibold text-slate-800">{{ stats.expenses_quantity }}</p>
                    </div>
                </div>

                <month-filter model="expenses"></month-filter>

                <div class="overflow-x-scroll border border-slate-400 sm:rounded-lg">
                    <table class="min-w-full">
                        <thead class="bg-slate-200">
                            <tr class="border-b border-gray-300">
                                <th class="hidden md:table-cell px-2 md:px-6 text-left uppercase text-xs tracking-wider font-bold">Source</th>
                                <th class="px-2 md:px-6 py-3 text-right uppercase text-xs tracking-wider font-bold whitespace-nowrap">Bought At</th>
                                <th class="hidden md:table-cell"></th>
                                <th class="hidden md:table-cell px-2 md:px-6 text-right uppercase text-xs tracking-wider font-bold">Cost</th>
                                <th class="hidden md:table-cell px-2 md:px-6 text-left uppercase text-xs tracking-wider font-bold">Category</th>
                                <th class="px-2 md:px-6 text-left w-full uppercase text-xs tracking-wider font-bold">Description</th>
                                <th class="px-2 md:px-6 text-left w-full uppercase text-xs tracking-wider font-bold">Observation</th>
                                <th class="px-2 md:px-6 text-left uppercase text-xs tracking-wider text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="expense in expenses" :key="expense.id" class="text-sm bg-white even:bg-slate-50">
                                <td class="hidden md:table-cell px-2 md:px-6 uppercase"><span :class="`px-3 py-1 rounded font-semibold text-xs bg-${expense.source_color}-200 text-${expense.source_color}-700 whitespace-nowrap`">{{ expense.source_name }}</span></td>
                                <td class="px-2 md:px-6 py-3 text-right tabular-nums space-y-2">
                                    <span class="block ">{{ expense.formatted_bought_at }}</span>
                                    <div class="block md:hidden text-slate-600 font-semibold text-lg space-x-2">
                                        <span v-if="expense.installments_quantity > 1" :class="[ expense.installments_quantity == expense.number ? 'bg-green-100 text-green-600': ' text-slate-600 bg-slate-200']" class="text-xs font-normal py-1 px-2 rounded">{{ expense.number }}/{{ expense.installments_quantity }}</span>
                                    	<span class="text-sm text-slate-400 font-normal">R$</span>
                                        <span>{{ expense.formatted_cost }}</span>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell">
                                    <span v-if="expense.installments_quantity > 1" :class="[ expense.installments_quantity == expense.number ? 'bg-green-100 text-green-600': ' text-slate-400 bg-slate-100']" class="text-xs py-1 px-2 rounded">{{ expense.number }}/{{ expense.installments_quantity }}</span>
                                </td>
                                <td class="hidden md:table-cell px-2 md:px-6 text-right whitespace-nowrap font-mono text-base font-semibold text-slate-600">
                                    <span class="text-gray-300 text-xs font-sans font-normal">R$</span> {{ expense.formatted_cost }}
                                </td>
                                <td class="hidden md:table-cell px-2 md:px-6 uppercase"><span :class="`px-3 py-1 rounded font-semibold text-xs bg-${expense.category_color}-200 text-${expense.category_color}-700`">{{ expense.category_name }}</span></td>
                                <td class="px-2 md:px-6 uppercase w-full whitespace-nowrap">{{ expense.description }}</td>
                                <td class="px-2 md:px-6 text-xs w-full text-gray-400">{{ expense.observation }}</td>
                                <td class="px-2 md:px-6 text-gray-200">
                                    <div class="flex justify-center items-center space-x-2">
                                        <Link :href="`/expenses/${expense.id}/edit`" class="cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 hover:text-indigo-500">
                                                <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                                <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                            </svg>
                                        </Link>
                                        <button @click="deleteItem(expense.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 hover:text-red-500">
                                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="uppercase text-sm bg-slate-300">
                                <td class="hidden md:table-cell p-6"></td>
                                <td class="hidden md:table-cell"></td>
                                <td class="hidden md:table-cell"></td>
                                <td class="px-2 md:px-6 text-right whitespace-nowrap font-mono text-lg font-semibold text-slate-900">
                                    <span class="text-gray-400 text-xs font-sans font-normal">R$</span> {{ stats.total_cost }}
                                </td>
                                <td class="hidden md:table-cell"></td>
                                <td class=""></td>
                                <td class=""></td>
                                <td class=""></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
