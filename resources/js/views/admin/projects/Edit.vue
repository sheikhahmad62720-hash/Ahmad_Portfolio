<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';

const route = useRoute();
const router = useRouter();
const loading = ref(true);
const saving = ref(false);

const fileInput = ref<HTMLInputElement | null>(null);
const uploading = ref(false);
const imagePreview = ref('');

const form = ref({
  title: '',
  slug: '',
  description: '',
  content: '',
  thumbnail: '',
  demo_url: '',
  github_url: '',
  is_published: true,
  is_featured: false,
});

const errors = ref<Record<string, string>>({});

async function handleImageUpload(event: Event) {
  const file = (event.target as HTMLInputElement).files?.[0];
  if (!file) return;

  uploading.value = true;
  try {
    const base64 = await new Promise<string>((resolve, reject) => {
      const reader = new FileReader();
      reader.onload = () => resolve(reader.result as string);
      reader.onerror = reject;
      reader.readAsDataURL(file);
    });

    const ext = file.name.split('.').pop() || 'jpg';

    try {
      const response = await api.post('/admin/upload', { base64, extension: ext });
      if (response.data.success && response.data.data?.url) {
        form.value.thumbnail = response.data.data.url;
        imagePreview.value = response.data.data.url;
        return;
      }
    } catch (uploadErr) {
      console.warn('Server upload failed, using base64:', uploadErr);
    }

    form.value.thumbnail = base64;
    imagePreview.value = base64;
  } catch (error: any) {
    console.error('Upload error:', error);
    alert('Upload failed: ' + error.message);
  } finally {
    uploading.value = false;
  }
}

async function fetchProject() {
  loading.value = true;
  try {
    const response = await api.get(`/admin/projects/${route.params.id}`);
    if (response.data.success) {
      const data = response.data.data;
      Object.assign(form.value, data);
      if (data.thumbnail) {
        imagePreview.value = data.thumbnail;
      }
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
    const response = await api.put(`/admin/projects/${route.params.id}`, form.value);
    if (response.data.success) {
      router.push('/admin/projects');
    }
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    }
  } finally {
    saving.value = false;
  }
}

onMounted(fetchProject);
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold mb-1">Edit Project</h1>
        <p class="text-gray-600 dark:text-gray-400">Update project information</p>
      </div>
      <router-link to="/admin/projects" class="btn-secondary text-center sm:w-auto w-full">
        ← Back
      </router-link>
    </div>

    <LoadingSpinner v-if="loading" />

    <form v-else @submit.prevent="handleSubmit" class="space-y-6">
      <div class="card">
        <h2 class="text-xl font-bold mb-4 p-5">Basic Information</h2>

        <div class="space-y-4 p-5">
          <div>
            <label class="block text-sm font-medium mb-2">Title *</label>
            <input v-model="form.title" type="text" required class="input" />
            <p v-if="errors.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Slug *</label>
            <input v-model="form.slug" type="text" required class="input" />
            <p v-if="errors.slug" class="text-red-500 text-sm mt-1">{{ errors.slug }}</p>
          </div>

          <!-- Thumbnail -->
          <div>
            <label class="block text-sm font-medium mb-2">Thumbnail</label>
            <div
              class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-6 text-center hover:border-copper transition-colors cursor-pointer"
              @click="fileInput?.click()"
            >
              <div v-if="imagePreview" class="mb-4">
                <img :src="imagePreview" class="max-h-48 mx-auto rounded-lg object-cover" />
                <p class="text-sm text-green-500 mt-2">✓ Image uploaded</p>
              </div>
              <div v-else>
                <div class="text-4xl mb-2">🖼️</div>
                <p class="text-gray-500">Click to upload thumbnail</p>
                <p class="text-sm text-gray-400 mt-1">PNG, JPG, WEBP up to 5MB</p>
              </div>
            </div>
            <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="handleImageUpload" />
            <div v-if="uploading" class="flex items-center gap-2 mt-2 text-copper">
              <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
              </svg>
              <span class="text-sm">Uploading...</span>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Description *</label>
            <textarea v-model="form.description" required rows="3" class="input"></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Content</label>
            <textarea v-model="form.content" rows="8" class="input"></textarea>
          </div>
        </div>
      </div>

      <div class="card p-5">
        <h2 class="text-xl font-bold mb-4">Links</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-2">Demo URL</label>
            <input v-model="form.demo_url" type="url" class="input" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">GitHub URL</label>
            <input v-model="form.github_url" type="url" class="input" />
          </div>
        </div>
      </div>

      <div class="card p-5">
        <h2 class="text-xl font-bold mb-4">Settings</h2>
        <div class="space-y-4">
          <label class="flex items-center">
            <input v-model="form.is_published" type="checkbox" class="rounded" />
            <span class="ml-2">Published</span>
          </label>
          <label class="flex items-center">
            <input v-model="form.is_featured" type="checkbox" class="rounded" />
            <span class="ml-2">Featured</span>
          </label>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <router-link to="/admin/projects" class="btn-secondary">Cancel</router-link>
        <button type="submit" :disabled="saving" class="btn-primary min-h-[42px]">
          <span v-if="saving" class="flex items-center justify-center h-5">
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
          </span>
          <span v-else>Update Project</span>
        </button>
      </div>
    </form>
  </div>
</template>
