<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from '@inertiajs/inertia-vue3';

const props = defineProps({
    expenses: Object,
})
</script>

<template>
    <Head title="Expense's List" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Expense's List
            </h2>
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
                                            <th class="px-6 py-2 text-right w-1 uppercase text-xs tracking-wider font-bold">Cost</th>
                                            <th class="px-6 py-2 text-left uppercase text-xs tracking-wider font-bold">Description</th>
                                            <th class="px-6 py-2 text-left uppercase text-xs tracking-wider font-bold">Observation</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr v-for="expense in expenses" :key="expense.id">
                                            <td class="px-6 py-2 text-right">{{ expense.formatted_date }}</td>
                                            <td class="px-6 py-2 text-right whitespace-nowrap">
                                                <span class="text-gray-400 text-xs">R$</span> {{ expense.formatted_cost }}
                                            </td>
                                            <td class="px-6 py-2">{{ expense.description }}</td>
                                            <td class="px-6 py-2">{{ expense.observation }}</td>
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
