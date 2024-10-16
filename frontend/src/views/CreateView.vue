<script setup lang="ts">
import axiosInstance from "@/api/axiosInstance";
import Button from "@/components/Button.vue";
import {ref} from "vue";
import Input from "@/components/Input.vue";
import {QuillEditor} from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const title = ref('');
const subtitle = ref('');

const loading = ref(false);

// Определяем errors как массив строк, чтобы поддерживать несколько сообщений об ошибках
const errors = ref([]);

async function onSubmit() {
  const formData = {
    title: title.value,
    subtitle: subtitle.value,
  }

  try {
    loading.value = true;
    errors.value = [];

    const response = await axiosInstance.post('todo/create', formData);
    console.log('Успешно отправлено', response.data);

    title.value = '';
    subtitle.value = '';
  } catch (error: any) {
    console.error(error);
    errors.value = error.response.data.errors;
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <main>
    <form @submit.prevent="onSubmit" class="flex flex-col gap-3 max-w-sm mx-auto">
      <div class="relative z-0 w-full group">
        <input
          type="text"
          v-model="title"
          id="title"
          class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
          required/>

        <label
          for="title"
          class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
          Заголовок
        </label>
      </div>

      <div class="relative z-0 w-full group">
        <input
          type="text"
          v-model="subtitle"
          id="subtitle"
          class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"/>

        <label
          for="subtitle"
          class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
          Подзаголовок
        </label>
      </div>

      <Button type="submit" class="mx-auto w-full" :disabled="loading">
        {{ loading ? 'Отправка...' : 'Отправить' }}
      </Button>

      <P v-for="(err, index) in errors" :key="index" class="text-xs text-red-400 dark:text-red-400">
        {{ err[0] }}
      </P>
    </form>
  </main>
</template>

<style scoped>
</style>
