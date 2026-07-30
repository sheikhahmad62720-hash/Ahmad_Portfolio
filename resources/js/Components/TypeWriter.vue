<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps<{
  texts: string[];
}>();

const displayText = ref('');
const textIndex = ref(0);
const charIndex = ref(0);
const isDeleting = ref(false);
let typingInterval: ReturnType<typeof setInterval>;

function type() {
  const currentText = props.texts[textIndex.value];
  
  if (isDeleting.value) {
    displayText.value = currentText.substring(0, charIndex.value - 1);
    charIndex.value--;
    
    if (charIndex.value === 0) {
      isDeleting.value = false;
      textIndex.value = (textIndex.value + 1) % props.texts.length;
    }
  } else {
    displayText.value = currentText.substring(0, charIndex.value + 1);
    charIndex.value++;
    
    if (charIndex.value === currentText.length) {
      setTimeout(() => {
        isDeleting.value = true;
      }, 2000);
    }
  }
}

onMounted(() => {
  typingInterval = setInterval(type, isDeleting.value ? 50 : 150);
});

onUnmounted(() => {
  clearInterval(typingInterval);
});
</script>

<template>
  <span class="inline-flex items-center">
    <span class="font-semibold text-gradient">{{ displayText }}</span>
    <span class="cursor ml-0.5">|</span>
  </span>
</template>

<style scoped>
.cursor {
  animation: blink 1s infinite;
}

@keyframes blink {
  0%, 50% {
    opacity: 1;
  }
  51%, 100% {
    opacity: 0;
  }
}
</style>
