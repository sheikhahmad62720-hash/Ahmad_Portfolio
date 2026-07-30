<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import api from '@/utils/api';

const router = useRouter();
const route = useRoute();
const loading = ref(false);
const isEdit = ref(false);
const errors = ref<Record<string, string>>({});

const form = ref({
    company: '',
    position: '',
    location: '',
    employment_type: 'Full-time',
    description: '',
    start_date: '',
    end_date: '',
    is_current: false,
});

async function fetchItem() {
    if (!route.params.id) return;
    loading.value = true;
    try {
        const res = await api.get(`/admin/experience/${route.params.id}`);
        if (res.data.success) {
            const d = res.data.data;
            form.value = {
                company: d.company,
                position: d.position,
                location: d.location || '',
                employment_type: d.employment_type || 'Full-time',
                description: d.description || '',
                start_date: d.start_date?.split('T')[0] || '',
                end_date: d.end_date?.split('T')[0] || '',
                is_current: d.is_current || false,
            };
        }
    } catch (e) { console.error(e) }
    finally { loading.value = false }
}

async function handleSubmit() {
    loading.value = true;
    errors.value = {};
    try {
        const endpoint = isEdit.value ? `/admin/experience/${route.params.id}` : '/admin/experience';
        const method = isEdit.value ? 'put' : 'post';
        const res = await api[method](endpoint, form.value);
        if (res.data.success) router.push('/admin/experience');
    } catch (e: any) {
        if (e.response?.data?.errors) errors.value = e.response.data.errors;
    } finally { loading.value = false }
}

onMounted(() => {
    isEdit.value = !!route.params.id;
    if (isEdit.value) fetchItem();
});
</script>

<template>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-1">{{ isEdit ? 'Edit' : 'Add' }} Experience</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ isEdit ? 'Update' : 'Add' }} work experience details</p>
            </div>
            <router-link to="/admin/experience" class="btn-secondary text-center sm:w-auto w-full">← Back</router-link>
        </div>

        <LoadingSpinner v-if="loading && isEdit" />

        <form v-else @submit.prevent="handleSubmit" class="space-y-6">
            <div class="card p-5">
                <h2 class="text-xl font-bold mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Position *</label>
                        <input v-model="form.position" type="text" required class="input" placeholder="e.g. Full Stack Developer" />
                        <p v-if="errors.position" class="text-red-500 text-sm mt-1">{{ errors.position }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Company *</label>
                        <input v-model="form.company" type="text" required class="input" placeholder="Company name" />
                        <p v-if="errors.company" class="text-red-500 text-sm mt-1">{{ errors.company }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Location</label>
                        <input v-model="form.location" type="text" class="input" placeholder="e.g. Lahore, Pakistan" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Employment Type</label>
                        <select v-model="form.employment_type" class="input">
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract">Contract</option>
                            <option value="Freelance">Freelance</option>
                            <option value="Internship">Internship</option>
                        </select>
                    </div>
                    <div></div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Start Date *</label>
                        <input v-model="form.start_date" type="date" required class="input" />
                        <p v-if="errors.start_date" class="text-red-500 text-sm mt-1">{{ errors.start_date }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">End Date</label>
                        <input v-model="form.end_date" type="date" class="input" :disabled="form.is_current" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input v-model="form.is_current" type="checkbox" class="rounded" />
                            <span class="text-sm font-medium">I currently work here</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Description</label>
                        <textarea v-model="form.description" rows="4" class="input" placeholder="Describe your role and achievements..."></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <router-link to="/admin/experience" class="btn-secondary">Cancel</router-link>
                <button type="submit" :disabled="loading" class="btn-primary min-h-[42px]">
                    <span v-if="loading" class="flex items-center justify-center h-5">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                    <span v-else>{{ isEdit ? 'Update' : 'Save' }} Experience</span>
                </button>
            </div>
        </form>
    </div>
</template>
