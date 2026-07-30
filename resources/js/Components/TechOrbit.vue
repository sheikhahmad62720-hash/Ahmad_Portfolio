<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

const techStack = [
  { name: 'Laravel', icon: '🎯' },
  { name: 'Vue.js', icon: '💚' },
  { name: 'PHP', icon: '🐘' },
  { name: 'TypeScript', icon: '📘' },
  { name: 'Tailwind', icon: '🎨' },
  { name: 'MySQL', icon: '🗄️' },
  { name: 'Git', icon: '🔀' },
  { name: 'Docker', icon: '🐳' },
];

const orbitRef = ref<HTMLElement | null>(null);
const isDragging = ref(false);
const isPaused = ref(false);
let rotationAngle = 0;

function getTechPosition(index: number) {
  const angle = (index / techStack.length) * 360;
  const radius = 140;
  const x = Math.cos((angle * Math.PI) / 180) * radius;
  const y = Math.sin((angle * Math.PI) / 180) * radius;
  
  return {
    transform: `translate(-50%, -50%) translate(${x}px, ${y}px) rotateY(${angle}deg)`,
    left: '50%',
    top: '50%',
  };
}

function pauseRotation() {
  isPaused.value = true;
}

function resumeRotation() {
  if (!isDragging.value) {
    isPaused.value = false;
  }
}

function startDrag(e: MouseEvent) {
  isDragging.value = true;
  const startX = e.clientX;
  let currentRotation = rotationAngle;

  const onMouseMove = (moveEvent: MouseEvent) => {
    const deltaX = moveEvent.clientX - startX;
    rotationAngle = currentRotation + deltaX * 0.5;
    if (orbitRef.value) {
      orbitRef.value.style.transform = `rotateY(${rotationAngle}deg)`;
    }
  };

  const onMouseUp = () => {
    isDragging.value = false;
    document.removeEventListener('mousemove', onMouseMove);
    document.removeEventListener('mouseup', onMouseUp);
    
    setTimeout(() => {
      if (!isPaused.value) {
        resumeRotation();
      }
    }, 2000);
  };

  document.addEventListener('mousemove', onMouseMove);
  document.addEventListener('mouseup', onMouseUp);
}

onMounted(() => {
  // Auto-rotation handled by CSS animation
});
</script>

<template>
  <div class="relative w-full h-full flex items-center justify-center perspective-1000">
    <div 
      ref="orbitRef"
      class="relative w-80 h-80 preserve-3d"
      :class="{ 'animate-spin-slow': !isDragging }"
      @mouseenter="pauseRotation"
      @mouseleave="resumeRotation"
      @mousedown="startDrag"
    >
      <!-- Central Monogram -->
      <div class="absolute inset-0 flex items-center justify-center z-10">
        <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-copper to-copper-light 
                    shadow-2xl flex items-center justify-center text-white text-4xl font-display font-bold
                    glow-copper">
          MA
        </div>
      </div>

      <!-- Tech Stack Items -->
      <div
        v-for="(tech, index) in techStack"
        :key="tech.name"
        class="absolute tech-item"
        :style="getTechPosition(index)"
      >
        <div 
          class="w-16 h-16 rounded-xl bg-navy border-2 border-copper/30 
                 flex flex-col items-center justify-center gap-1
                 hover:border-copper hover:scale-110 hover:glow-copper
                 transition-all duration-300 cursor-pointer group"
        >
          <span class="text-2xl">{{ tech.icon }}</span>
          <span class="text-[8px] font-mono text-copper opacity-0 group-hover:opacity-100 transition-opacity">
            {{ tech.name }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.perspective-1000 {
  perspective: 1000px;
}

.preserve-3d {
  transform-style: preserve-3d;
}

.animate-spin-slow {
  animation: rotate3d 20s linear infinite;
}

@keyframes rotate3d {
  from {
    transform: rotateY(0deg);
  }
  to {
    transform: rotateY(360deg);
  }
}

.tech-item {
  transition: transform 0.3s ease;
}
</style>
