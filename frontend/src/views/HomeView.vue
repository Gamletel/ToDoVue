<script setup lang="ts">
import axiosInstance from '@/api/axiosInstance';
import {onMounted, ref} from "vue";
import ToDoCard from "@/components/ToDoCard.vue";

interface ToDo {
  id: number,
  title: string,
  subtitle: string,
}

const data = ref<ToDo[]>([]);
const error = ref<null | string>(null);

onMounted(async () => {
  try {
    const response = await axiosInstance.get('/todo');
    console.log(response);
    data.value = response.data;
    console.log(data.value);
  } catch (e: any) {
    error.value = e.message;
    console.error('Ошибка при получении списка задач', e);
  }
});
</script>

<template>
  <div>
    <div v-if="error">Ошибка: {{ error }}</div>

    <div v-else-if="data.length > 0" class="flex flex-col items-center gap-5">
      <ToDoCard
        v-for="(item, index) in data"
        :key="index"
        :id="item.id"
        :title="item.title"
        :subtitle="item.subtitle" />
    </div>

    <div v-else-if="data.length === 0">
      <h2>Заметок нет.</h2>
      <p>Попробуйте <router-link :to="{name:'todo.create'}" class="text-blue-400 hover:text-blue-600 transition-colors">создать</router-link> первую!</p>
    </div>

    <div v-else>Загрузка...</div>
  </div>
</template>

