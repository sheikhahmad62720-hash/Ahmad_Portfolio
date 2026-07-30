<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import api from '@/utils/api';

const router = useRouter();
const route = useRoute();
const loading = ref(false);
const saving = ref(false);
const isEdit = ref(false);
const errors = ref<Record<string, string>>({});
const projects = ref<any[]>([]);

const form = ref({
    client_name: '',
    client_position: '',
    client_company: '',
    client_avatar: '',
    content: '',
    rating: 5,
    project_id: null as number | null,
    is_featured: false,
    is_published: true,
});

async function fetchProjects() {
    try {
        const res = await api.get('/admin/projects');
        if (res.data.success) projects.value = res.data.data;
    } catch (e) { console.error(e) }
}

async function fetchItem() {
    if (!route.params.id) return;
    loading.value = true;
    try {
        const res = await api.get(`/admin/testimonials/${route.params.id}`);
        if (res.data.success) {
            const d = res.data.data;
            form.value = {
                client_name: d.client_name,
                client_position: d.client_position || '',
                client_company: d.client_company || '',
                client_avatar: d.client_avatar || '',
                content: d.content,
                rating: d.rating,
                project_id: d.project_id,
                is_featured: d.is_featured || false,
                is_published: d.is_published ?? true,
            };
        }
    } catch (e) { console.error(e) }
    finally { loading.value = false }
}

async function handleSubmit() {
    saving.value = true;
    errors.value = {};
    try {
        const endpoint = isEdit.value ? `/admin/testimonials/${route.params.id}` : '/admin/testimonials';
        const method = isEdit.value ? 'put' : 'post';
        const res = await api[method](endpoint, form.value);
        if (res.data.success) router.push('/admin/testimonials');
    } catch (e: any) {
        if (e.response?.data?.errors) errors.value = e.response.data.errors;
    } finally { saving.value = false }
}

onMounted(() => {
    isEdit.value = !!route.params.id;
    fetchProjects();
    if (isEdit.value) fetchItem();
});
</script>

<template>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-1">{{ isEdit ? 'Edit' : 'Add' }} Testimonial</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ isEdit ? 'Update' : 'Add' }} client testimonial</p>
            </div>
            <router-link to="/admin/testimonials" class="btn-secondary text-center sm:w-auto w-full">← Back</router-link>
        </div>

        <LoadingSpinner v-if="loading && isEdit" />

        <form v-else @submit.prevent="handleSubmit" class="space-y-6">
            <div class="card p-5">
                <h2 class="text-xl font-bold mb-4">Testimonial Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Client Name *</label>
                        <input v-model="form.client_name" type="text" required class="input" placeholder="Client name" />
                        <p v-if="errors.client_name" class="text-red-500 text-sm mt-1">{{ errors.client_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Position</label>
                        <input v-model="form.client_position" type="text" class="input" placeholder="e.g. CEO" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Company</label>
                        <input v-model="form.client_company" type="text" class="input" placeholder="Company name" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Content *</label>
                        <textarea v-model="form.content" required rows="4" class="input" placeholder="Testimonial text..."></textarea>
                        <p v-if="errors.content" class="text-red-500 text-sm mt-1">{{ errors.content }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Rating *</label>
                        <div class="flex gap-2">
                            <button v-for="n in 5" :key="n" type="button" @click="form.rating = n"
                                class="text-2xl transition-colors" :class="n <= form.rating ? 'text-amber-500' : 'text-gray-300'">★</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Related Project</label>
                        <select v-model="form.project_id" class="input">
                            <option :value="null">None</option>
                            <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.title }}</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 flex gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input v-model="form.is_published" type="checkbox" class="rounded" />
                            <span class="text-sm font-medium">Published</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input v-model="form.is_featured" type="checkbox" class="rounded" />
                            <span class="text-sm font-medium">Featured</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <router-link to="/admin/testimonials" class="btn-secondary">Cancel</router-link>
                <button type="submit" :disabled="saving" class="btn-primary min-h-[42px]">
                    <span v-if="saving" class="flex items-center justify-center h-5">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                    <span v-else>{{ isEdit ? 'Update' : 'Save' }} Testimonial</span>
                </button>
            </div>
        </form>
    </div>
</template>
