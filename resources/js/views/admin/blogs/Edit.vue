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
  excerpt: '',
  content: '',
  featured_image: '',
  status: 'published',
  is_featured: false,
});

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
        form.value.featured_image = response.data.data.url;
        imagePreview.value = response.data.data.url;
        return;
      }
    } catch (uploadErr) {
      console.warn('Server upload failed, using base64:', uploadErr);
    }

    form.value.featured_image = base64;
    imagePreview.value = base64;
  } catch (error: any) {
    console.error('Upload error:', error);
    alert('Upload failed: ' + error.message);
  } finally {
    uploading.value = false;
  }
}

async function fetchBlog() {
  loading.value = true;
  try {
    const response = await api.get(`/admin/blogs/${route.params.id}`);
    if (response.data.success) {
      const data = response.data.data;
      form.value = {
        title: data.title,
        slug: data.slug,
        excerpt: data.excerpt || '',
        content: data.content,
        featured_image: data.featured_image || '',
        status: data.status || 'draft',
        is_featured: data.is_featured || false,
      };
      if (data.featured_image) {
        imagePreview.value = data.featured_image;
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
  try {
    const response = await api.put(`/admin/blogs/${route.params.id}`, form.value);
    if (response.data.success) {
      router.push('/admin/blogs');
    }
  } catch (error) {
    console.error('Error:', error);
  } finally {
    saving.value = false;
  }
}

onMounted(fetchBlog);
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold mb-1">Edit Blog Post</h1>
      </div>
      <router-link to="/admin/blogs" class="btn-secondary text-center sm:w-auto w-full">← Back</router-link>
    </div>

    <LoadingSpinner v-if="loading" />

    <form v-else @submit.prevent="handleSubmit" class="space-y-6">
      <div class="card p-5">
        <h2 class="text-xl font-bold mb-4">Content</h2>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2">Title *</label>
            <input v-model="form.title" type="text" required class="input" />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Slug *</label>
            <input v-model="form.slug" type="text" required class="input" />
          </div>

          <!-- Featured Image -->
          <div>
            <label class="block text-sm font-medium mb-2">Featured Image</label>
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
                <p class="text-gray-500">Click to upload featured image</p>
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
            <label class="block text-sm font-medium mb-2">Excerpt</label>
            <textarea v-model="form.excerpt" rows="2" class="input"></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Content *</label>
            <textarea v-model="form.content" required rows="12" class="input"></textarea>
          </div>
        </div>
      </div>

      <div class="card p-5">
        <h2 class="text-xl font-bold mb-4">Settings</h2>
        <div class="space-y-4">
          <label class="flex items-center">
            <input v-model="form.status" type="checkbox" class="rounded" true-value="published" false-value="draft" />
            <span class="ml-2">Published</span>
          </label>
          <label class="flex items-center">
            <input v-model="form.is_featured" type="checkbox" class="rounded" />
            <span class="ml-2">Featured</span>
          </label>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <router-link to="/admin/blogs" class="btn-secondary">Cancel</router-link>
        <button type="submit" :disabled="saving" class="btn-primary min-h-[42px]">
          <span v-if="saving" class="flex items-center justify-center h-5">
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
          </span>
          <span v-else>Update Post</span>
        </button>
      </div>
    </form>
  </div>
</template>
