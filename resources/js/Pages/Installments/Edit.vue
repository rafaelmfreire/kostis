<script setup>
import { reactive } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    expense: Object,
    installment: Object,
    errors: Object,
});

const form = reactive({
  number: props.installment.number,
  cost: props.installment.cost_in_dollars,
  paid_at: new Date(props.installment.paid_at).getFullYear()+'-'+(new Date(props.installment.paid_at).getMonth()+1).toString().padStart(2, '0'),
});

function submit() {
    Inertia.patch(`/expenses/${props.expense.id}/installments/${props.installment.id}`, form)
}
</script>

<template>
    <Head title="Edit Installment" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Installment
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mt-5 md:col-span-2 md:mt-0">
                            <form action="#" method="POST">
                                <div class="overflow-hidden sm:rounded-md" >
                                    <div class="bg-white px-4 py-5 sm:p-6">
                                        <div class="grid grid-cols-6 gap-6">
                                            <div class="col-span-6 sm:col-span-2" >
                                                <label for="income" class="block text-sm font-medium text-gray-700" >Installment Number</label >
                                                <input @keydown="errors.number = null" v-model="form.number" type="number" step="1" min="1" name="number" id="number" :class="{ 'border-red-400' : errors.number }" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                                <small class="text-red-500" v-if="errors.number">{{ errors.number }}</small>
                                            </div>

                                            <div class="col-span-6 sm:col-span-2" >
                                                <label for="cost" class="block text-sm font-medium text-gray-700" >Cost</label >
                                                <input @keydown="errors.cost = null" v-model="form.cost" type="number" step="0.01" name="cost" id="cost" :class="{ 'border-red-400' : errors.cost }" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                                <small class="text-red-500" v-if="errors.cost">{{ errors.cost }}</small>
                                            </div>

                                            <div class="col-span-6 sm:col-span-2" >
                                                <label for="date" class="block text-sm font-medium text-gray-700" >Paid At</label >
                                                <input @keydown="errors.paid_at = null" v-model="form.paid_at" type="month" name="date" id="date" :class="{ 'border-red-400' : errors.paid_at }" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                                <small class="text-red-500" v-if="errors.paid_at">{{ errors.paid_at }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 text-right sm:px-6" >
                                        <button type="button" @click="submit()" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" > Update </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
