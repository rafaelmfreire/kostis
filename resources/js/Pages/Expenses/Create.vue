<script setup>
import { onMounted, reactive } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    categories: Object,
    sources: Object,
    errors: Object,
});

const form = reactive({
  cost: null,
  bought_at: (new Date().getFullYear()+'-'+(new Date().getMonth() + 1).toString().padStart(2, '0')+'-'+(new Date().getDate()).toString().padStart(2, '0')),
  paid_at: (new Date().getFullYear()+'-'+(new Date().getMonth() + 1).toString().padStart(2, '0')+'-01'),
  description: null,
  observation: null,
});

function submit(addNew) {
    this.form.addNew = addNew
    Inertia.post(route('expenses.store'), this.form, {
        preserveState: (page) => !addNew,
    })
}

onMounted(() => {
    document.getElementById("cost").focus();
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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mt-5 md:col-span-2 md:mt-0">
                            <form action="#" method="POST">
                                <div class="overflow-hidden sm:rounded-md" >
                                    <div class="bg-white px-4 py-5 sm:p-6">
                                        <div class="grid grid-cols-10 gap-6">
                                            <div class="col-span-6 sm:col-span-2" >
                                                <label for="cost" class="block text-sm font-medium text-gray-700" >Cost</label >
                                                <input @keydown="errors.cost = null" v-model="form.cost" type="number" step="0.01" name="cost" id="cost" :class="{ 'border-red-400' : errors.cost }" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                                <small class="text-red-500" v-if="errors.cost">{{ errors.cost }}</small>
                                            </div>

                                            <div class="col-span-6 sm:col-span-2" >
                                                <label for="date" class="block text-sm font-medium text-gray-700" >Bought At</label >
                                                <input @keydown="errors.bought_at = null" v-model="form.bought_at" type="date" name="bought_at" id="bought_at" :class="{ 'border-red-400' : errors.bought_at }" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                                <small class="text-red-500" v-if="errors.bought_at">{{ errors.bought_at }}</small>
                                            </div>

                                            <div class="col-span-6 sm:col-span-2" >
                                                <label for="date" class="block text-sm font-medium text-gray-700" >Paid At</label >
                                                <input @keydown="errors.paid_at = null" v-model="form.paid_at" type="date" name="paid_at" id="paid_at" :class="{ 'border-red-400' : errors.paid_at }" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                                <small class="text-red-500" v-if="errors.paid_at">{{ errors.paid_at }}</small>
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

                                            <div class="col-span-10" >
                                                <label for="description" class="block text-sm font-medium text-gray-700" >Description</label >
                                                <input @keydown="errors.description = null" v-model="form.description" type="text" name="description" id="description" :class="{ 'border-red-400' : errors.description }" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                                <small class="text-red-500" v-if="errors.description">{{ errors.description }}</small>
                                            </div>

                                            <div class="col-span-10">
                                                <label for="observation" class="block text-sm font-medium text-gray-700" >Observation</label >
                                                <input @keydown="errors.observation = null" v-model="form.observation" type="text" name="observation" id="observation" :class="{ 'border-red-400' : errors.observation }" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                                <small class="text-red-500" v-if="errors.observation">{{ errors.observation }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 text-right sm:px-6 space-x-4" >
                                        <button type="button" @click="submit(false)" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" > Save </button>
                                        <button type="button" @click="submit(true)" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" > Save and Add New</button>
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
