import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const routes: RouteRecordRaw[] = [
  // Public Routes
  {
    path: '/',
    component: () => import('@/layouts/PublicLayout.vue'),
    children: [
      {
        path: '',
        name: 'home',
        component: () => import('@/views/public/Home.vue'),
        meta: { title: 'Home' },
      },
      {
        path: 'about',
        name: 'about',
        component: () => import('@/views/public/About.vue'),
        meta: { title: 'About' },
      },
      {
        path: 'portfolio',
        name: 'portfolio',
        component: () => import('@/views/public/Portfolio.vue'),
        meta: { title: 'Portfolio' },
      },
      {
        path: 'portfolio/:slug',
        name: 'project-detail',
        component: () => import('@/views/public/ProjectDetail.vue'),
        meta: { title: 'Project Details' },
      },
      {
        path: 'blog',
        name: 'blog',
        component: () => import('@/views/public/Blog.vue'),
        meta: { title: 'Blog' },
      },
      {
        path: 'blog/:slug',
        name: 'blog-detail',
        component: () => import('@/views/public/BlogDetail.vue'),
        meta: { title: 'Blog Post' },
      },
      {
        path: 'contact',
        name: 'contact',
        component: () => import('@/views/public/Contact.vue'),
        meta: { title: 'Contact' },
      },
    ],
  },

  // Auth Routes
  {
    path: '/login',
    component: () => import('@/layouts/AuthLayout.vue'),
    children: [
      {
        path: '',
        name: 'login',
        component: () => import('@/views/auth/Login.vue'),
        meta: { title: 'Login', guest: true },
      },
    ],
  },
  {
    path: '/auth',
    component: () => import('@/layouts/AuthLayout.vue'),
    children: [
      {
        path: 'register',
        name: 'register',
        component: () => import('@/views/auth/Register.vue'),
        meta: { title: 'Register', guest: true },
      },
      {
        path: 'forgot-password',
        name: 'forgot-password',
        component: () => import('@/views/auth/ForgotPassword.vue'),
        meta: { title: 'Forgot Password', guest: true },
      },
      {
        path: 'reset-password/:token',
        name: 'reset-password',
        component: () => import('@/views/auth/ResetPassword.vue'),
        meta: { title: 'Reset Password', guest: true },
      },
    ],
  },

  // Admin Routes
  {
    path: '/admin',
    component: () => import('@/layouts/AdminLayout.vue'),
    children: [
      {
        path: '',
        name: 'admin-dashboard',
        component: () => import('@/views/admin/Dashboard.vue'),
        meta: { title: 'Dashboard', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'projects',
        name: 'admin-projects',
        component: () => import('@/views/admin/projects/Index.vue'),
        meta: { title: 'Projects', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'projects/create',
        name: 'admin-projects-create',
        component: () => import('@/views/admin/projects/Create.vue'),
        meta: { title: 'Create Project', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'projects/:id/edit',
        name: 'admin-projects-edit',
        component: () => import('@/views/admin/projects/Edit.vue'),
        meta: { title: 'Edit Project', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'blogs',
        name: 'admin-blogs',
        component: () => import('@/views/admin/blogs/Index.vue'),
        meta: { title: 'Blogs', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'blogs/create',
        name: 'admin-blogs-create',
        component: () => import('@/views/admin/blogs/Create.vue'),
        meta: { title: 'Create Blog Post', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'blogs/:id/edit',
        name: 'admin-blogs-edit',
        component: () => import('@/views/admin/blogs/Edit.vue'),
        meta: { title: 'Edit Blog Post', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'skills',
        name: 'admin-skills',
        component: () => import('@/views/admin/skills/Index.vue'),
        meta: { title: 'Skills', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'skills/create',
        name: 'admin-skills-create',
        component: () => import('@/views/admin/skills/Create.vue'),
        meta: { title: 'Add Skill', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'skills/:id/edit',
        name: 'admin-skills-edit',
        component: () => import('@/views/admin/skills/Edit.vue'),
        meta: { title: 'Edit Skill', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'experience',
        name: 'admin-experience',
        component: () => import('@/views/admin/experience/Index.vue'),
        meta: { title: 'Experience', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'experience/create',
        name: 'admin-experience-create',
        component: () => import('@/views/admin/experience/Create.vue'),
        meta: { title: 'Add Experience', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'experience/:id/edit',
        name: 'admin-experience-edit',
        component: () => import('@/views/admin/experience/Edit.vue'),
        meta: { title: 'Edit Experience', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'education',
        name: 'admin-education',
        component: () => import('@/views/admin/education/Index.vue'),
        meta: { title: 'Education', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'education/create',
        name: 'admin-education-create',
        component: () => import('@/views/admin/education/Create.vue'),
        meta: { title: 'Add Education', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'education/:id/edit',
        name: 'admin-education-edit',
        component: () => import('@/views/admin/education/Edit.vue'),
        meta: { title: 'Edit Education', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'messages',
        name: 'admin-messages',
        component: () => import('@/views/admin/messages/Index.vue'),
        meta: { title: 'Messages', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'testimonials',
        name: 'admin-testimonials',
        component: () => import('@/views/admin/testimonials/Index.vue'),
        meta: { title: 'Testimonials', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'testimonials/create',
        name: 'admin-testimonials-create',
        component: () => import('@/views/admin/testimonials/Create.vue'),
        meta: { title: 'Add Testimonial', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'testimonials/:id/edit',
        name: 'admin-testimonials-edit',
        component: () => import('@/views/admin/testimonials/Edit.vue'),
        meta: { title: 'Edit Testimonial', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'analytics',
        name: 'admin-analytics',
        component: () => import('@/views/admin/Analytics.vue'),
        meta: { title: 'Analytics', requiresAuth: true, requiresAdmin: true },
      },
      {
        path: 'settings',
        name: 'admin-settings',
        component: () => import('@/views/admin/settings/Index.vue'),
        meta: { title: 'Settings', requiresAuth: true, requiresAdmin: true },
      },
    ],
  },

  // 404 Not Found
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('@/views/NotFound.vue'),
    meta: { title: '404 Not Found' },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { top: 0 };
    }
  },
});

// Navigation guards
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  // Set page title
  document.title = to.meta.title
    ? `${to.meta.title} - Muhammad Ahmad`
    : 'Muhammad Ahmad - Portfolio';

  // Restore session if token exists but user not loaded
  if (authStore.token && !authStore.user) {
    await authStore.fetchUser();
  }

  // Check authentication
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'login', query: { redirect: to.fullPath } });
  } else if (to.meta.guest && authStore.isAuthenticated) {
    next({ name: 'admin-dashboard' });
  } else if (to.meta.requiresAdmin && !authStore.isAdmin) {
    next({ name: 'login' });
  } else {
    next();
  }
});

export default router;
