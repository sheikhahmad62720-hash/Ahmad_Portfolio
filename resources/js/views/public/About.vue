<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/utils/api';


const loading = ref(true);
const experiences = ref<any[]>([]);
const education = ref<any[]>([]);

function formatDate(date: string | null) {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short' });
}

async function fetchData() {
    try {
        const [expRes, eduRes] = await Promise.all([
            api.get('/portfolio/experience'),
            api.get('/portfolio/education'),
        ]);
        if (expRes.data.success) experiences.value = expRes.data.data;
        if (eduRes.data.success) education.value = eduRes.data.data;
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    fetchData();
});
</script>

<template>
    <div class="min-h-screen ">
        <!-- Header -->
        <section class="section bg-gradient-to-b from-gray-50 to-white dark:from-graphite dark:to-navy">
            <div class="container-custom text-center animate-fade-in">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-copper/10 border border-copper/20 mb-6 mt-8">
                    <span class="text-sm font-mono text-copper">About Me</span>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-display font-bold mb-6">
                    Full Stack Developer with a <span class="text-gradient">passion for excellence</span>
                </h1>
                <p
                    class="text-base sm:text-lg md:text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto leading-relaxed">
                    Building production-ready applications with modern technologies and best practices
                </p>
            </div>
        </section>
        <!-- Profile Section -->
        <section class="section">
            <div class="container-custom">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center">
                    <!-- Image -->
                    <div class="relative animate-scale-in max-w-sm mx-auto lg:max-w-none">
                        <div
                            class="aspect-square rounded-2xl overflow-hidden shadow-2xl border-4 border-copper/20 hover:border-copper/40 transition-all">
                            <img src="/assets/ahmad.webp" alt="Muhammad Ahmad"
                                class="w-full h-full object-cover object-top" />
                        </div>
                        <div class="absolute -bottom-6 -right-6 glass-card p-4">
                            <div class="text-3xl font-bold text-gradient">5+</div>
                            <div class="text-sm font-mono text-gray-600 dark:text-gray-400">Years Experience</div>
                        </div>
                    </div>
                    <!-- Content -->
                    <div class="space-y-2 animate-slide-up">
                        <h2 class="heading-md">Hi, I'm Muhammad Ahmad</h2>
                        <div class="space-y-4 text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                            <p>
                                I'm a passionate <span class="text-copper font-semibold">Full Stack Developer</span>
                                specializing in Laravel and Vue.js,
                                currently working at <span class="text-copper font-semibold">NBTHUB</span>. With over 5
                                years of experience,
                                I've helped numerous clients bring their ideas to life through clean, scalable
                                applications.
                            </p>
                            <p>
                                My journey started with curiosity about how things work behind the scenes. Today, I
                                combine
                                technical expertise with creative problem-solving to build modern web applications that
                                deliver
                                exceptional performance and user experience.
                            </p>
                        </div>
                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-4 pt-6">
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-copper/10 flex items-center justify-center flex-shrink-0 text-sm sm:text-base">
                                        📍</div>
                                    <div class="min-w-0">
                                        <div class="text-xs sm:text-sm font-mono text-gray-500">Location</div>
                                        <div class="font-semibold text-sm sm:text-base truncate">Haroonabad, Pakistan
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-electric/10 flex items-center justify-center flex-shrink-0 text-sm sm:text-base">
                                        💼</div>
                                    <div class="min-w-0">
                                        <div class="text-xs sm:text-sm font-mono text-gray-500">Company</div>
                                        <div class="font-semibold text-sm sm:text-base truncate">NBTHUB</div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-copper/10 flex items-center justify-center flex-shrink-0 text-sm sm:text-base">
                                        📧</div>
                                    <div class="min-w-0">
                                        <div class="text-xs sm:text-sm font-mono text-gray-500">Email</div>
                                        <div class="font-semibold text-xs sm:text-sm truncate">
                                            sheikhahmad62720@gmail.com</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-electric/10 flex items-center justify-center flex-shrink-0 text-sm sm:text-base">
                                        🎓</div>
                                    <div class="min-w-0">
                                        <div class="text-xs sm:text-sm font-mono text-gray-500">Education</div>
                                        <div class="font-semibold text-sm sm:text-base truncate">Computer Science</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- CTA -->
                        <div class="flex gap-4 pt-6">
                            <router-link to="/contact" class="btn-primary">Hire Me</router-link>
                            <a href="#" class="btn-outline">Download CV</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Work Experience -->
        <section class="section bg-gray-50 dark:bg-graphite">
            <div class="container-custom">
                <div class="text-center mb-12">
                    <h2 class="text-responsive-section font-display font-bold mb-4">Work <span
                            class="text-gradient">Experience</span></h2>
                    <p class="text-gray-600 dark:text-gray-400">My professional journey</p>
                </div>
                <div class="max-w-4xl mx-auto space-y-6">
                    <div v-if="loading" class="text-center py-8 text-gray-500">Loading...</div>
                    <div v-for="exp in experiences" :key="exp.id"
                        class="card-hover p-5 sm:p-8 border-l-4 border-copper">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                            <div>
                                <h3 class="text-lg sm:text-2xl font-display font-semibold mb-1">{{ exp.position }}</h3>
                                <p class="text-copper font-semibold">{{ exp.company }}</p>
                            </div>
                            <div
                                class="text-xs sm:text-sm font-mono text-gray-500 mt-2 md:mt-0 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-copper/10">
                                {{ formatDate(exp.start_date) }} - {{ exp.is_current ? 'Present' :
                                    formatDate(exp.end_date) }}
                            </div>
                        </div>
                        <p v-if="exp.location" class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            📍 {{ exp.location }} &bull; {{ exp.employment_type }}
                        </p>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ exp.description }}</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Education -->
        <section class="section">
            <div class="container-custom">
                <div class="text-center mb-12">
                    <h2 class="text-responsive-section font-display font-bold mb-4">My <span
                            class="text-gradient">Education</span></h2>
                    <p class="text-gray-600 dark:text-gray-400">Academic background</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
                    <div v-for="edu in education" :key="edu.id" class="card-hover p-5 sm:p-6">
                        <div class="flex items-start gap-4 mb-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-copper to-electric flex items-center justify-center text-2xl">
                                🎓
                            </div>
                            <div class="flex-1">
                                <h3 class="font-display font-semibold text-lg mb-1">{{ edu.degree }}</h3>
                                <p class="text-copper font-medium">{{ edu.institution }}</p>
                                <p class="text-sm font-mono text-gray-500 mt-1">
                                    {{ formatDate(edu.start_date) }} - {{ edu.is_current ? 'Present' :
                                        formatDate(edu.end_date) }}
                                </p>
                            </div>
                        </div>
                        <div v-if="edu.field_of_study" class="text-gray-600 dark:text-gray-400 text-sm mb-2">
                            Field: {{ edu.field_of_study }}
                        </div>
                        <div v-if="edu.grade" class="badge-copper">Grade: {{ edu.grade }}</div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Skills -->
        <section class="section bg-gray-50 dark:bg-graphite">
            <div class="container-custom">
                <div class="text-center mb-12">
                    <h2 class="text-responsive-section font-display font-bold mb-4">Skills &amp; <span
                            class="text-gradient">Expertise</span></h2>
                    <p class="text-gray-600 dark:text-gray-400">Technologies I work with</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 md:gap-8 max-w-5xl mx-auto">
                    <div class="card-hover p-5 sm:p-6">
                        <h3 class="font-display font-semibold text-lg sm:text-xl mb-4">Frontend Development</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li v-for="item in ['Vue.js 3 & Composition API', 'React & Next.js', 'TypeScript', 'Tailwind CSS']"
                                :key="item" class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-copper flex-shrink-0"></span>{{ item }}
                            </li>
                        </ul>
                    </div>
                    <div class="card-hover p-5 sm:p-6">
                        <h3 class="font-display font-semibold text-lg sm:text-xl mb-4">Backend Development</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li v-for="item in ['Laravel & PHP', 'RESTful API Design', 'MySQL & PostgreSQL', 'Authentication & Security']"
                                :key="item" class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-electric flex-shrink-0"></span>{{ item }}
                            </li>
                        </ul>
                    </div>
                    <div class="card-hover p-5 sm:p-6">
                        <h3 class="font-display font-semibold text-lg sm:text-xl mb-4">Tools &amp; DevOps</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li v-for="item in ['Git & GitHub', 'Docker & CI/CD', 'AWS & Cloud Services', 'Performance Optimization']"
                                :key="item" class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-copper flex-shrink-0"></span>{{ item }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
