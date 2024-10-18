<script setup lang="ts">

import {onMounted, ref, watch} from "vue";
import axiosInstance from "@/api/axiosInstance";
import Input from "@/components/Input.vue";

const props = defineProps<{
  id: number,
  title: string,
  subtitle: string,
  isCompleted: number,
}>();

const tempTitle = ref(props.title);
const tempSubtitle = ref(props.subtitle);

const isOptionsPanelOpened = ref(false);
const clickPosition = ref({x: 0, y: 0});

const isChanging = ref(false);

const emit = defineEmits(["removeToDo"]);

const isChecked = ref(!!props.isCompleted);

async function setIsCompleted(val) {
  try {
    const response = await axiosInstance.patch(`/todo/${props.id}`, {
      isCompleted: val ? 1 : 0,
    });

    console.log('Изменение задачи успешно!', response.data);
  } catch (e) {
    console.error('Ошибка во время изменения задачи!', e);
  }
}

watch(isChecked, (newVal) => {
  setIsCompleted(newVal);
})

watch(
  () => props.title,
  (newVal) => {
    tempTitle.value = newVal;
  }
);

watch(
  () => props.subtitle,
  (newVal) => {
    tempSubtitle.value = newVal;
  }
);

async function deleteTodo() {
  try {
    await axiosInstance.delete(`/todo/${props.id}`);
    console.log("Задача успешно удалена!");

    emit("removeToDo");
  } catch (e) {
    console.error("Ошибка при удалении задачи!", e);
  }
}

function cancelChanging() {
  isChanging.value = false;
  tempTitle.value = props.title;
  tempSubtitle.value = props.subtitle;
}

async function toggleOptionsPanel(event: MouseEvent) {
  if (isChanging.value === true) {
    isOptionsPanelOpened.value = false;
    return;
  }

  isOptionsPanelOpened.value = !isOptionsPanelOpened.value;
  if (isOptionsPanelOpened.value === true) {
    const todoCard = event.currentTarget as HTMLElement;
    const rect = todoCard.getBoundingClientRect();

    clickPosition.value = {
      x: event.clientX - rect.left,
      y: event.clientY - rect.top,
    };
  }
}

async function saveChanges() {
  try {
    const response = await axiosInstance.put(`/todo/${props.id}`, {
      'title': tempTitle.value,
      'subtitle': tempSubtitle.value,
    });
    const data = await response.data.todo;

    tempTitle.value = data.title;
    tempSubtitle.value = data.subtitle;
  } catch (e) {
    console.error('Ошибка при обновлении данных', e);
  } finally {
    isChanging.value = false;
  }
}
</script>

<template>
  <div
    @click="toggleOptionsPanel"
    :class="{'todo-card-disabled' : isChecked && !isChanging}"
    class="todo-card relative transition-all block  w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

    <button
      type="button"
      :style="{ top: `${clickPosition.y}px`, left: `${clickPosition.x}px` }"
      :class="isOptionsPanelOpened ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
      class="absolute z-10 transition-opacity px-3 py-2 text-xs font-medium text-center inline-flex items-center gap-1 text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
      @click="isChanging = !isChanging"
    >
      <svg class="w-[16px] h-[16px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28"/>
      </svg>

      Редактировать
    </button>

    <div class="flex gap-3 w-full justify-between mb-2">
      <button v-if="isChanging" type="button"
              @click="saveChanges"
              class="transition-all flex gap-1 items-center focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
        <svg class="w-[16px] h-[16px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
          <path fill-rule="evenodd" d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7.414A2 2 0 0 0 20.414 6L18 3.586A2 2 0 0 0 16.586 3H5Zm3 11a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v6H8v-6Zm1-7V5h6v2a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
          <path fill-rule="evenodd" d="M14 17h-4v-2h4v2Z" clip-rule="evenodd"/>
        </svg>

        Сохранить
      </button>

      <button v-if="isChanging"
              @click="cancelChanging"
              type="button"
              class="transition-all flex gap-1 items-center py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:hover:border-gray-400">
        <svg class="w-[16px] h-[16px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>

        Отменить
      </button>

      <label v-if="!isChanging" class="inline-flex items-center cursor-pointer">
        <input type="checkbox" class="sr-only peer" v-model="isChecked">
        <div
          class="relative ring-2 w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
      </label>

      <button v-if="!isChanging" @click="deleteTodo" type="button"
              class="transition-all flex items-center gap-1 py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:hover:border-gray-400">
        <svg class="w-[16px] h-[16px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
        </svg>

        Удалить
      </button>
    </div>

    <div
      :class="isChanging ? 'h-full opacity-100 pointer-events-auto' : 'max-h-0 opacity-0 pointer-events-none' "
      class="changing-panel transition-all">
      <div class="relative z-0 w-full group">
        <input
          type="text"
          v-model="tempTitle"
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
        <textarea
          type="text"
          v-model="tempSubtitle"
          id="subtitle"
          class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
          required/>

        <label
          for="subtitle"
          class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
          Подзаголовок
        </label>
      </div>
    </div>

    <div
      :class="isChanging ? 'max-h-0 opacity-0 pointer-events-none' : 'h-full opacity-100 pointer-events-auto' "
      class="main-panel">
      <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ tempTitle }}</h5>
      <p v-if="tempSubtitle && tempSubtitle !== ''" class="font-normal text-gray-700 dark:text-gray-400">{{ tempSubtitle }}</p>
    </div>
  </div>
</template>

<style scoped>

</style>
