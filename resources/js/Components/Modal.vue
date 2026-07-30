<script setup lang="ts">
import { XMarkIcon } from '@heroicons/vue/24/outline';

interface Props {
  modelValue: boolean;
  closeOnClickOutside?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  closeOnClickOutside: true,
});

const emit = defineEmits<{
  'update:modelValue': [value: boolean];
  'close': [];
}>();

function handleClose() {
  if (props.closeOnClickOutside) {
    emit('update:modelValue', false);
    emit('close');
  }
}
</script>

<template>
  <Teleport to="body">
    <Transition name="fade">
      <div
        v-if="modelValue"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
        @click.self="handleClose"
      >
        <Transition name="slide-up">
          <div
            v-if="modelValue"
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
            @click.stop
          >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
              <h3 class="text-xl font-semibold">
                <slot name="title">Modal Title</slot>
              </h3>
              <button
                @click="handleClose"
                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
              >
                <XMarkIcon class="w-5 h-5" />
              </button>
            </div>

            <!-- Body -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
              <slot />
            </div>

            <!-- Footer -->
            <div v-if="$slots.footer" class="p-6 border-t border-gray-200 dark:border-gray-700">
              <slot name="footer" />
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.3s ease;
}

.slide-up-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.slide-up-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}
</style>
