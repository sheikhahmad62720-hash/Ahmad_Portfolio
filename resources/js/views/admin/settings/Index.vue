<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';

const loading = ref(true);
const saving = ref(false);
const successMessage = ref('');

const settings = ref({
  site_title: '',
  site_description: '',
  contact_email: '',
  contact_phone: '',
  address: '',
  github_url: '',
  linkedin_url: '',
  twitter_url: '',
  instagram_url: '',
  meta_keywords: '',
  google_analytics_id: '',
  google_search_console: '',
  openai_api_key: '',
  ai_model: 'gpt-4',
  enable_chatbot: true,
});

async function fetchSettings() {
  loading.value = true;

  try {
    const response = await api.get('/admin/settings');

    if (response.data.success) {
      const groups = response.data.data;

      Object.values(groups).forEach((group: any) => {
        group.forEach((setting: any) => {
          if (setting.key in settings.value) {
            (settings.value as any)[setting.key] = setting.value;
          }
        });
      });
    }
  } catch (error) {
    console.error('Error fetching settings:', error);
  } finally {
    loading.value = false;
  }
}

async function saveSettings() {
  saving.value = true;
  successMessage.value = '';

  try {
    const payload = {
      settings: Object.entries(settings.value).map(([key, value]) => ({
        key,
        value,
      })),
    };

    const response = await api.put('/admin/settings', payload);

    if (response.data.success) {
      successMessage.value = 'Settings saved successfully!';
      setTimeout(() => {
        successMessage.value = '';
      }, 3000);
    }
  } catch (error) {
    console.error('Error saving settings:', error);
  } finally {
    saving.value = false;
  }
}

onMounted(() => {
  fetchSettings();
});
</script>

<template>
  <div class="space-y-6">
    <div>
      <h1 class="text-2xl sm:text-3xl font-bold mb-1">Settings</h1>
      <p class="text-gray-600 dark:text-gray-400">Manage your portfolio settings</p>
    </div>

    <LoadingSpinner v-if="loading" />

    <div v-else class="space-y-6">
      <!-- General Settings -->
      <div class="card">
        <h2 class="text-2xl font-bold mb-4">General Settings</h2>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2">Site Title</label>
            <input
              v-model="settings.site_title"
              type="text"
              class="input"
              placeholder="Muhammad Ahmad - Portfolio"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Site Description</label>
            <textarea
              v-model="settings.site_description"
              rows="3"
              class="input"
              placeholder="Full Stack Developer..."
            ></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2">Contact Email</label>
              <input v-model="settings.contact_email" type="email" class="input" />
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Contact Phone</label>
              <input v-model="settings.contact_phone" type="tel" class="input" />
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Address</label>
            <input v-model="settings.address" type="text" class="input" placeholder="Lahore, Pakistan" />
          </div>
        </div>
      </div>

      <!-- Social Links -->
      <div class="card">
        <h2 class="text-2xl font-bold mb-4">Social Media Links</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-2">GitHub</label>
            <input v-model="settings.github_url" type="url" class="input" placeholder="https://github.com/..." />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">LinkedIn</label>
            <input v-model="settings.linkedin_url" type="url" class="input" placeholder="https://linkedin.com/in/..." />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Twitter</label>
            <input v-model="settings.twitter_url" type="url" class="input" placeholder="https://twitter.com/..." />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Instagram</label>
            <input v-model="settings.instagram_url" type="url" class="input" placeholder="https://instagram.com/..." />
          </div>
        </div>
      </div>

      <!-- SEO Settings -->
      <div class="card">
        <h2 class="text-2xl font-bold mb-4">SEO Settings</h2>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2">Meta Keywords (comma-separated)</label>
            <input
              v-model="settings.meta_keywords"
              type="text"
              class="input"
              placeholder="Laravel, Vue.js, Full Stack Developer"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Google Analytics ID</label>
            <input v-model="settings.google_analytics_id" type="text" class="input" placeholder="G-XXXXXXXXXX" />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Google Search Console</label>
            <input v-model="settings.google_search_console" type="text" class="input" />
          </div>
        </div>
      </div>

      <!-- AI Settings -->
      <div class="card">
        <h2 class="text-2xl font-bold mb-4">AI Integration</h2>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2">OpenAI API Key</label>
            <input
              v-model="settings.openai_api_key"
              type="password"
              class="input"
              placeholder="sk-..."
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">AI Model</label>
            <select v-model="settings.ai_model" class="input">
              <option value="gpt-4">GPT-4</option>
              <option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>
              <option value="gemini-pro">Gemini Pro</option>
              <option value="claude-3">Claude 3</option>
            </select>
          </div>

          <label class="flex items-center">
            <input
              v-model="settings.enable_chatbot"
              type="checkbox"
              class="rounded"
            />
            <span class="ml-2">Enable AI Chatbot</span>
          </label>
        </div>
      </div>

      <!-- Save Button -->
      <div class="flex justify-end">
        <button
          @click="saveSettings"
          :disabled="saving"
          class="btn-primary px-8 min-h-[42px]"
        >
          <span v-if="saving" class="flex items-center justify-center h-5">
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
          </span>
          <span v-else>Save Settings</span>
        </button>
      </div>

      <!-- Success Message -->
      <div
        v-if="successMessage"
        class="card bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800"
      >
        <p class="text-green-700 dark:text-green-300">{{ successMessage }}</p>
      </div>
    </div>
  </div>
</template>
