<script setup lang="ts">
import axiosInstance from '@/api/axiosInstance';
import {onMounted, ref} from "vue";
import ToDoCard from "@/components/ToDoCard.vue";
import Pagination from "@/components/Pagination.vue";

interface ToDo {
  id: number,
  title: string,
  subtitle: string,
  isCompleted: number,
}

interface Links{
  label: string,
  url: string,
}

enum GridType{
  LIST = 'list',
  GRID = 'grid'
}

const todos = ref<ToDo[]>([]);
const pagination = ref<Links[]>([]);
const error = ref<null | string>(null);
const gridStyle = ref('');

async function getToDos(){
  try {
    const response = await axiosInstance.get('/todo');
    console.log(response.data);
    todos.value = response.data.data;
    pagination.value = response.data.links;
  } catch (e: any) {
    error.value = e.message;
    console.error('Ошибка при получении списка задач', e);
  }
}

function removeToDo() {
  getToDos();
}

function fetchToDos(ToDos: []){
  todos.value = ToDos;
}

function changeGrid(type: GridType){
  switch (type) {
    case 'list':
      gridStyle.value = 'flex flex-col items-center';
      break;
    case 'grid':
      gridStyle.value = 'grid grid-cols-2';
      break;
  }
}

onMounted(async () => {
  changeGrid(GridType.LIST);
  await getToDos();
});
</script>

<template>
  <div>
    <div class="w-full inline-flex justify-center rounded-md shadow-sm mb-3" role="group">
      <button type="button" @click="changeGrid(GridType.LIST)" class="inline-flex gap-1 items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/>
        </svg>

        Список
      </button>

      <button type="button" @click="changeGrid(GridType.GRID)" class="inline-flex gap-1 items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.143 4H4.857A.857.857 0 0 0 4 4.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 10 9.143V4.857A.857.857 0 0 0 9.143 4Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 20 9.143V4.857A.857.857 0 0 0 19.143 4Zm-10 10H4.857a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286A.857.857 0 0 0 9.143 14Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286a.857.857 0 0 0-.857-.857Z"/>
        </svg>

        Сетка
      </button>
    </div>

    <div v-if="error">Ошибка: {{ error }}</div>

    <div v-else-if="todos.length > 0"
         :class="gridStyle"
         class="gap-5">
      <ToDoCard
        v-for="(item) in todos"
        :key="item.id"
        :id="item.id"
        :title="item.title"
        :subtitle="item.subtitle ?? ''"
        :isCompleted="item.isCompleted"
        @removeToDo="removeToDo"
      />
    </div>

    <Pagination v-if="todos.length !== 0" :links="pagination" @fetchToDos="fetchToDos" />


    <div v-else-if="todos.length === 0">
      <h2>Заметок нет.</h2>
      <p>Попробуйте <router-link :to="{name:'todo.create'}" class="text-blue-400 hover:text-blue-600 transition-colors">создать</router-link> первую!</p>
    </div>

    <div v-else>Загрузка...</div>
  </div>
</template>

