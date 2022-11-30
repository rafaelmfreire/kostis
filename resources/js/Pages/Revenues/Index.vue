<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import MonthList from "@/Components/MonthList.vue";
import MonthItem from "@/Components/MonthItem.vue";
import ButtonLink from "@/Components/ButtonLink.vue";
import { Head } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    revenues: Object,
})

function deleteItem(id) {
    if(! confirm('Are you sure you want to delete this revenue?')) {
        return;
    }

    Inertia.delete(`/revenues/${id}`, {
        preserveState: true,
    })
}

function reload(month) {
    Inertia.visit('/revenues?month='+month.date.getFullYear()+'-'+(month.date.getMonth()+1).toString().padStart(2, '0'), {
        only: ['revenues'],
        preserveState: true
    })
}
</script>

<template>
    <Head title="Revenues List" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Revenues List
                </h2>

                <ButtonLink href="/revenues/create">Add new revenue</ButtonLink>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <month-list class="flex justify-between mb-8" @on-month-changed="reload">
                    <month-item :refMonth="-5" class="px-3 py-1 bg-gray-200 rounded-md"></month-item>
                    <month-item :refMonth="-4" class="px-3 py-1 bg-gray-200 rounded-md"></month-item>
                    <month-item :refMonth="-3" class="px-3 py-1 bg-gray-200 rounded-md"></month-item>
                    <month-item :refMonth="-2" class="px-3 py-1 bg-gray-200 rounded-md"></month-item>
                    <month-item :refMonth="-1" class="px-3 py-1 bg-gray-200 rounded-md"></month-item>
                    <month-item :refMonth="0" class="px-3 py-1 bg-gray-200 rounded-md"></month-item>
                    <month-item :refMonth="1" class="px-3 py-1 bg-gray-200 rounded-md"></month-item>
                    <month-item :refMonth="2" class="px-3 py-1 bg-gray-200 rounded-md"></month-item>
                    <month-item :refMonth="3" class="px-3 py-1 bg-gray-200 rounded-md"></month-item>
                    <month-item :refMonth="4" class="px-3 py-1 bg-gray-200 rounded-md"></month-item>
                    <month-item :refMonth="5" class="px-3 py-1 bg-gray-200 rounded-md"></month-item>
                </month-list>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mt-5 md:col-span-2 md:mt-0">
                            <div class="overflow-hidden border border-gray-300 sm:rounded-lg">
                                <table class="min-w-full">
                                    <thead class="bg-gray-200">
                                        <tr class="border-b border-gray-300">
                                            <th class="px-6 py-3 text-right w-1 uppercase text-xs tracking-wider font-bold">Date</th>
                                            <th class="px-6 text-right w-1 uppercase text-xs tracking-wider font-bold">Income</th>
                                            <th class="px-6 text-left uppercase text-xs tracking-wider font-bold">Description</th>
                                            <th class="px-6 text-left uppercase text-xs tracking-wider font-bold">Observation</th>
                                            <th class="text-center uppercase text-xs tracking-wider text-gray-300">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr v-for="revenue in revenues" :key="revenue.id" class="uppercase text-sm even:bg-gray-50">
                                            <td class="px-6 py-3 text-right tabular-nums">{{ revenue.formatted_date }}</td>
                                            <td class="px-6 text-right whitespace-nowrap tabular-nums">
                                                <span class="text-gray-400 text-xs">R$</span> {{ revenue.formatted_income }}
                                            </td>
                                            <td class="px-6">{{ revenue.description }}</td>
                                            <td class="px-6">{{ revenue.observation }}</td>
                                            <td class="text-gray-200 hover:text-red-500 cursor-pointer" @click="deleteItem(revenue.id)">
                                                <span class="flex justify-center items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
