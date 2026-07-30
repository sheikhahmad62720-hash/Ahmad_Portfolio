<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import type { Project } from '@/types';

const router = useRouter();
const route = useRoute();
const loading = ref(false);
const isEdit = ref(false);

const form = ref({
  title: '',
  slug: '',
  description: '',
  content: '',
  client_name: '',
  thumbnail: '',
  video_url: '',
  demo_url: '',
  github_url: '',
  is_published: true,
  is_featured: false,
  order: 0,
});

const errors = ref<Record<string, string>>({});

// Auto-generate slug from title
watch(() => form.value.title, (newTitle) => {
  if (!isEdit.value && newTitle) {
    form.value.slug = newTitle
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-|-$/g, '');
  }
});

async function handleFileUpload(event: Event, field: string) {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  
  if (!file) return;

  const formData = new FormData();
  formData.append('file', file);
  formData.append('folder', 'projects');

  try {
    const response = await api.post('/admin/media/upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });

    if (response.data.success) {
      form.value[field] = response.data.data.url;
    }
  } catch (error) {
    console.error('Error uploading file:', error);
  }
}

async function fetchProject() {
  if (!route.params.id) return;

  loading.value = true;

  try {
    const response = await api.get(`/admin/projects/${route.params.id}`);
    
    if (response.data.success) {
      const project = response.data.data;
      Object.keys(form.value).forEach((key) => {
        if (project[key] !== undefined) {
          form.value[key] = project[key];
        }
      });
    }
  } catch (error) {
    console.error('Error fetching project:', error);
    router.push('/admin/projects');
  } finally {
    loading.value = false;
  }
}

async function handleSubmit() {
  loading.value = true;
  errors.value = {};

  try {
    const endpoint = isEdit.value 
      ? `/admin/projects/${route.params.id}`
      : '/admin/projects';
    
    const method = isEdit.value ? 'put' : 'post';
    
    const response = await api[method](endpoint, form.value);

    if (response.data.success) {
      router.push('/admin/projects');
    }
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    }
    console.error('Error saving project:', error);
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  isEdit.value = !!route.params.id;
  if (isEdit.value) {
    fetchProject();
  }
});
</script>

<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold mb-1">{{ isEdit ? 'Edit Project' : 'Create Project' }}</h1>
        <p class="text-gray-600 dark:text-gray-400">{{ isEdit ? 'Update project details' : 'Add a new project to your portfolio' }}</p>
      </div>
      <router-link to="/admin/projects" class="btn-secondary text-center sm:w-auto w-full">
        ← Back
      </router-link>
    </div>

    <!-- Form -->
    <form @submit.prevent="handleSubmit" class="space-y-6">
      <!-- Basic Info Card -->
      <div class="card">
        <h2 class="text-xl font-semibold mb-4">Basic Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Title -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-2">Title *</label>
            <input
              v-model="form.title"
              type="text"
              required
              class="input"
              placeholder="Project Title"
            />
            <p v-if="errors.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</p>
          </div>

          <!-- Slug -->
          <div>
            <label class="block text-sm font-medium mb-2">Slug *</label>
            <input
              v-model="form.slug"
              type="text"
              required
              class="input"
              placeholder="project-slug"
            />
            <p v-if="errors.slug" class="text-red-500 text-sm mt-1">{{ errors.slug }}</p>
          </div>

          <!-- Client Name -->
          <div>
            <label class="block text-sm font-medium mb-2">Client Name</label>
            <input
              v-model="form.client_name"
              type="text"
              class="input"
              placeholder="Client or Company Name"
            />
          </div>

          <!-- Description -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-2">Short Description *</label>
            <textarea
              v-model="form.description"
              rows="3"
              required
              class="input"
              placeholder="Brief description of the project"
            ></textarea>
            <p v-if="errors.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</p>
          </div>

          <!-- Content -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-2">Full Content</label>
            <textarea
              v-model="form.content"
              rows="10"
              class="input"
              placeholder="Detailed project description (supports HTML)"
            ></textarea>
          </div>
        </div>
      </div>

      <!-- Media Card -->
      <div class="card">
        <h2 class="text-xl font-semibold mb-4">Media</h2>
        
        <div class="space-y-4">
          <!-- Thumbnail -->
          <div>
            <label class="block text-sm font-medium mb-2">Thumbnail Image</label>
            <input
              type="file"
              accept="image/*"
              @change="handleFileUpload($event, 'thumbnail')"
              class="input"
            />
            <div v-if="form.thumbnail" class="mt-2">
              <img :src="form.thumbnail" alt="Thumbnail" class="h-32 rounded-lg" />
            </div>
          </div>

          <!-- Video URL -->
          <div>
            <label class="block text-sm font-medium mb-2">Video URL</label>
            <input
              v-model="form.video_url"
              type="url"
              class="input"
              placeholder="https://youtube.com/embed/..."
            />
          </div>
        </div>
      </div>

      <!-- Links Card -->
      <div class="card">
        <h2 class="text-xl font-semibold mb-4">Project Links</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium mb-2">Demo URL</label>
            <input
              v-model="form.demo_url"
              type="url"
              class="input"
              placeholder="https://demo.example.com"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">GitHub URL</label>
            <input
              v-model="form.github_url"
              type="url"
              class="input"
              placeholder="https://github.com/username/repo"
            />
          </div>
        </div>
      </div>

      <!-- Settings Card -->
      <div class="card">
        <h2 class="text-xl font-semibold mb-4">Settings</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Status -->
          <div>
            <label class="flex items-center">
              <input
                v-model="form.is_published"
                type="checkbox"
                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
              />
              <span class="ml-2 text-sm font-medium">Published</span>
            </label>
          </div>

          <!-- Featured -->
          <div>
            <label class="flex items-center">
              <input
                v-model="form.is_featured"
                type="checkbox"
                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
              />
              <span class="ml-2 text-sm font-medium">Featured</span>
            </label>
          </div>

          <!-- Order -->
          <div>
            <label class="block text-sm font-medium mb-2">Display Order</label>
            <input
              v-model.number="form.order"
              type="number"
              class="input"
              placeholder="0"
            />
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex items-center justify-end gap-4">
        <router-link to="/admin/projects" class="btn-secondary">
          Cancel
        </router-link>
        <button type="submit" :disabled="loading" class="btn-primary min-h-[42px]">
          <span v-if="loading" class="flex items-center justify-center h-5">
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
          </span>
          <span v-else>{{ isEdit ? 'Update' : 'Create' }} Project</span>
        </button>
      </div>
    </form>
  </div>
</template>
