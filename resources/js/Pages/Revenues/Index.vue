<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import MonthFilter from "@/Components/MonthFilter.vue";
import ButtonLink from "@/Components/ButtonLink.vue";
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    revenues: Object,
    stats: Object,
})

function deleteItem(id) {
    if(! confirm('Are you sure you want to delete this revenue?')) {
        return;
    }

    Inertia.delete(`/revenues/${id}`, {
        preserveState: true,
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

                <div class="flex items-center w-full divide-x overflow-hidden rounded-md mb-8 drop-shadow">
                    <div class="bg-white rounded-l-md p-8 w-1/4">
                        <h2 class="uppercase text-gray-400 tracking-wider font-semibold mb-2 text-sm">Total Earn</h2>
                        <p class="text-4xl font-semibold text-slate-800">R$ {{ stats.total_income }}</p>
                    </div>
                    <div class="bg-white p-8 w-1/4">
                        <h2 class="uppercase text-gray-400 tracking-wider font-semibold mb-2 text-sm">Most Valuable</h2>
                        <p class="text-4xl font-semibold text-slate-800">R$ {{ stats.most_valuable }}</p>
                    </div>
                    <div class="bg-white p-8 w-1/4">
                        <h2 class="uppercase text-gray-400 tracking-wider font-semibold mb-2 text-sm">Average</h2>
                        <p class="text-4xl font-semibold text-slate-800">R$ {{ stats.average }}</p>
                    </div>
                    <div class="bg-white rounded-r-md p-8 w-1/4">
                        <h2 class="uppercase text-gray-400 tracking-wider font-semibold mb-2 text-sm">Quantity of Revenues</h2>
                        <p class="text-4xl font-semibold text-slate-800">{{ stats.revenues_quantity }}</p>
                    </div>
                </div>

                <month-filter model="revenues"></month-filter>

                <div class="overflow-hidden border border-slate-400 sm:rounded-lg">
                    <table class="min-w-full">
                        <thead class="bg-slate-200">
                            <tr class="border-b border-gray-300">
                                <th class="px-6 py-3 text-right w-1 uppercase text-xs tracking-wider font-bold">Date</th>
                                <th class="px-6 text-right w-1 uppercase text-xs tracking-wider font-bold">Income</th>
                                <th class="px-6 text-left uppercase text-xs tracking-wider font-bold">Description</th>
                                <th class="px-6 text-left uppercase text-xs tracking-wider font-bold">Observation</th>
                                <th class="text-center uppercase text-xs tracking-wider text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="revenue in revenues" :key="revenue.id" class="uppercase text-sm bg-white even:bg-gray-50">
                                <td class="px-6 py-3 text-right tabular-nums">{{ revenue.formatted_date }}</td>
                                <td class="px-6 text-right whitespace-nowrap font-mono text-base font-semibold text-slate-800">
                                    <span class="text-gray-400 text-xs font-sans font-normal">R$</span>
                                    {{ revenue.formatted_income }}
                                </td>
                                <td class="px-6">{{ revenue.description }}</td>
                                <td class="px-6 text-xs text-gray-400">{{ revenue.observation }}</td>
                                <td class="px-6 text-gray-200 cursor-pointer">
                                    <div class="flex justify-center items-center space-x-2">
                                        <Link :href="`/revenues/${revenue.id}/edit`">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 hover:text-indigo-500">
                                                <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                                <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                            </svg>
                                        </Link>
                                        <span @click="deleteItem(revenue.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 hover:text-red-500">
                                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr class="uppercase text-sm bg-slate-300">
                                <td class="px-6 py-3 text-right">Total:</td>
                                <td class="px-6 text-right whitespace-nowrap font-mono text-lg font-semibold text-slate-900">
                                    <span class="text-gray-400 text-xs font-sans font-normal">R$</span> {{ stats.total_income }}
                                </td>
                                <td class="p-6"></td>
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
