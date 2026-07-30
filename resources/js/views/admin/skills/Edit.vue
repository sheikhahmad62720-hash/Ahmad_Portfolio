<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';

const route = useRoute();
const router = useRouter();
const loading = ref(true);
const saving = ref(false);
const errors = ref<Record<string, string>>({});

const form = ref({
  id: null as number | null,
  name: '',
  icon: '',
  category: 'frontend',
  proficiency_level: 80,
  years_experience: null as number | null,
  description: '',
  is_featured: false,
});

async function fetchSkill() {
  try {
    const response = await api.get(`/admin/skills/${route.params.id}`);
    if (response.data.success) {
      const s = response.data.data;
      form.value = {
        id: s.id,
        name: s.name,
        icon: s.icon || '',
        category: s.category || 'frontend',
        proficiency_level: s.proficiency_level,
        years_experience: s.years_experience ?? null,
        description: s.description || '',
        is_featured: s.is_featured,
      };
    }
  } catch (error) {
    console.error('Error:', error);
  } finally {
    loading.value = false;
  }
}

async function handleSubmit() {
  saving.value = true;
  errors.value = {};
  try {
    const response = await api.put(`/admin/skills/${route.params.id}`, form.value);
    if (response.data.success) {
      router.push('/admin/skills');
    }
  } catch (error: any) {
    if (error.response?.status === 422 && error.response.data?.errors) {
      errors.value = error.response.data.errors;
    }
    console.error('Error:', error);
  } finally {
    saving.value = false;
  }
}

onMounted(fetchSkill);
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold mb-1">Edit Skill</h1>
        <p class="text-gray-600 dark:text-gray-400">Update skill details</p>
      </div>
      <router-link to="/admin/skills" class="btn-secondary text-center sm:w-auto w-full">← Back</router-link>
    </div>

    <LoadingSpinner v-if="loading" />

    <form v-else @submit.prevent="handleSubmit" class="space-y-6">
      <div class="card p-5">
        <h2 class="text-xl font-bold mb-4">Skill Details</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2">Name *</label>
            <input v-model="form.name" type="text" required class="input" placeholder="e.g. Laravel" />
            <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2">Icon (Emoji)</label>
              <input v-model="form.icon" type="text" class="input" placeholder="🚀" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2">Category</label>
              <select v-model="form.category" class="input">
                <option value="frontend">Frontend</option>
                <option value="backend">Backend</option>
                <option value="database">Database</option>
                <option value="devops">DevOps</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2">Proficiency (%) *</label>
              <input v-model.number="form.proficiency_level" type="number" min="0" max="100" required class="input" />
              <p v-if="errors.proficiency_level" class="text-red-500 text-sm mt-1">{{ errors.proficiency_level }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium mb-2">Years of Experience</label>
              <input v-model.number="form.years_experience" type="number" min="0" max="50" step="0.5" class="input" placeholder="e.g. 5" />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Description</label>
            <textarea v-model="form.description" rows="3" class="input" placeholder="Brief description of your expertise with this skill..."></textarea>
          </div>

          <label class="flex items-center gap-2 cursor-pointer">
            <input v-model="form.is_featured" type="checkbox" class="rounded" />
            <span class="text-sm font-medium">Feature this skill</span>
          </label>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <router-link to="/admin/skills" class="btn-secondary">Cancel</router-link>
        <button type="submit" :disabled="saving" class="btn-primary min-h-[42px]">
          <span v-if="saving" class="flex items-center justify-center h-5">
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
          </span>
          <span v-else>Update Skill</span>
        </button>
      </div>
    </form>
  </div>
</template>
