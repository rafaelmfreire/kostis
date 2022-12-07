<script setup>
import { onMounted, reactive } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    categories: Object,
    sources: Object,
    errors: Object,
    expense: Object,
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
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <form action="#" method="POST">
                    <div class="overflow-hidden sm:rounded-md" >
                        <div class="bg-white px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <!-- <div class="col-span-6 sm:col-span-2" >
                                    <label for="cost" class="block text-sm font-medium text-gray-700" >Cost</label >
                                    <input @keypress.enter="submit(true)" @keydown="errors.cost = null" v-model="form.cost" type="number" step="0.01" name="cost" id="cost" :class="{ 'border-red-400' : errors.cost }" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                    <small class="text-red-500" v-if="errors.cost">{{ errors.cost }}</small>
                                </div> -->

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
            </div>
        </div>
    </AuthenticatedLayout>
</template>
