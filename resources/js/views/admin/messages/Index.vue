<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import Modal from '@/components/Modal.vue';
import type { Message, PaginationMeta } from '@/types';
import dayjs from 'dayjs';

const loading = ref(true);
const messages = ref<Message[]>([]);
const searchQuery = ref('');
const filters = ref({ status: '' });

const pagination = ref<PaginationMeta>({
    current_page: 1,
    from: 1,
    to: 1,
    per_page: 10,
    total: 0,
    last_page: 1,
});

const messageModal = ref({
    open: false,
    message: null as Message | null,
});

function formatDate(date: string) {
    return dayjs(date).format('MMM D, YYYY h:mm A');
}

async function fetchMessages() {
    loading.value = true;
    try {
        const params: any = {
            page: pagination.value.current_page,
            per_page: pagination.value.per_page,
        };
        if (searchQuery.value) params.search = searchQuery.value;
        if (filters.value.status) params.status = filters.value.status;

        const response = await api.get('/admin/messages', { params });
        if (response.data.success) {
            messages.value = response.data.data;
            if (response.data.meta) {
                pagination.value = response.data.meta;
            }
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
    searchTimeout = setTimeout(() => {
        pagination.value.current_page = 1;
        fetchMessages();
    }, 300);
}

function changePage(page: number) {
    pagination.value.current_page = page;
    fetchMessages();
}

function openMessageModal(message: Message) {
    messageModal.value.message = message;
    messageModal.value.open = true;

    // Mark as read
    if (message.status === 'unread') {
        api.patch(`/admin/messages/${message.id}`, { status: 'read' });
        message.status = 'read';
    }
}

async function updateMessageStatus() {
    if (!messageModal.value.message) return;

    try {
        await api.patch(`/admin/messages/${messageModal.value.message.id}`, {
            status: messageModal.value.message.status,
        });
        fetchMessages();
    } catch (error) {
        console.error('Error:', error);
    }
}

async function deleteMessage(message: Message) {
    if (!confirm(`Delete message from ${message.name}?`)) return;

    try {
        await api.delete(`/admin/messages/${message.id}`);
        messageModal.value.open = false;
        fetchMessages();
    } catch (error) {
        console.error('Error:', error);
    }
}

onMounted(fetchMessages);
</script>

<template>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-1">Messages</h1>
                <p class="text-gray-600 dark:text-gray-400">Contact form submissions</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="card">
            <div class="flex flex-wrap items-center gap-4">
                <input v-model="searchQuery" type="text" placeholder="Search messages..."
                    class="input flex-1 min-w-[200px]" @input="handleSearch" />
                <select v-model="filters.status" @change="fetchMessages" class="input w-40">
                    <option value="">All</option>
                    <option value="unread">Unread</option>
                    <option value="read">Read</option>
                    <option value="replied">Replied</option>
                </select>
            </div>
        </div>

        <!-- Messages List -->
        <div class="card overflow-hidden">
            <LoadingSpinner v-if="loading" />

            <div v-else-if="messages.length > 0">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div v-for="message in messages" :key="message.id"
                        class="p-4 sm:p-6 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors cursor-pointer"
                        @click="openMessageModal(message)">
                        <div class="flex items-start gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center shrink-0">
                                <span class="text-primary-600 dark:text-primary-400 font-semibold">
                                    {{ message.name.charAt(0) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <h3 class="font-semibold truncate">{{ message.name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ message.email }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <span :class="[
                                            'badge text-xs whitespace-nowrap',
                                            message.status === 'unread' ? 'badge-warning' :
                                                message.status === 'replied' ? 'badge-success' : 'badge'
                                        ]">
                                            {{ message.status }}
                                        </span>
                                        <button @click.stop="deleteMessage(message)"
                                            class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ formatDate(message.created_at) }}</p>
                                <p v-if="message.subject" class="text-sm font-medium mt-2">{{ message.subject }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mt-1">{{ message.message }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="pagination.last_page > 1" class="px-4 sm:px-6 py-4 border-t">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="text-sm text-center sm:text-left">
                            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }}
                        </div>
                        <div class="flex gap-2 justify-center sm:justify-end">
                            <button @click="changePage(pagination.current_page - 1)"
                                :disabled="pagination.current_page === 1"
                                class="px-4 py-2 rounded-lg hover:bg-gray-100 disabled:opacity-50 text-sm">
                                Previous
                            </button>
                            <button @click="changePage(pagination.current_page + 1)"
                                :disabled="pagination.current_page === pagination.last_page"
                                class="px-4 py-2 rounded-lg hover:bg-gray-100 disabled:opacity-50 text-sm">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-12">
                <div class="text-6xl mb-4">💬</div>
                <h3 class="text-xl font-semibold mb-2">No messages</h3>
            </div>
        </div>

        <!-- Message Detail Modal -->
        <Modal v-model="messageModal.open" size="lg">
            <template #title>Message Details</template>
            <div v-if="messageModal.message" class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Name</label>
                    <p class="text-gray-900 dark:text-gray-100">{{ messageModal.message.name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Email</label>
                    <p class="text-gray-900 dark:text-gray-100">{{ messageModal.message.email }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Subject</label>
                    <p class="text-gray-900 dark:text-gray-100">{{ messageModal.message.subject }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Message</label>
                    <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ messageModal.message.message }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Received</label>
                    <p class="text-gray-900 dark:text-gray-100">{{ messageModal.message.created_at }}</p>
                </div>
                <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button @click="deleteMessage(messageModal.message)"
                        class="btn-danger text-sm px-4 py-2 rounded-lg transition-colors">
                        Delete Message
                    </button>
                </div>
            </div>
        </Modal>
    </div>
</template>
