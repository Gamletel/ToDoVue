<script setup lang="ts">
import { defineProps, defineEmits, ref, watch } from 'vue';

const props = defineProps<{
  modelValue: string;
  type?: string;
  id?: string;
  labelText?: string;
  required?: boolean;
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void;
}>();

const inputValue = ref(props.modelValue);

watch(
  () => props.modelValue,
  (newValue) => {
    inputValue.value = newValue;
  }
);

function updateValue(event: Event) {
  const target = event.target as HTMLInputElement;
  emit('update:modelValue', target.value);
}

const inputType = props.type ?? 'text';
const inputID = props.id ?? '';
const labelText = props.labelText ?? '';
const isRequired = props.required ?? false;
</script>

<template>
  <div class="relative z-0 w-full mb-5 group">
    <input
      :type="inputType"
      v-model="inputValue"
      @input="updateValue"
      :id="inputID"
      class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
      :required="isRequired" />

    <label
      :for="inputID"
      class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
      {{ labelText }}
    </label>
  </div>
</template>

<style scoped>
</style>
