<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import Modal from '@/components/Modal.vue';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';

const loading = ref(true);
const testimonials = ref<any[]>([]);
const deleteModal = ref({ open: false, loading: false, item: null as any | null });

async function fetchData() {
    loading.value = true;
    try {
        const res = await api.get('/admin/testimonials');
        if (res.data.success) testimonials.value = res.data.data;
    } catch (e) { console.error(e) }
    finally { loading.value = false }
}

function confirmDelete(item: any) {
    deleteModal.value.item = item;
    deleteModal.value.open = true;
}

async function deleteItem() {
    if (!deleteModal.value.item) return;
    deleteModal.value.loading = true;
    try {
        await api.delete(`/admin/testimonials/${deleteModal.value.item.id}`);
        deleteModal.value.open = false;
        fetchData();
    } catch (e) { console.error(e) }
    finally { deleteModal.value.loading = false }
}

function starsHtml(rating: number) {
    return '★'.repeat(rating) + '☆'.repeat(5 - rating);
}

onMounted(fetchData);
</script>

<template>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-1">Testimonials</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage client testimonials</p>
            </div>
            <router-link to="/admin/testimonials/create" class="btn-primary text-center sm:w-auto w-full">+ Add Testimonial</router-link>
        </div>

        <div class="card overflow-hidden">
            <LoadingSpinner v-if="loading" />

            <div v-else-if="testimonials.length > 0">
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Project</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="t in testimonials" :key="t.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4">
                                    <div class="font-medium">{{ t.client_name }}</div>
                                    <div class="text-sm text-gray-500">{{ t.client_position }} {{ t.client_company }}</div>
                                </td>
                                <td class="px-6 py-4 text-amber-500">{{ starsHtml(t.rating) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ t.project?.title || '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-1">
                                        <span :class="['badge text-xs', t.is_published ? 'badge-success' : 'badge-warning']">{{ t.is_published ? 'Published' : 'Draft' }}</span>
                                        <span v-if="t.is_featured" class="badge-primary text-xs">Featured</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <router-link :to="`/admin/testimonials/${t.id}/edit`" class="p-2 rounded text-primary-600 hover:bg-primary-50" title="Edit">
                                            <PencilIcon class="w-4 h-4" />
                                        </router-link>
                                        <button @click="confirmDelete(t)" class="p-2 rounded text-red-600 hover:bg-red-50" title="Delete">
                                            <TrashIcon class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
                    <div v-for="t in testimonials" :key="t.id" class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <div class="font-medium">{{ t.client_name }}</div>
                                <div class="text-amber-500 text-sm">{{ starsHtml(t.rating) }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ t.client_position }} {{ t.client_company }}</div>
                                <div class="text-xs text-gray-500">Project: {{ t.project?.title || '-' }}</div>
                                <div class="flex gap-1 mt-1">
                                    <span :class="['badge text-xs', t.is_published ? 'badge-success' : 'badge-warning']">{{ t.is_published ? 'Published' : 'Draft' }}</span>
                                    <span v-if="t.is_featured" class="badge-primary text-xs">Featured</span>
                                </div>
                            </div>
                            <div class="flex gap-1 shrink-0">
                                <router-link :to="`/admin/testimonials/${t.id}/edit`" class="p-2 text-primary-600"><PencilIcon class="w-4 h-4" /></router-link>
                                <button @click="confirmDelete(t)" class="p-2 text-red-600"><TrashIcon class="w-4 h-4" /></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-12">
                <div class="text-6xl mb-4">💬</div>
                <h3 class="text-xl font-semibold mb-2">No testimonials yet</h3>
                <router-link to="/admin/testimonials/create" class="btn-primary">Add Testimonial</router-link>
            </div>
        </div>

        <Modal v-model="deleteModal.open">
            <template #title>Delete Testimonial</template>
            <div class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete <strong>{{ deleteModal.item?.client_name }}</strong>'s testimonial?</div>
            <div class="flex justify-end gap-3">
                <button @click="deleteModal.open = false" class="btn-outline">Cancel</button>
                <button @click="deleteItem" class="btn-danger" :disabled="deleteModal.loading">Delete</button>
            </div>
        </Modal>
    </div>
</template>
