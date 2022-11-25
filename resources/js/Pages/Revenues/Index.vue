<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ButtonLink from "@/Components/ButtonLink.vue";
import { Head } from '@inertiajs/inertia-vue3';

const props = defineProps({
    revenues: Object,
})
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mt-5 md:col-span-2 md:mt-0">
                            <div class="overflow-hidden border border-gray-200 sm:rounded-lg">
                                <table class="min-w-full">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th class="px-6 py-2 text-right w-1 uppercase text-xs tracking-wider font-bold">Date</th>
                                            <th class="px-6 py-2 text-right w-1 uppercase text-xs tracking-wider font-bold">Income</th>
                                            <th class="px-6 py-2 text-left uppercase text-xs tracking-wider font-bold">Description</th>
                                            <th class="px-6 py-2 text-left uppercase text-xs tracking-wider font-bold">Observation</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr v-for="revenue in revenues" :key="revenue.id">
                                            <td class="px-6 py-2 text-right tabular-nums">{{ revenue.formatted_date }}</td>
                                            <td class="px-6 py-2 text-right whitespace-nowrap tabular-nums">
                                                <span class="text-gray-400 text-xs">R$</span> {{ revenue.formatted_income }}
                                            </td>
                                            <td class="px-6 py-2">{{ revenue.description }}</td>
                                            <td class="px-6 py-2">{{ revenue.observation }}</td>
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
