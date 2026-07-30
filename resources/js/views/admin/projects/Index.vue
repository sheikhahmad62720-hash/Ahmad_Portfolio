<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import Modal from '@/components/Modal.vue';
import { EyeIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import type { Project, PaginationMeta } from '@/types';
import dayjs from 'dayjs';

const loading = ref(true);
const projects = ref<Project[]>([]);
const searchQuery = ref('');

const filters = ref({
  status: '',
  featured: '',
});

const pagination = ref<PaginationMeta>({
  current_page: 1,
  from: 1,
  to: 1,
  per_page: 10,
  total: 0,
  last_page: 1,
});

const deleteModal = ref({
  open: false,
  loading: false,
  project: null as Project | null,
});

function formatDate(date: string) {
  return dayjs(date).format('MMM D, YYYY');
}

async function fetchProjects() {
  loading.value = true;

  try {
    const params: any = {
      page: pagination.value.current_page,
      per_page: pagination.value.per_page,
    };

    if (searchQuery.value) params.search = searchQuery.value;
    if (filters.value.status) params.is_published = filters.value.status === 'published' ? 1 : 0;
    if (filters.value.featured) params.is_featured = filters.value.featured;

    const response = await api.get('/admin/projects', { params });

    if (response.data.success) {
      projects.value = response.data.data;
      if (response.data.meta) {
        pagination.value = response.data.meta;
      }
    }
  } catch (error) {
    console.error('Error fetching projects:', error);
  } finally {
    loading.value = false;
  }
}

let searchTimeout: ReturnType<typeof setTimeout>;
function handleSearch() {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    pagination.value.current_page = 1;
    fetchProjects();
  }, 300);
}

function changePage(page: number) {
  pagination.value.current_page = page;
  fetchProjects();
}

function viewProject(project: Project) {
  window.open(`/portfolio/${project.slug}`, '_blank');
}

function confirmDelete(project: Project) {
  deleteModal.value.project = project;
  deleteModal.value.open = true;
}

async function deleteProject() {
  if (!deleteModal.value.project) return;

  deleteModal.value.loading = true;

  try {
    const response = await api.delete(`/admin/projects/${deleteModal.value.project.id}`);

    if (response.data.success) {
      deleteModal.value.open = false;
      fetchProjects();
    }
  } catch (error) {
    console.error('Error deleting project:', error);
  } finally {
    deleteModal.value.loading = false;
  }
}

onMounted(() => {
  fetchProjects();
});
</script>

<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold mb-1">Projects</h1>
        <p class="text-gray-600 dark:text-gray-400">Manage your portfolio projects</p>
      </div>
      <router-link to="/admin/projects/create" class="btn-primary text-center sm:w-auto w-full">
        + New Project
      </router-link>
    </div>

    <!-- Filters and Search -->
    <div class="card">
      <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
        <div class="flex-1 min-w-0">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search projects..."
            class="input w-full"
            @input="handleSearch"
          />
        </div>

        <div class="flex flex-col xs:flex-row gap-3 sm:gap-4">
          <select v-model="filters.status" @change="fetchProjects" class="input w-full xs:w-40">
            <option value="">All Status</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
          </select>

          <select v-model="filters.featured" @change="fetchProjects" class="input w-full xs:w-40">
            <option value="">All</option>
            <option value="1">Featured Only</option>
            <option value="0">Not Featured</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Projects List -->
    <div class="card overflow-hidden">
      <LoadingSpinner v-if="loading" />

      <div v-else-if="projects.length > 0">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Project
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Views
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Created
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="project in projects" :key="project.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="h-12 w-12 flex-shrink-0 mr-4">
                      <img
                        v-if="project.thumbnail"
                        :src="project.thumbnail"
                        :alt="project.title"
                        class="h-12 w-12 rounded-lg object-cover"
                      />
                      <div v-else class="h-12 w-12 rounded-lg bg-gray-200 dark:bg-gray-700"></div>
                    </div>
                    <div>
                      <div class="font-medium">{{ project.title }}</div>
                      <div class="text-sm text-gray-500">{{ project.slug }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-col gap-1">
                    <span :class="[
                      'badge text-xs inline-flex w-fit',
                      project.is_published ? 'badge-success' : 'badge-warning'
                    ]">
                      {{ project.is_published ? 'Published' : 'Draft' }}
                    </span>
                    <span v-if="project.is_featured" class="badge-primary text-xs inline-flex w-fit">
                      ⭐ Featured
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm">
                  {{ project.views_count }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                  {{ formatDate(project.created_at) }}
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-2">
                    <button
                      @click="viewProject(project)"
                      class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50"
                      title="View"
                    >
                      <EyeIcon class="w-5 h-5" />
                    </button>
                    <router-link
                      :to="`/admin/projects/${project.id}/edit`"
                      class="text-primary-600 hover:text-primary-900 p-2 rounded hover:bg-primary-50"
                      title="Edit"
                    >
                      <PencilIcon class="w-5 h-5" />
                    </router-link>
                    <button
                      @click="confirmDelete(project)"
                      class="text-red-600 hover:text-red-900 p-2 rounded hover:bg-red-50"
                      title="Delete"
                    >
                      <TrashIcon class="w-5 h-5" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
          <div v-for="project in projects" :key="project.id" class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            <div class="flex items-start gap-3">
              <div class="h-12 w-12 flex-shrink-0">
                <img
                  v-if="project.thumbnail"
                  :src="project.thumbnail"
                  :alt="project.title"
                  class="h-12 w-12 rounded-lg object-cover"
                />
                <div v-else class="h-12 w-12 rounded-lg bg-gray-200 dark:bg-gray-700"></div>
              </div>
              <div class="flex-1 min-w-0">
                <div class="font-medium truncate">{{ project.title }}</div>
                <div class="text-sm text-gray-500 truncate">{{ project.slug }}</div>
                <div class="flex items-center gap-2 mt-2">
                  <span :class="[
                    'badge text-xs',
                    project.is_published ? 'badge-success' : 'badge-warning'
                  ]">
                    {{ project.is_published ? 'Published' : 'Draft' }}
                  </span>
                  <span v-if="project.is_featured" class="badge-primary text-xs">⭐ Featured</span>
                  <span class="text-xs text-gray-500 ml-auto">{{ project.views_count }} views</span>
                </div>
                <div class="text-xs text-gray-500 mt-1">Created: {{ formatDate(project.created_at) }}</div>
                <div class="flex items-center gap-3 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                  <button @click="viewProject(project)" class="text-blue-600 hover:text-blue-900 text-sm font-medium">View</button>
                  <router-link :to="`/admin/projects/${project.id}/edit`" class="text-primary-600 hover:text-primary-900 text-sm font-medium">Edit</router-link>
                  <button @click="confirmDelete(project)" class="text-red-600 hover:text-red-900 text-sm font-medium ml-auto">Delete</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="pagination.last_page > 1" class="px-4 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="text-sm text-gray-700 dark:text-gray-300 text-center sm:text-left">
              Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} results
            </div>

            <div class="flex gap-2 justify-center sm:justify-end">
              <button
                @click="changePage(pagination.current_page - 1)"
                :disabled="pagination.current_page === 1"
                class="px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed text-sm"
              >
                Previous
              </button>
              <button
                @click="changePage(pagination.current_page + 1)"
                :disabled="pagination.current_page === pagination.last_page"
                class="px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed text-sm"
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <div class="text-6xl mb-4">📁</div>
        <h3 class="text-xl font-semibold mb-2">No projects yet</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-4">Create your first project to get started</p>
        <router-link to="/admin/projects/create" class="btn-primary">
          Create Project
        </router-link>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <Modal v-model="deleteModal.open">
      <template #title>Delete Project</template>
      <div class="text-gray-600 dark:text-gray-400 mb-6">
        Are you sure you want to delete <strong>{{ deleteModal.project?.title }}</strong>? This action cannot be undone.
      </div>
      <div class="flex justify-end gap-3">
        <button @click="deleteModal.open = false" class="btn-outline">Cancel</button>
        <button @click="deleteProject" class="btn-danger" :disabled="deleteModal.loading">
          <span v-if="deleteModal.loading">Deleting...</span>
          <span v-else>Delete</span>
        </button>
      </div>
    </Modal>
  </div>
</template>
