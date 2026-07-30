<script setup lang="ts">
import { ref } from 'vue';
import { useThemeStore } from '@/stores/theme';
import { useAuthStore } from '@/stores/auth';
import { Bars3Icon, XMarkIcon, SunIcon, MoonIcon } from '@heroicons/vue/24/outline';

const themeStore = useThemeStore();
const authStore = useAuthStore();
const mobileMenuOpen = ref(false);

const menuItems = [
    { name: 'Home', path: '/' },
    { name: 'About', path: '/about' },
    { name: 'Portfolio', path: '/portfolio' },
    { name: 'Blog', path: '/blog' },
    { name: 'Contact', path: '/contact' },
];
</script>

<template>
    <div class="min-h-screen bg-white dark:bg-navy ">
        <!-- Navigation -->
        <nav class="fixed top-0 w-full z-50 bg-navy/95 backdrop-blur-xl border-b border-gray-800/50">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 ">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <router-link to="/" class="flex items-center space-x-3 group">
                        <div
                            class="w-11 h-11 rounded-xl bg-gradient-to-br from-copper to-copper-light flex items-center justify-center text-white font-display font-bold text-base group-hover:scale-110 transition-transform shadow-lg">
                            MA
                        </div>
                        <span
                            class="text-sm sm:text-xl font-display font-bold text-white truncate max-w-[120px] sm:max-w-none">Muhammad
                            Ahmad</span>
                    </router-link>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex items-center space-x-2">
                        <router-link v-for="item in menuItems" :key="item.path" :to="item.path" exact
                            class="px-5 py-2.5 rounded-xl font-medium text-gray-300 transition-all hover:bg-white/10 hover:text-white"
                            exact-active-class="bg-copper/20 text-copper font-semibold">
                            {{ item.name }}
                        </router-link>

                        <!-- Admin Link - only when logged in -->
                        <router-link v-if="authStore.isAuthenticated" to="/admin"
                            class="px-5 py-2.5 rounded-xl font-medium text-gray-300 transition-all hover:bg-white/10 hover:text-white"
                            active-class="bg-copper/20 text-copper font-semibold">
                            Admin
                        </router-link>

                        <!-- Theme Toggle -->
                        <button @click="themeStore.toggle()"
                            class="ml-3 p-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white transition-all"
                            aria-label="Toggle theme">
                            <component :is="themeStore.isDark ? SunIcon : MoonIcon" class="w-5 h-5" />
                        </button>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden p-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white transition-all">
                        <component :is="mobileMenuOpen ? XMarkIcon : Bars3Icon" class="w-6 h-6" />
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div v-if="mobileMenuOpen" class="md:hidden border-t border-gray-800/50 bg-navy/98 backdrop-blur-xl">
                <div class="px-4 py-4 space-y-2">
                    <router-link v-for="item in menuItems" :key="item.path" :to="item.path"
                        @click="mobileMenuOpen = false"
                        class="block px-5 py-3 rounded-xl text-gray-300 hover:bg-white/10 hover:text-white transition-all font-medium">
                        {{ item.name }}
                    </router-link>
                    <router-link v-if="authStore.isAuthenticated" to="/admin" @click="mobileMenuOpen = false"
                        class="block px-5 py-3 rounded-xl text-gray-300 hover:bg-white/10 hover:text-white transition-all font-medium">
                        Admin
                    </router-link>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="pt-0">
            <router-view />
        </main>

        <!-- Footer -->
        <footer
            class="relative bg-gradient-to-b from-gray-50 to-white dark:from-graphite dark:to-navy border-t border-gray-200 dark:border-gray-800 mt-20">
            <!-- Grid Background -->
            <div class="absolute inset-0 grid-bg opacity-30"></div>

            <div class="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-10 sm:py-16">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-12 mb-8 sm:mb-12">
                    <!-- Brand -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center gap-2 mb-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-copper to-copper-light flex items-center justify-center text-white font-display font-bold text-lg">
                                MA
                            </div>
                            <span class="text-xl font-display font-bold">Muhammad Ahmad</span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 leading-7 mb-6 max-w-md text-sm sm:text-base">
                            Full Stack Developer crafting exceptional web experiences with Laravel & Vue.js.
                            Currently building amazing products at NBTHUB.
                        </p>

                        <!-- Social Links -->
                        <div class="flex gap-3">
                            <a href="https://github.com/sheikhahmad62720-hash"
                                class="w-10 h-10 rounded-xl bg-gray-200 dark:bg-gray-800 hover:bg-copper hover:text-white dark:hover:bg-copper flex items-center justify-center transition-all hover:scale-110">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                </svg>
                            </a>
                            <a href="https://www.linkedin.com/in/muhammad-ahmad-85135a28b?utm_source=share_via&utm_content=profile&utm_medium=member_android"
                                class="w-10 h-10 rounded-xl bg-gray-200 dark:bg-gray-800 hover:bg-copper hover:text-white dark:hover:bg-copper flex items-center justify-center transition-all hover:scale-110">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                </svg>
                            </a>
                            <a href="https://x.com/ahmaddkhan628"
                                class="w-10 h-10 rounded-xl bg-gray-200 dark:bg-gray-800 hover:bg-copper hover:text-white dark:hover:bg-copper flex items-center justify-center transition-all hover:scale-110">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                </svg>
                            </a>
                            <a href="https://www.instagram.com/ahmad_sheikh_79?igsh=dnVhM3I2enpxaXNh"
                                class="w-10 h-10 rounded-xl bg-gray-200 dark:bg-gray-800 hover:bg-copper hover:text-white dark:hover:bg-copper flex items-center justify-center transition-all hover:scale-110">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <!-- Quick Links -->
                    <div>
                        <h4 class="font-display font-semibold text-lg mb-4">Quick Links</h4>
                        <ul class="space-y-3">
                            <li><router-link to="/"
                                    class="text-gray-600 dark:text-gray-400 hover:text-copper transition-colors">Home</router-link>
                            </li>
                            <li><router-link to="/about"
                                    class="text-gray-600 dark:text-gray-400 hover:text-copper transition-colors">About</router-link>
                            </li>
                            <li><router-link to="/portfolio"
                                    class="text-gray-600 dark:text-gray-400 hover:text-copper transition-colors">Portfolio</router-link>
                            </li>
                            <li><router-link to="/blog"
                                    class="text-gray-600 dark:text-gray-400 hover:text-copper transition-colors">Blog</router-link>
                            </li>
                            <li><router-link to="/contact"
                                    class="text-gray-600 dark:text-gray-400 hover:text-copper transition-colors">Contact</router-link>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h4 class="font-display font-semibold text-lg mb-4">Get In Touch</h4>
                        <ul class="space-y-3 text-gray-600 dark:text-gray-400">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-copper flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Haroonabad, Pakistan</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-copper flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a href="mailto:sheikhahmad62720@gmail.com"
                                    class="hover:text-copper transition-colors">sheikhahmad62720@gmail.com</a>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-copper flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:+923316918426" class="hover:text-copper transition-colors">+92 331
                                    6918426</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom Bar -->
                <div
                    class="border-t border-gray-200 dark:border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        &copy; {{ new Date().getFullYear() }} <span class="font-semibold text-copper">Muhammad
                            Ahmad</span>. All rights reserved.
                    </p>
                    <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-400">
                        <a href="#" class="hover:text-copper transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-copper transition-colors">Terms of Service</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
