<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import Modal from '@/components/Modal.vue';
import { EyeIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import type { Blog, PaginationMeta } from '@/types';
import dayjs from 'dayjs';

const loading = ref(true);
const blogs = ref<Blog[]>([]);
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
  blog: null as Blog | null,
});

function formatDate(date: string) {
  return dayjs(date).format('MMM D, YYYY');
}

async function fetchBlogs() {
  loading.value = true;

  try {
    const params: any = {
      page: pagination.value.current_page,
      per_page: pagination.value.per_page,
    };

    if (searchQuery.value) params.search = searchQuery.value;
    if (filters.value.status) params.status = filters.value.status;
    if (filters.value.featured) params.is_featured = filters.value.featured;

    const response = await api.get('/admin/blogs', { params });

    if (response.data.success) {
      blogs.value = response.data.data;
      if (response.data.meta) {
        pagination.value = response.data.meta;
      }
    }
  } catch (error) {
    console.error('Error fetching blogs:', error);
  } finally {
    loading.value = false;
  }
}

let searchTimeout: ReturnType<typeof setTimeout>;
function handleSearch() {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    pagination.value.current_page = 1;
    fetchBlogs();
  }, 300);
}

function changePage(page: number) {
  pagination.value.current_page = page;
  fetchBlogs();
}

function viewBlog(blog: Blog) {
  window.open(`/blog/${blog.slug}`, '_blank');
}

function confirmDelete(blog: Blog) {
  deleteModal.value.blog = blog;
  deleteModal.value.open = true;
}

async function deleteBlog() {
  if (!deleteModal.value.blog) return;

  deleteModal.value.loading = true;

  try {
    const response = await api.delete(`/admin/blogs/${deleteModal.value.blog.id}`);

    if (response.data.success) {
      deleteModal.value.open = false;
      fetchBlogs();
    }
  } catch (error) {
    console.error('Error deleting blog:', error);
  } finally {
    deleteModal.value.loading = false;
  }
}

onMounted(() => {
  fetchBlogs();
});
</script>

<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold mb-1">Blog Posts</h1>
        <p class="text-gray-600 dark:text-gray-400">Manage your blog content</p>
      </div>
      <router-link to="/admin/blogs/create" class="btn-primary text-center sm:w-auto w-full">
        + New Post
      </router-link>
    </div>

    <!-- Filters -->
    <div class="card">
      <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
        <div class="flex-1 min-w-0">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search posts..."
            class="input w-full"
            @input="handleSearch"
          />
        </div>

        <div class="flex flex-col xs:flex-row gap-3 sm:gap-4">
          <select v-model="filters.status" @change="fetchBlogs" class="input w-full xs:w-40">
            <option value="">All Status</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
          </select>

          <select v-model="filters.featured" @change="fetchBlogs" class="input w-full xs:w-40">
            <option value="">All</option>
            <option value="1">Featured Only</option>
            <option value="0">Not Featured</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Blogs List -->
    <div class="card overflow-hidden">
      <LoadingSpinner v-if="loading" />

      <div v-else-if="blogs.length > 0">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                  Post
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                  Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                  Views
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                  Published
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="blog in blogs" :key="blog.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="h-12 w-12 flex-shrink-0 mr-4">
                      <img
                        v-if="blog.featured_image"
                        :src="blog.featured_image"
                        :alt="blog.title"
                        class="h-12 w-12 rounded-lg object-cover"
                      />
                      <div v-else class="h-12 w-12 rounded-lg bg-gray-200 dark:bg-gray-700"></div>
                    </div>
                    <div>
                      <div class="font-medium">{{ blog.title }}</div>
                      <div class="text-sm text-gray-500">{{ blog.reading_time }} min read</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-col gap-1">
                    <span :class="[
                      'badge text-xs inline-flex w-fit',
                      blog.status === 'published' ? 'badge-success' : 'badge-warning'
                    ]">
                      {{ blog.status === 'published' ? 'Published' : 'Draft' }}
                    </span>
                    <span v-if="blog.is_featured" class="badge-primary text-xs inline-flex w-fit">
                      ⭐ Featured
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm">
                  {{ blog.views_count }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                  {{ blog.published_at ? formatDate(blog.published_at) : '-' }}
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                  <div class="flex items-center justify-end gap-2">
                    <button
                      @click="viewBlog(blog)"
                      class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50"
                      title="View"
                    >
                      <EyeIcon class="w-5 h-5" />
                    </button>
                    <router-link
                      :to="`/admin/blogs/${blog.id}/edit`"
                      class="text-primary-600 hover:text-primary-900 p-2 rounded hover:bg-primary-50"
                      title="Edit"
                    >
                      <PencilIcon class="w-5 h-5" />
                    </router-link>
                    <button
                      @click="confirmDelete(blog)"
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
          <div v-for="blog in blogs" :key="blog.id" class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            <div class="flex items-start gap-3">
              <div class="h-12 w-12 flex-shrink-0">
                <img
                  v-if="blog.featured_image"
                  :src="blog.featured_image"
                  :alt="blog.title"
                  class="h-12 w-12 rounded-lg object-cover"
                />
                <div v-else class="h-12 w-12 rounded-lg bg-gray-200 dark:bg-gray-700"></div>
              </div>
              <div class="flex-1 min-w-0">
                <div class="font-medium truncate">{{ blog.title }}</div>
                <div class="text-sm text-gray-500">{{ blog.reading_time }} min read</div>
                <div class="flex items-center gap-2 mt-2">
                  <span :class="[
                    'badge text-xs',
                    blog.status === 'published' ? 'badge-success' : 'badge-warning'
                  ]">
                    {{ blog.status === 'published' ? 'Published' : 'Draft' }}
                  </span>
                  <span v-if="blog.is_featured" class="badge-primary text-xs">⭐ Featured</span>
                  <span class="text-xs text-gray-500 ml-auto">{{ blog.views_count }} views</span>
                </div>
                <div class="text-xs text-gray-500 mt-1">Published: {{ blog.published_at ? formatDate(blog.published_at) : '-' }}</div>
                <div class="flex items-center gap-3 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                  <button @click="viewBlog(blog)" class="text-blue-600 hover:text-blue-900 text-sm font-medium">View</button>
                  <router-link :to="`/admin/blogs/${blog.id}/edit`" class="text-primary-600 hover:text-primary-900 text-sm font-medium">Edit</router-link>
                  <button @click="confirmDelete(blog)" class="text-red-600 hover:text-red-900 text-sm font-medium ml-auto">Delete</button>
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
                class="px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50 text-sm"
              >
                Previous
              </button>
              <button
                @click="changePage(pagination.current_page + 1)"
                :disabled="pagination.current_page === pagination.last_page"
                class="px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-50 text-sm"
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <div class="text-6xl mb-4">📝</div>
        <h3 class="text-xl font-semibold mb-2">No blog posts yet</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-4">Create your first post</p>
        <router-link to="/admin/blogs/create" class="btn-primary">
          Create Post
        </router-link>
      </div>
    </div>

    <!-- Delete Modal -->
    <Modal v-model="deleteModal.open">
      <template #title>Delete Blog Post</template>
      <div class="text-gray-600 dark:text-gray-400 mb-6">
        Are you sure you want to delete <strong>{{ deleteModal.blog?.title }}</strong>? This action cannot be undone.
      </div>
      <div class="flex justify-end gap-3">
        <button @click="deleteModal.open = false" class="btn-outline">Cancel</button>
        <button @click="deleteBlog" class="btn-danger" :disabled="deleteModal.loading">
          <span v-if="deleteModal.loading">Deleting...</span>
          <span v-else>Delete</span>
        </button>
      </div>
    </Modal>
  </div>
</template>
