<script setup lang="ts">
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/utils/api';

const router = useRouter();
const loading = ref(false);
const uploading = ref(false);
const imagePreview = ref('');
const fileInputBlog = ref<HTMLInputElement | null>(null);

const form = ref({
  title: '',
  slug: '',
  excerpt: '',
  content: '',
  featured_image: '',
  status: 'published',
  is_featured: false,
});

const errors = ref<Record<string, string>>({});

watch(() => form.value.title, (newTitle) => {
  if (!form.value.slug) {
    form.value.slug = newTitle.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
  }
});

async function handleImageUpload(event: Event) {
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

    // Try server upload
    try {
      const response = await api.post('/admin/upload', { base64, extension: ext });
      console.log('Upload response:', response.data);

      if (response.data.success && response.data.data?.url) {
        form.value.featured_image = response.data.data.url;
        imagePreview.value = response.data.data.url;
        console.log('Saved URL:', form.value.featured_image);
        return;
      }
    } catch (uploadErr) {
      console.warn('Server upload failed, using base64:', uploadErr);
    }

    // Fallback: save base64 directly
    form.value.featured_image = base64;
    imagePreview.value = base64;
    console.log('Using base64 fallback, length:', base64.length);

  } catch (error: any) {
    console.error('Upload error:', error);
    alert('Upload failed: ' + error.message);
  } finally {
    uploading.value = false;
  }
}

async function handleSubmit() {
  loading.value = true;
  errors.value = {};
  try {
    console.log('Submitting form:', form.value); // debug
    const response = await api.post('/admin/blogs', form.value);
    if (response.data.success) {
      router.push('/admin/blogs');
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
  <div class="space-y-6 p-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold mb-1">Create Blog Post</h1>
        <p class="text-gray-600 dark:text-gray-400">Write a new blog post</p>
      </div>
      <router-link to="/admin/blogs" class="btn-secondary text-center sm:w-auto w-full">← Back</router-link>
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-6">
      <div class="card p-5">
        <h2 class="text-xl font-bold mb-4">Content</h2>
        <div class="space-y-4 ">

          <div>
            <label class="block text-sm font-medium mb-2">Title *</label>
            <input v-model="form.title" type="text" required class="input" placeholder="Blog post title" />
            <p v-if="errors.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Slug *</label>
            <input v-model="form.slug" type="text" required class="input" placeholder="blog-post-slug" />
          </div>

          <!-- Featured Image Upload -->
          <div>
            <label class="block text-sm font-medium mb-2">Featured Image</label>
            <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-6 text-center hover:border-copper transition-colors cursor-pointer" @click="fileInputBlog?.click()">
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
            <input
              ref="fileInputBlog"
              type="file"
              accept="image/*"
              class="hidden"
              @change="handleImageUpload"
            />
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
            <textarea v-model="form.excerpt" rows="2" class="input" placeholder="Short description..."></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Content *</label>
            <textarea v-model="form.content" required rows="12" class="input" placeholder="Write your blog content here..."></textarea>
            <p v-if="errors.content" class="text-red-500 text-sm mt-1">{{ errors.content }}</p>
          </div>

        </div>
      </div>

      <div class="card p-5">
        <h2 class="text-xl font-bold mb-4">Settings</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2">Status</label>
            <select v-model="form.status" class="input">
              <option value="published">Published</option>
              <option value="draft">Draft</option>
            </select>
          </div>
          <label class="flex items-center gap-2 cursor-pointer">
            <input v-model="form.is_featured" type="checkbox" class="rounded w-4 h-4" />
            <span>Featured Post</span>
          </label>
        </div>
      </div>

      <!-- Error display -->
      <div v-if="Object.keys(errors).length > 0" class="p-4 bg-red-500/10 border border-red-500/30 rounded-xl">
        <ul class="text-red-400 text-sm space-y-1">
          <li v-for="(error, field) in errors" :key="field">{{ field }}: {{ error }}</li>
        </ul>
      </div>

      <div class="flex justify-end gap-3">
        <router-link to="/admin/blogs" class="btn-secondary">Cancel</router-link>
        <button type="submit" :disabled="loading || uploading" class="btn-primary min-h-[42px]">
          <span v-if="loading" class="flex items-center justify-center h-5">
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
          </span>
          <span v-else>Create Post</span>
        </button>
      </div>
    </form>
  </div>
</template>
