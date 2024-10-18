<script setup lang="ts">
import axiosInstance from "@/api/axiosInstance";
import {aw} from "vitest/dist/chunks/reporters.C4ZHgdxQ";
import {onMounted, ref} from "vue";

interface Link {
  url: string,
  label: string,
}

const emit = defineEmits(["fetchToDos"]);

const props = defineProps<{
  links: Link[];
}>();

const currentLinks = ref([]);

async function fetchPage(link) {
  console.log(link)
  try {
    const response = await axiosInstance(link);
    const data = await response.data;
    emit('fetchToDos', data.data);
    currentLinks.value = data.links;
    console.log(data);
  } catch (e) {
    console.error(e);
  }
}

onMounted(()=>{
  currentLinks.value = props.links;
})
</script>

<template>
  <nav aria-label="Page navigation example">
    <ul class="flex items-center -space-x-px h-8 text-sm justify-center mt-3">
      <li>
        <button @click="fetchPage(currentLinks[0].url)"
                class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
          <span class="sr-only">Предыдущая</span>
          <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
               viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5 1 1 5l4 4"/>
          </svg>
        </button>
      </li>


      <li v-for="(item, index) in currentLinks">
        <button v-if="index !== 0 && index !== currentLinks.length - 1" @click="fetchPage(item.url)" :key="index"
                class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
          {{ item.label }}
        </button>
      </li>

      <li>
        <button
          @click="fetchPage(currentLinks[currentLinks.length - 1].url)"
          class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
          <span class="sr-only">Следующая</span>
          <svg class="w-2.5 h-2.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
               viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m1 9 4-4-4-4"/>
          </svg>
        </button>
      </li>
    </ul>
  </nav>

</template>

<style scoped>

</style>
