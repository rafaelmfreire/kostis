<script setup>
import { onMounted, reactive } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    categories: Object,
    sources: Object,
    errors: Object,
    expense: Object,
    installments: Object,
});

const form = reactive({
  cost: props.expense.cost_in_dollars,
  bought_at: new Date(props.expense.bought_at).getFullYear()+'-'+(new Date(props.expense.bought_at).getMonth()+1).toString().padStart(2, '0')+'-'+new Date(props.expense.bought_at).getDate().toString().padStart(2, '0'),
  paid_at: new Date(props.expense.paid_at).getFullYear()+'-'+(new Date(props.expense.paid_at).getMonth()+1).toString().padStart(2, '0')+'-'+new Date(props.expense.paid_at).getDate().toString().padStart(2, '0'),
  description: props.expense.description,
  observation: props.expense.observation,
  category_id: props.expense.category_id,
  source_id: props.expense.source_id,
});

function submit() {
    Inertia.patch(route('expenses.update', props.expense.id), this.form)
}

onMounted(() => {
    document.getElementById("bought_at").focus();
})
</script>

<template>
    <Head title="Add Expense" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Add Expense
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl grid grid-cols-5 gap-8 mx-auto sm:px-6 lg:px-8">
                <form action="#" method="POST" class="col-span-3">
                    <div class="overflow-hidden sm:rounded-md" >
                        <div class="bg-white px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-6 gap-6">

                                <div class="col-span-6 sm:col-span-2" >
                                    <label for="date" class="block text-sm font-medium text-gray-700" >Bought At</label >
                                    <input @keypress.enter="submit(true)" @keydown="errors.bought_at = null" v-model="form.bought_at" type="date" name="bought_at" id="bought_at" :class="{ 'border-red-400' : errors.bought_at }" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    <small class="text-red-500" v-if="errors.bought_at">{{ errors.bought_at }}</small>
                                </div>

                                <div class="col-span-6 sm:col-span-2" >
                                    <label for="category" class="block text-sm font-medium text-gray-700" >Category</label >
                                    <select v-model="form.category_id" @change="errors.category_id = null" :class="{ 'border-red-400' : errors.category_id }" class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                        <option :value="category.id" v-for="category in categories" :key="category">{{ category.name }}</option>
                                    </select>
                                    <small class="text-red-500" v-if="errors.category_id">{{ errors.category_id }}</small>
                                </div>

                                <div class="col-span-6 sm:col-span-2" >
                                    <label for="source" class="block text-sm font-medium text-gray-700" >Source</label >
                                    <select v-model="form.source_id" @change="errors.source_id = null" :class="{ 'border-red-400' : errors.source_id }" class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                        <option :value="source.id" v-for="source in sources" :key="source">{{ source.name }}</option>
                                    </select>
                                    <small class="text-red-500" v-if="errors.source_id">{{ errors.source_id }}</small>
                                </div>

                                <div class="col-span-6" >
                                    <label for="description" class="block text-sm font-medium text-gray-700" >Description</label >
                                    <input @keypress.enter="submit(true)" @keydown="errors.description = null" v-model="form.description" type="text" name="description" id="description" :class="{ 'border-red-400' : errors.description }" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    <small class="text-red-500" v-if="errors.description">{{ errors.description }}</small>
                                </div>

                                <div class="col-span-6">
                                    <label for="observation" class="block text-sm font-medium text-gray-700" >Observation</label >
                                    <input @keypress.enter="submit(true)" @keydown="errors.observation = null" v-model="form.observation" type="text" name="observation" id="observation" :class="{ 'border-red-400' : errors.observation }" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    <small class="text-red-500" v-if="errors.observation">{{ errors.observation }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6 space-x-4" >
                            <button type="button" @click="submit()" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" > Update </button>
                        </div>
                    </div>
                </form>

                <div class="col-span-2">
                    <div class="overflow-hidden sm:rounded-lg border border-gray-300">
                        <table class="min-w-full">
                            <thead class="bg-gray-200">
                                <tr class="border-b border-gray-300">
                                    <th class="px-6 py-3 text-right uppercase text-xs tracking-wider font-bold">Cost</th>
                                    <th class="px-6 text-left uppercase text-xs tracking-wider font-bold">Paid At</th>
                                    <th class="text-right pr-6 uppercase text-xs tracking-wider text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="installment in installments" :key="installment.id" class="uppercase text-sm bg-white even:bg-gray-50">
                                    <td class="px-6 text-right whitespace-nowrap font-mono text-base text-slate-600">
                                        <div class="flex justify-between">
                                            <span v-if="expense.installments_quantity > 1" :class="[ expense.installments_quantity == expense.number ? 'bg-green-100 text-green-600': 'font-semibold text-slate-500 bg-slate-200']" class="text-xs py-1 px-2 rounded">
                                                {{ installment.number }}/{{ expense.installments_quantity }}
                                            </span>
                                            <span v-else> </span>

                                            <div class="space-x-2 font-semibold">
                                                <span class="text-gray-300 text-xs font-sans font-normal">R$</span>
                                                <span>{{ installment.formatted_cost }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 capitalize tabular-nums">{{ installment.formatted_paid_at }}</td>
                                    <td class="px-6 text-gray-200">
                                        <div class="flex justify-end items-center">
                                            <Link :href="`/expenses/${expense.id}/installments/${installment.id}/edit`" class="cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 hover:text-indigo-500">
                                                    <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                                    <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                                </svg>
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
