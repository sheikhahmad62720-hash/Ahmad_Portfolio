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
    institution: '',
    degree: '',
    field_of_study: '',
    location: '',
    description: '',
    grade: '',
    start_date: '',
    end_date: '',
    is_current: false,
});

async function fetchItem() {
    if (!route.params.id) return;
    loading.value = true;
    try {
        const res = await api.get(`/admin/education/${route.params.id}`);
        if (res.data.success) {
            const d = res.data.data;
            form.value = {
                institution: d.institution,
                degree: d.degree,
                field_of_study: d.field_of_study || '',
                location: d.location || '',
                description: d.description || '',
                grade: d.grade || '',
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
        const endpoint = isEdit.value ? `/admin/education/${route.params.id}` : '/admin/education';
        const method = isEdit.value ? 'put' : 'post';
        const res = await api[method](endpoint, form.value);
        if (res.data.success) router.push('/admin/education');
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
                <h1 class="text-2xl sm:text-3xl font-bold mb-1">{{ isEdit ? 'Edit' : 'Add' }} Education</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ isEdit ? 'Update' : 'Add' }} education details</p>
            </div>
            <router-link to="/admin/education" class="btn-secondary text-center sm:w-auto w-full">← Back</router-link>
        </div>

        <LoadingSpinner v-if="loading && isEdit" />

        <form v-else @submit.prevent="handleSubmit" class="space-y-6">
            <div class="card p-5">
                <h2 class="text-xl font-bold mb-4">Education Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Institution *</label>
                        <input v-model="form.institution" type="text" required class="input" placeholder="University/School name" />
                        <p v-if="errors.institution" class="text-red-500 text-sm mt-1">{{ errors.institution }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Degree *</label>
                        <input v-model="form.degree" type="text" required class="input" placeholder="e.g. Bachelor of Science" />
                        <p v-if="errors.degree" class="text-red-500 text-sm mt-1">{{ errors.degree }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Field of Study</label>
                        <input v-model="form.field_of_study" type="text" class="input" placeholder="e.g. Computer Science" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Location</label>
                        <input v-model="form.location" type="text" class="input" placeholder="e.g. Lahore, Pakistan" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Grade</label>
                        <input v-model="form.grade" type="text" class="input" placeholder="e.g. A, 3.5 GPA, 85%" />
                    </div>
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
                            <span class="text-sm font-medium">I currently study here</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Description</label>
                        <textarea v-model="form.description" rows="3" class="input" placeholder="Additional details..."></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <router-link to="/admin/education" class="btn-secondary">Cancel</router-link>
                <button type="submit" :disabled="loading" class="btn-primary min-h-[42px]">
                    <span v-if="loading" class="flex items-center justify-center h-5">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                    <span v-else>{{ isEdit ? 'Update' : 'Save' }} Education</span>
                </button>
            </div>
        </form>
    </div>
</template>
