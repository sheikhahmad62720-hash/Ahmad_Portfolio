<script setup lang="ts">
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/utils/api';

const router = useRouter();
const loading = ref(false);
const uploading = ref(false);
const thumbnailPreview = ref('');
const fileInputProject = ref<HTMLInputElement | null>(null);

const form = ref({
  title: '',
  slug: '',
  description: '',
  content: '',
  thumbnail: '',
  demo_url: '',
  github_url: '',
  status: 'completed',
  is_published: true,
  is_featured: false,
  technologies: [] as number[],
});

const errors = ref<Record<string, string>>({});

watch(() => form.value.title, (newTitle) => {
  if (!form.value.slug) {
    form.value.slug = newTitle.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
  }
});

async function handleThumbnailUpload(event: Event) {
  const file = (event.target as HTMLInputElement).files?.[0];
  if (!file) return;

  uploading.value = true;
  try {
    // Convert to base64
    const base64 = await new Promise<string>((resolve, reject) => {
      const reader = new FileReader();
      reader.onload = () => resolve(reader.result as string);
      reader.onerror = reject;
      reader.readAsDataURL(file);
    });

    const ext = file.name.split('.').pop() || 'jpg';
    const response = await api.post('/admin/upload', { base64, extension: ext });

    if (response.data.success) {
      form.value.thumbnail = response.data.data.url;
      thumbnailPreview.value = base64; // Show local preview immediately
    }
  } catch (error: any) {
    console.error('Upload error:', error?.response?.data || error);
    alert('Upload failed: ' + (error?.response?.data?.message || error.message));
  } finally {
    uploading.value = false;
  }
}

async function handleSubmit() {
  loading.value = true;
  errors.value = {};
  try {
    const response = await api.post('/admin/projects', form.value);
    if (response.data.success) {
      router.push('/admin/projects');
    }
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    } else if (error.response?.data?.message) {
      errors.value = { general: error.response.data.message };
    }
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold mb-1">Create Project</h1>
        <p class="text-gray-600 dark:text-gray-400">Add a new project to your portfolio</p>
      </div>
      <router-link to="/admin/projects" class="btn-secondary text-center sm:w-auto w-full">← Back</router-link>
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-6">

      <!-- Basic Info -->
      <div class="card p-5">
        <h2 class="text-xl font-bold mb-4">Basic Information</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2">Title *</label>
            <input v-model="form.title" type="text" required class="input" placeholder="Project title" />
            <p v-if="errors.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Slug *</label>
            <input v-model="form.slug" type="text" required class="input" placeholder="project-slug" />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Description *</label>
            <textarea v-model="form.description" required rows="3" class="input" placeholder="Brief project description"></textarea>
            <p v-if="errors.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Full Content</label>
            <textarea v-model="form.content" rows="8" class="input" placeholder="Detailed project information..."></textarea>
          </div>
        </div>
      </div>

      <!-- Thumbnail Upload -->
      <div class="card p-5">
        <h2 class="text-xl font-bold mb-4">Project Thumbnail</h2>
        <div
          class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-6 text-center hover:border-copper transition-colors cursor-pointer"
          @click="fileInputProject?.click()"
        >
          <div v-if="thumbnailPreview" class="mb-4">
            <img :src="thumbnailPreview" class="max-h-48 mx-auto rounded-lg object-cover" />
            <p class="text-sm text-green-500 mt-2">✓ Thumbnail uploaded</p>
          </div>
          <div v-else>
            <div class="text-4xl mb-2">🖼️</div>
            <p class="text-gray-500">Click to upload thumbnail</p>
            <p class="text-sm text-gray-400 mt-1">PNG, JPG, WEBP up to 5MB</p>
          </div>
        </div>
        <input
          ref="fileInputProject"
          type="file"
          accept="image/*"
          class="hidden"
          @change="handleThumbnailUpload"
        />
        <div v-if="uploading" class="flex items-center gap-2 mt-2 text-copper">
          <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
          </svg>
          <span class="text-sm">Uploading...</span>
        </div>
      </div>

      <!-- Links -->
      <div class="card p-5">
        <h2 class="text-xl font-bold mb-4">Links</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-2">Demo URL</label>
            <input v-model="form.demo_url" type="url" class="input" placeholder="https://demo.example.com" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">GitHub URL</label>
            <input v-model="form.github_url" type="url" class="input" placeholder="https://github.com/..." />
          </div>
        </div>
      </div>

      <!-- Settings -->
      <div class="card p-5">
        <h2 class="text-xl font-bold mb-4">Settings</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2">Status *</label>
            <select v-model="form.status" class="input">
              <option value="completed">Completed</option>
              <option value="in_progress">In Progress</option>
              <option value="planned">Planned</option>
            </select>
          </div>
          <label class="flex items-center gap-2 cursor-pointer">
            <input v-model="form.is_published" type="checkbox" class="rounded w-4 h-4" />
            <span>Published</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input v-model="form.is_featured" type="checkbox" class="rounded w-4 h-4" />
            <span>Featured</span>
          </label>
        </div>
      </div>

      <!-- Errors -->
      <div v-if="Object.keys(errors).length > 0" class="p-4 bg-red-500/10 border border-red-500/30 rounded-xl">
        <ul class="text-red-400 text-sm space-y-1">
          <li v-for="(error, field) in errors" :key="field">{{ field }}: {{ error }}</li>
        </ul>
      </div>

      <!-- Submit -->
      <div class="flex justify-end gap-3">
        <router-link to="/admin/projects" class="btn-secondary">Cancel</router-link>
        <button type="submit" :disabled="loading || uploading" class="btn-primary min-h-[42px]">
          <span v-if="loading" class="flex items-center justify-center h-5">
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
          </span>
          <span v-else>Create Project</span>
        </button>
      </div>
    </form>
  </div>
</template>
