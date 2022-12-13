<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from '@vue/reactivity';

const props = defineProps({
    installments: Object,
    categories: Object,
})

const year = ref( new Date().getFullYear() )

function getCostByMonthAndCategory(month, category_id) {
    var cost = props.installments.filter((installment) => installment.category_id == category_id && installment.month == month)[0]
    return cost ? new Intl.NumberFormat('pt-BR', {minimumFractionDigits: 2 }).format(cost.cost_by_month/100) : '-';
}

function getTotalCostByCategory(category_id) {
    var cost = props.installments.filter((installment) => installment.category_id == category_id)
    const sum = cost.reduce((accumulator, object) => {
        return accumulator + (parseInt(object.cost_by_month) / 100);
    }, 0);
    return new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(sum);
}

function getTotalCost() {
    const sum = props.installments.reduce((accumulator, object) => {
        return accumulator + (parseInt(object.cost_by_month) / 100);
    }, 0);
    return new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(sum);
}

function getTotalCostByMonth(month) {
    var cost = props.installments.filter((installment) => installment.month == month)
    const sum = cost.reduce((accumulator, object) => {
        return accumulator + (parseInt(object.cost_by_month) / 100);
    }, 0);
    return new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(sum);
}

function reload() {
    Inertia.visit(`/dashboard?year=${year.value}`, {
        only: ['installments'],
        preserveState: true
    })
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
                <label for="date" class="block text-sm text-white" >Filter By</label >
                <input @change="reload()" v-model="year" type="number" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm mb-4" />
                <div class="overflow-x-scroll xl:overflow-x-hidden border border-slate-400 sm:rounded-lg">
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
                                <th class="px-2 md:px-6 text-right w-full uppercase text-xs tracking-wider font-bold">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="category in categories" :key="category.id" class="text-sm bg-white even:bg-slate-50 divide-x">
                                <td class="px-2 md:px-6 py-3 text-left space-y-2 uppercase">
                                    <span :class="`px-3 py-1 rounded font-semibold text-xs bg-${category.color}-200 text-${category.color}-700 whitespace-nowrap`">{{ category.name }}</span>
                                </td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getCostByMonthAndCategory(1, category.id) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getCostByMonthAndCategory(2, category.id) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getCostByMonthAndCategory(3, category.id) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getCostByMonthAndCategory(4, category.id) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getCostByMonthAndCategory(5, category.id) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getCostByMonthAndCategory(6, category.id) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getCostByMonthAndCategory(7, category.id) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getCostByMonthAndCategory(8, category.id) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getCostByMonthAndCategory(9, category.id) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getCostByMonthAndCategory(10, category.id) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getCostByMonthAndCategory(11, category.id) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getCostByMonthAndCategory(12, category.id) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2 font-bold">{{ getTotalCostByCategory(category.id) }}</td>
                                
                            </tr>
                            <tr class="text-sm bg-white even:bg-slate-50 divide-x">
                                <td class="px-2 md:px-6 py-3 text-left text-xs font-semibold space-y-2 uppercase">Total</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getTotalCostByMonth(1) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getTotalCostByMonth(2) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getTotalCostByMonth(3) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getTotalCostByMonth(4) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getTotalCostByMonth(5) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getTotalCostByMonth(6) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getTotalCostByMonth(7) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getTotalCostByMonth(8) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getTotalCostByMonth(9) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getTotalCostByMonth(10) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getTotalCostByMonth(11) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2">{{ getTotalCostByMonth(12) }}</td>
                                <td class="px-2 md:px-3 py-3 text-right text-xs tabular-nums space-y-2 font-bold">{{ getTotalCost() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
