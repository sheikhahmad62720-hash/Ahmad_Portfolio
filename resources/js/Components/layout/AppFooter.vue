<script setup lang="ts">
import { contactInfo } from '@/data/contact';
</script>

<template>
  <footer
    class="relative mt-20 border-t border-gray-200 bg-gradient-to-b from-gray-50 to-white dark:border-gray-800 dark:from-graphite dark:to-navy"
  >
    <div class="absolute inset-0 grid-bg opacity-30" />
    <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-16 lg:px-8">
      <div class="mb-8 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4 sm:mb-12">
        <!-- Brand -->
        <div class="lg:col-span-2">
          <div class="mb-4 flex items-center gap-2">
            <span
              class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-copper to-copper-light font-display font-bold text-lg text-white"
            >
              MA
            </span>
            <span class="font-display text-xl font-bold">Muhammad Ahmad</span>
          </div>
          <p class="mb-6 max-w-md text-sm leading-7 text-gray-600 dark:text-gray-400 sm:text-base">
            Full Stack Developer crafting exceptional web experiences with Laravel &amp; Vue.js. Currently building
            amazing products at NBTHUB.
          </p>
          <div class="flex gap-3">
            <a
              v-for="s in contactInfo.social"
              :key="s.label"
              :href="s.href"
              target="_blank"
              rel="noopener noreferrer"
              class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-200 transition-all hover:scale-110 hover:bg-copper hover:text-white dark:bg-gray-800"
              :aria-label="s.label"
            >
              <span class="text-xs font-mono">{{ s.label.charAt(0) }}</span>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="mb-4 font-display text-lg font-semibold">Quick Links</h4>
          <ul class="space-y-3">
            <li>
              <router-link to="/" class="text-gray-600 transition-colors hover:text-copper dark:text-gray-400"
                >Home</router-link
              >
            </li>
            <li>
              <router-link to="/about" class="text-gray-600 transition-colors hover:text-copper dark:text-gray-400"
                >About</router-link
              >
            </li>
            <li>
              <router-link to="/portfolio" class="text-gray-600 transition-colors hover:text-copper dark:text-gray-400"
                >Portfolio</router-link
              >
            </li>
            <li>
              <router-link to="/blog" class="text-gray-600 transition-colors hover:text-copper dark:text-gray-400"
                >Blog</router-link
              >
            </li>
            <li>
              <router-link to="/contact" class="text-gray-600 transition-colors hover:text-copper dark:text-gray-400"
                >Contact</router-link
              >
            </li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div>
          <h4 class="mb-4 font-display text-lg font-semibold">Get In Touch</h4>
          <ul class="space-y-3 text-gray-600 dark:text-gray-400">
            <li class="flex items-start gap-2">
              <span class="mt-0.5 text-copper">📍</span>
              <span>{{ contactInfo.location }}</span>
            </li>
            <li class="flex items-start gap-2">
              <span class="mt-0.5 text-copper">📧</span>
              <a :href="`mailto:${contactInfo.email}`" class="transition-colors hover:text-copper">{{
                contactInfo.email
              }}</a>
            </li>
            <li class="flex items-start gap-2">
              <span class="mt-0.5 text-copper">📱</span>
              <a :href="contactInfo.phoneHref" class="transition-colors hover:text-copper">{{ contactInfo.phone }}</a>
            </li>
          </ul>
        </div>
      </div>

      <!-- Bottom bar -->
      <div
        class="flex flex-col items-center justify-between gap-4 border-t border-gray-200 pt-8 text-sm text-gray-600 dark:border-gray-800 dark:text-gray-400 md:flex-row"
      >
        <p>
          &copy; {{ new Date().getFullYear() }}
          <span class="font-semibold text-copper">Muhammad Ahmad</span>. All rights reserved.
        </p>
        <div class="flex items-center gap-6">
          <a href="#" class="transition-colors hover:text-copper">Privacy Policy</a>
          <a href="#" class="transition-colors hover:text-copper">Terms of Service</a>
        </div>
      </div>
    </div>
  </footer>
</template>
