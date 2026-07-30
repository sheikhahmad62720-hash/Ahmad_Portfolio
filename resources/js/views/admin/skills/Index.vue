<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import Modal from '@/components/Modal.vue';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import type { Skill } from '@/types';

const loading = ref(true);
const skills = ref<Skill[]>([]);
const searchQuery = ref('');
const filters = ref({ category: '' });

const deleteModal = ref({
    open: false,
    loading: false,
    skill: null as Skill | null,
});

async function fetchSkills() {
    loading.value = true;
    try {
        const params: any = {};
        if (searchQuery.value) params.search = searchQuery.value;
        if (filters.value.category) params.category = filters.value.category;

        const response = await api.get('/admin/skills', { params });
        if (response.data.success) {
            skills.value = response.data.data;
        }
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loading.value = false;
    }
}

let searchTimeout: ReturnType<typeof setTimeout>;
function handleSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(fetchSkills, 300);
}

async function moveSkill(id: number, index: number, direction: number) {
    const targetIndex = index + direction;
    if (targetIndex < 0 || targetIndex >= skills.value.length) return;

    const updated = [...skills.value];
    [updated[index], updated[targetIndex]] = [updated[targetIndex], updated[index]];

    try {
        await api.post('/admin/skills/reorder', {
            skills: updated.map((s, i) => ({ id: s.id, order: i })),
        });
        skills.value = updated;
    } catch (error) {
        console.error('Error reordering:', error);
    }
}

function confirmDelete(skill: Skill) {
    deleteModal.value.skill = skill;
    deleteModal.value.open = true;
}

async function deleteSkill() {
    if (!deleteModal.value.skill) return;
    deleteModal.value.loading = true;
    try {
        await api.delete(`/admin/skills/${deleteModal.value.skill.id}`);
        deleteModal.value.open = false;
        fetchSkills();
    } catch (error) {
        console.error('Error:', error);
    } finally {
        deleteModal.value.loading = false;
    }
}

onMounted(fetchSkills);
</script>

<template>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-1">Skills</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage your technical skills</p>
            </div>
            <router-link to="/admin/skills/create" class="btn-primary text-center sm:w-auto w-full">
                + Add Skill
            </router-link>
        </div>

        <div class="card">
            <div class="flex flex-wrap items-center gap-4 p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex-1 min-w-[200px]">
                    <input v-model="searchQuery" type="text" placeholder="Search skills..." class="input"
                        @input="handleSearch" />
                </div>
                <select v-model="filters.category" @change="fetchSkills" class="input w-44">
                    <option value="">All Categories</option>
                    <option value="frontend">Frontend</option>
                    <option value="backend">Backend</option>
                    <option value="database">Database</option>
                    <option value="devops">DevOps</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <LoadingSpinner v-if="loading" />

            <div v-else-if="skills.length > 0" class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Skill</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Proficiency</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Experience</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Featured</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(skill, index) in skills" :key="skill.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-3">
                                <div class="flex gap-0.5">
                                    <button v-if="index > 0" @click="moveSkill(skill.id, index, -1)"
                                        class="p-0.5 text-gray-400 hover:text-copper transition-colors" title="Move up">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                    </button>
                                    <button v-if="index < skills.length - 1" @click="moveSkill(skill.id, index, 1)"
                                        class="p-0.5 text-gray-400 hover:text-copper transition-colors"
                                        title="Move down">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl">{{ skill.icon || '🚀' }}</span>
                                    <div>
                                        <div class="font-medium">{{ skill.name }}</div>
                                        <div v-if="skill.description"
                                            class="text-xs text-gray-500 max-w-[240px] truncate">{{ skill.description }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                <span class="badge text-xs capitalize">{{ skill.category || '—' }}</span>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2 min-w-[120px]">
                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                        <div class="bg-primary-600 h-1.5 rounded-full"
                                            :style="{ width: `${skill.proficiency_level}%` }"></div>
                                    </div>
                                    <span class="text-xs font-mono text-gray-500 w-8 text-right">{{
                                        skill.proficiency_level }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-sm text-gray-500">
                                {{ skill.years_experience ? skill.years_experience + ' yrs' : '—' }}
                            </td>
                            <td class="px-6 py-3">
                                <span v-if="skill.is_featured" class="text-amber-500 text-sm">⭐ Featured</span>
                                <span v-else class="text-xs text-gray-400">—</span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <router-link :to="`/admin/skills/${skill.id}/edit`"
                                        class="p-2 rounded text-primary-600 hover:bg-primary-50 transition-colors"
                                        title="Edit">
                                        <PencilIcon class="w-4 h-4" />
                                    </router-link>
                                    <button @click="confirmDelete(skill)"
                                        class="p-2 rounded text-red-600 hover:bg-red-50 transition-colors"
                                        title="Delete">
                                        <TrashIcon class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-else class="text-center py-12">
                <div class="text-6xl mb-4">💪</div>
                <h3 class="text-xl font-semibold mb-2">No skills yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Add your first skill to showcase your expertise</p>
                <router-link to="/admin/skills/create" class="btn-primary">Add Your First Skill</router-link>
            </div>
        </div>

        <!-- Delete Modal -->
        <Modal v-model="deleteModal.open">
            <template #title>Delete Skill</template>
            <div class="text-gray-600 dark:text-gray-400 mb-6">
                Are you sure you want to delete <strong>{{ deleteModal.skill?.name }}</strong>? This action cannot be undone.
            </div>
            <div class="flex justify-end gap-3">
                <button @click="deleteModal.open = false" class="btn-outline">Cancel</button>
                <button @click="deleteSkill" class="btn-danger" :disabled="deleteModal.loading">
                    <span v-if="deleteModal.loading">Deleting...</span>
                    <span v-else>Delete</span>
                </button>
            </div>
        </Modal>
    </div>
</template>
