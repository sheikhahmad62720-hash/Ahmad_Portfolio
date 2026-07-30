import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '@/utils/api';
import type { User, LoginCredentials } from '@/types';

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null);
  const token = ref<string | null>(localStorage.getItem('token'));
  const loading = ref(false);
  const error = ref<string | null>(null);

  const isAuthenticated = computed(() => !!token.value);
  const isAdmin = computed(() => user.value?.role === 'admin' || !!token.value);
  const isClient = computed(() => user.value?.role === 'client');

  async function login(credentials: LoginCredentials) {
    loading.value = true;
    error.value = null;

    try {
      const response = await api.post('/auth/login', credentials);

      if (response.data.success) {
        token.value = response.data.data.token;
        user.value = response.data.data.user;
        localStorage.setItem('token', response.data.data.token);
        return true;
      }

      return false;
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Login failed';
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function logout() {
    loading.value = true;

    try {
      await api.post('/auth/logout');
    } catch (err) {
      console.error('Logout error:', err);
    } finally {
      user.value = null;
      token.value = null;
      localStorage.removeItem('token');
      loading.value = false;
    }
  }

  async function fetchUser() {
    if (!token.value) return;

    loading.value = true;
    error.value = null;

    try {
      const response = await api.get('/auth/user');

      if (response.data.success) {
        user.value = response.data.data;
      }
    } catch (err: any) {
      if (err.response?.status === 401) {
        await logout();
      }
      error.value = err.response?.data?.message || 'Failed to fetch user';
    } finally {
      loading.value = false;
    }
  }

  async function refreshToken() {
    if (!token.value) return;

    try {
      const response = await api.post('/auth/refresh');

      if (response.data.success) {
        token.value = response.data.data.token;
        localStorage.setItem('token', response.data.data.token);
      }
    } catch (err) {
      await logout();
    }
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    isAdmin,
    isClient,
    login,
    logout,
    fetchUser,
    refreshToken,
  };
});
