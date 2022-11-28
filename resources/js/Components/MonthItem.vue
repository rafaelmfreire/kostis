<script setup>
import { inject, onMounted, ref } from 'vue'

defineProps(['addMonth']);

const currentDate = inject('currentDate')
var newDate = ref(null)

onMounted(() => {
    console.log(currentDate.toLocaleString('pt-BR', {
        year: "numeric",
        month: "2-digit"
    }));
    // console.log(Date.parse(currentDate.value));
    newDate = Date.parse(currentDate.value)
    // console.log(newDate.toLocaleString('pt-BR', { year: "numeric", month: "2-digit" }));
    // newDate = new Date(newDate.getFullYear(), newDate.getMonth()+addMonth, 1)
});

function getMonth() {
    return newDate.toLocaleString('pt-BR', {
        year: "numeric",
        month: "2-digit"
    })
}
</script>
<template>
    <div>
        <!-- {{ getMonth() }} -->
    </div>
</template>