<script setup>
// import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';

const props = defineProps({
    expenses: Object,
})
</script>

<template>
    <Head title="Expenses" />

    <!-- <AuthenticatedLayout> -->
        <!-- <template> -->
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        <!-- </template> -->

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div v-for="expense in expenses" :key="expense.id">
                            <p>{{ new Intl.DateTimeFormat('pt-BR', { timeZone: 'GMT' }).format(new Date(expense.date)) }}</p>
                            <p>{{ expense.cost }}</p>
                            <p>{{ expense.description }}</p>
                            <p>{{ expense.observation }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- </AuthenticatedLayout> -->
</template>
