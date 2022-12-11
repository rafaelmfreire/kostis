<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';

const props = defineProps({
    installments: Object,
    categories: Object,
})

function getCostByMonthAndCategory(month, category_id) {
    var cost = props.installments.filter((installment) => installment.category_id === category_id && installment.month === month)[0]
    return cost ? cost.cost_by_month : '-';
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 class="text-white tracking-wider text-sm mb-2 uppercase">{{ new Date().getFullYear() }} Expenses</h2>
                <div class="overflow-x-scroll border border-slate-400 sm:rounded-lg">
                    <table class="min-w-full">
                        <thead class="bg-slate-200">
                            <tr class="border-b border-gray-300">
                                <th class="px-2 md:px-6 py-3 text-left w-full uppercase text-xs tracking-wider font-bold">Category</th>
                                <th class="px-2 md:px-6 text-right w-full uppercase text-xs tracking-wider font-bold">Jan</th>
                                <th class="px-2 md:px-6 text-right w-full uppercase text-xs tracking-wider font-bold">Feb</th>
                                <th class="px-2 md:px-6 text-right w-full uppercase text-xs tracking-wider font-bold">Mar</th>
                                <th class="px-2 md:px-6 text-right w-full uppercase text-xs tracking-wider font-bold">Apr</th>
                                <th class="px-2 md:px-6 text-right w-full uppercase text-xs tracking-wider font-bold">May</th>
                                <th class="px-2 md:px-6 text-right w-full uppercase text-xs tracking-wider font-bold">Jun</th>
                                <th class="px-2 md:px-6 text-right w-full uppercase text-xs tracking-wider font-bold">Jul</th>
                                <th class="px-2 md:px-6 text-right w-full uppercase text-xs tracking-wider font-bold">Aug</th>
                                <th class="px-2 md:px-6 text-right w-full uppercase text-xs tracking-wider font-bold">Sep</th>
                                <th class="px-2 md:px-6 text-right w-full uppercase text-xs tracking-wider font-bold">Oct</th>
                                <th class="px-2 md:px-6 text-right w-full uppercase text-xs tracking-wider font-bold">Nov</th>
                                <th class="px-2 md:px-6 text-right w-full uppercase text-xs tracking-wider font-bold">Dec</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="category in categories" :key="category.id" class="text-sm bg-white even:bg-slate-50">
                                <td class="px-2 md:px-6 py-3 text-left space-y-2 uppercase">
                                    <span :class="`px-3 py-1 rounded font-semibold text-xs bg-${category.color}-200 text-${category.color}-700 whitespace-nowrap`">{{ category.name }}</span>
                                </td>
                                <td class="px-2 md:px-6 py-3 text-right tabular-nums space-y-2">{{ getCostByMonthAndCategory(1, category.id) }}</td>
                                <td class="px-2 md:px-6 py-3 text-right tabular-nums space-y-2">{{ getCostByMonthAndCategory(2, category.id) }}</td>
                                <td class="px-2 md:px-6 py-3 text-right tabular-nums space-y-2">{{ getCostByMonthAndCategory(3, category.id) }}</td>
                                <td class="px-2 md:px-6 py-3 text-right tabular-nums space-y-2">{{ getCostByMonthAndCategory(4, category.id) }}</td>
                                <td class="px-2 md:px-6 py-3 text-right tabular-nums space-y-2">{{ getCostByMonthAndCategory(5, category.id) }}</td>
                                <td class="px-2 md:px-6 py-3 text-right tabular-nums space-y-2">{{ getCostByMonthAndCategory(6, category.id) }}</td>
                                <td class="px-2 md:px-6 py-3 text-right tabular-nums space-y-2">{{ getCostByMonthAndCategory(7, category.id) }}</td>
                                <td class="px-2 md:px-6 py-3 text-right tabular-nums space-y-2">{{ getCostByMonthAndCategory(8, category.id) }}</td>
                                <td class="px-2 md:px-6 py-3 text-right tabular-nums space-y-2">{{ getCostByMonthAndCategory(9, category.id) }}</td>
                                <td class="px-2 md:px-6 py-3 text-right tabular-nums space-y-2">{{ getCostByMonthAndCategory(10, category.id) }}</td>
                                <td class="px-2 md:px-6 py-3 text-right tabular-nums space-y-2">{{ getCostByMonthAndCategory(11, category.id) }}</td>
                                <td class="px-2 md:px-6 py-3 text-right tabular-nums space-y-2">{{ getCostByMonthAndCategory(12, category.id) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
