<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/utils/api';
import TechOrbit from '@/components/TechOrbit.vue';
import TypeWriter from '@/components/TypeWriter.vue';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import type { Project, Skill, Testimonial, BlogPost } from '@/types';

const router = useRouter();

// Swiper logic
const currentSlide = ref(0);
const slidesPerView = ref(3);
const slideWidth = computed(() => 100 / slidesPerView.value);

function updateSlidesPerView() {
    const width = window.innerWidth;
    if (width < 768) {
        slidesPerView.value = 1;
    } else if (width < 1024) {
        slidesPerView.value = 2;
    } else {
        slidesPerView.value = 3;
    }
    const maxSlide = Math.max(0, testimonials.value.length - slidesPerView.value);
    if (currentSlide.value > maxSlide) {
        currentSlide.value = maxSlide;
    }
}

function nextSlide() {
    const maxSlide = testimonials.value.length - slidesPerView.value;
    if (currentSlide.value < maxSlide) {
        currentSlide.value += slidesPerView.value;
        if (currentSlide.value > maxSlide) currentSlide.value = maxSlide;
    } else {
        currentSlide.value = 0;
    }
}

function prevSlide() {
    if (currentSlide.value > 0) {
        currentSlide.value -= slidesPerView.value;
        if (currentSlide.value < 0) currentSlide.value = 0;
    } else {
        currentSlide.value = Math.max(0, testimonials.value.length - slidesPerView.value);
    }
}

function goToSlide(index: number) {
    currentSlide.value = index * slidesPerView.value;
}

onMounted(() => {
    updateSlidesPerView();
    window.addEventListener('resize', updateSlidesPerView);
    fetchFeaturedProjects();
    fetchSkills();
    fetchTestimonials();
    fetchLatestBlogs();
});

onUnmounted(() => {
    window.removeEventListener('resize', updateSlidesPerView);
});

const roles = [
    'Full Stack Developer',
    'Laravel Expert',
    'Vue.js Specialist',
    'Backend Architect',
    'UI/UX Enthusiast',
];

const services = [
    {
        icon: '🚀',
        title: 'Web Development',
        description: 'Custom web applications built with cutting-edge technologies.',
        features: ['Responsive Design', 'RESTful APIs', 'Database Design', 'Performance']
    },
    {
        icon: '💡',
        title: 'SaaS Solutions',
        description: 'Complete SaaS products from concept to deployment.',
        features: ['Multi-tenancy', 'Payments', 'Analytics', 'Scalability']
    },
    {
        icon: '🎨',
        title: 'UI/UX Design',
        description: 'Beautiful interfaces with exceptional user experience.',
        features: ['Wireframing', 'Prototyping', 'User Testing', 'Design Systems']
    },
    {
        icon: '⚡',
        title: 'API Development',
        description: 'Robust APIs with comprehensive documentation.',
        features: ['REST & GraphQL', 'Authentication', 'Rate Limiting', 'Versioning']
    },
    {
        icon: '🔧',
        title: 'Consulting',
        description: 'Expert advice on architecture and best practices.',
        features: ['Code Review', 'Architecture', 'Performance', 'Security']
    },
    {
        icon: '🛡️',
        title: 'Maintenance',
        description: 'Ongoing support to keep your apps running smoothly.',
        features: ['Bug Fixes', 'Updates', 'Monitoring', '24/7 Support']
    }
];

const loadingProjects = ref(true);
const loadingSkills = ref(true);
const loadingTestimonials = ref(true);
const loadingBlogs = ref(true);

const featuredProjects = ref<Project[]>([]);
const skills = ref<Skill[]>([]);
const testimonials = ref<Testimonial[]>([]);
const latestBlogs = ref<BlogPost[]>([]);

async function fetchFeaturedProjects() {
    try {
        const response = await api.get('/portfolio/projects', {
            params: { is_featured: true, per_page: 6 }
        });
        if (response.data.success) {
            featuredProjects.value = response.data.data;
        }
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loadingProjects.value = false;
    }
}

async function fetchSkills() {
    try {
        const response = await api.get('/portfolio/skills');
        if (response.data.success) {
            // Flatten grouped skills into single array
            const grouped = response.data.data;
            skills.value = Object.values(grouped).flat();
        }
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loadingSkills.value = false;
    }
}

async function fetchTestimonials() {
    try {
        const response = await api.get('/portfolio/testimonials', {
            params: { per_page: 6 }
        });
        if (response.data.success) {
            testimonials.value = response.data.data;
        }
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loadingTestimonials.value = false;
    }
}

async function fetchLatestBlogs() {
    try {
        const response = await api.get('/blogs', {
            params: { per_page: 3 }
        });
        if (response.data.success) {
            latestBlogs.value = response.data.data;
        }
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loadingBlogs.value = false;
    }
}

function navigateToProject(slug: string) {
    router.push({ name: 'project-detail', params: { slug } });
}

function navigateToBlog(slug: string) {
    router.push({ name: 'blog-detail', params: { slug } });
}

function formatDate(date: string) {
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
}
</script>

<template>
    <div class="min-h-screen">
        <!-- Hero Section -->
        <section
            class="relative min-h-screen flex items-center justify-center overflow-hidden bg-white dark:bg-navy pt-24 sm:pt-0 pb-16 sm:pb-0">
            <!-- Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-copper/5 via-transparent to-electric/5"></div>
            <div class="absolute inset-0 grid-bg opacity-30"></div>

            <div class="container-custom relative z-10 px-4 sm:px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center">
                    <!-- Left: Content -->
                    <div class="text-center lg:text-left">
                        <div
                            class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full glass-card border border-copper/20 mb-4 sm:mb-6">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            <span class="text-xs sm:text-sm font-mono text-copper">Available for Freelance</span>
                        </div>

                        <h1 class="text-2xl sm:text-4xl md:text-5xl font-display font-bold tracking-tight mb-4">
                            Hi, I'm <br />
                            <span class="text-gradient">Muhammad Ahmad</span>
                        </h1>

                        <div
                            class="text-sm sm:text-2xl md:text-3xl font-display font-semibold text-gray-700 dark:text-gray-300 mb-6 min-h-[1.5rem] sm:h-10">
                            <TypeWriter :texts="roles" />
                        </div>

                        <p
                            class="text-sm sm:text-lg text-gray-600 dark:text-gray-400 mb-8 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                            Crafting exceptional web experiences with Laravel & Vue.js.
                            Currently building products at NBTHUB.
                        </p>

                        <div class="flex flex-wrap gap-3 sm:gap-4 justify-center lg:justify-start">
                            <router-link to="/portfolio" class="btn-primary w-full sm:w-auto text-center">
                                View My Work →
                            </router-link>
                            <router-link to="/contact" class="btn-outline w-full sm:w-auto text-center">
                                Get In Touch
                            </router-link>
                        </div>
                    </div>

                    <!-- Right: 3D Orbit -->
                    <div
                        class="flex justify-center lg:justify-end mt-8 lg:mt-0 max-w-[260px] sm:max-w-none mx-auto scale-90 sm:scale-100">
                        <TechOrbit />
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="hidden sm:flex absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
                <div class="w-6 h-10 border-2 border-copper rounded-full flex justify-center">
                    <div class="w-1 h-2 bg-copper rounded-full mt-2 animate-pulse"></div>
                </div>
            </div>
        </section>

        <!-- Stats Bar -->
        <section class="py-8 sm:py-12 bg-gray-50 dark:bg-graphite border-y border-gray-200 dark:border-gray-800">
            <div class="container-custom">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-8">
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-gradient mb-2">5+</div>
                        <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Years Experience</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-gradient mb-2">50+</div>
                        <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Projects Delivered</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-gradient mb-2">30+</div>
                        <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Happy Clients</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-gradient mb-2">100%</div>
                        <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Satisfaction</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Projects -->
        <section class="section">
            <div class="container-custom">
                <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-12">
                    <h2 class="text-responsive-section font-display font-bold mb-4">
                        Featured <span class="text-gradient">Work</span>
                    </h2>
                    <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400">
                        Explore my latest projects and creative solutions
                    </p>
                </div>

                <LoadingSpinner v-if="loadingProjects" />

                <div v-else-if="featuredProjects.length > 0"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <div v-for="project in featuredProjects" :key="project.id" @click="navigateToProject(project.slug)"
                        class="group cursor-pointer">
                        <div class="card-hover overflow-hidden">
                            <div
                                class="aspect-[4/3] relative overflow-hidden rounded-t-2xl bg-gray-100 dark:bg-gray-800">
                                <img v-if="project.thumbnail" :src="project.thumbnail" :alt="project.title"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            </div>
                            <div class="p-4 sm:p-6">
                                <h3
                                    class="font-display font-semibold text-base sm:text-xl mb-2 group-hover:text-copper transition-colors">
                                    {{ project.title }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                    {{ project.description }}
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="tech in project.technologies?.slice(0, 3)" :key="tech.id"
                                        class="text-xs px-3 py-1 rounded-full bg-copper/10 text-copper border border-copper/20">
                                        {{ tech.name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-8 sm:mt-12">
                    <router-link to="/portfolio" class="btn-outline text-sm sm:text-base">
                        View All Projects
                    </router-link>
                </div>
            </div>
        </section>

        <!-- Skills -->
        <section class="section bg-gray-50 dark:bg-graphite">
            <div class="container-custom">
                <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-12">
                    <h2 class="text-responsive-section font-display font-bold mb-4">
                        Tech <span class="text-gradient">Stack</span>
                    </h2>
                    <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400">
                        Technologies I use to bring ideas to life
                    </p>
                </div>

                <LoadingSpinner v-if="loadingSkills" />

                <div v-else-if="skills.length > 0"
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <div v-for="skill in skills" :key="skill.id" class="card-hover p-4 sm:p-6 text-center">
                        <div class="text-4xl mb-3">{{ skill.icon || '⚡' }}</div>
                        <div class="font-semibold text-sm mb-2">{{ skill.name }}</div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                            <div class="h-full rounded-full bg-gradient-to-r from-copper to-electric transition-all duration-1000"
                                :style="{ width: (skill.proficiency_level || 0) + '%' }"></div>
                        </div>
                        <p class="text-xs font-mono text-gray-500 mt-1">{{ skill.proficiency_level || 0 }}%</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services -->
        <section class="section">
            <div class="container-custom">
                <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-12">
                    <h2 class="text-responsive-section font-display font-bold mb-4">
                        What I <span class="text-gradient">Offer</span>
                    </h2>
                    <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400">
                        Comprehensive solutions for your digital needs
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <div v-for="service in services" :key="service.title" class="card-hover p-5 sm:p-8">
                        <h3 class="font-display font-semibold text-lg sm:text-xl mb-3">{{ service.title }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm mb-4">
                            {{ service.description }}
                        </p>
                        <ul class="space-y-2">
                            <li v-for="feature in service.features" :key="feature"
                                class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 text-copper flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ feature }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Swiper -->
        <section class="section bg-gray-50 dark:bg-graphite overflow-hidden">
            <div class="container-custom">
                <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-12">
                    <h2 class="text-responsive-section font-display font-bold mb-4">
                        Client <span class="text-gradient">Reviews</span>
                    </h2>
                    <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400">
                        Trusted by clients worldwide
                    </p>
                </div>

                <LoadingSpinner v-if="loadingTestimonials" />

                <div v-else-if="testimonials.length > 0" class="relative px-8 sm:px-0">
                    <!-- Swiper Container -->
                    <div class="overflow-hidden" ref="swiperContainer">
                        <div class="flex transition-transform duration-500 ease-in-out"
                            :style="{ transform: `translateX(-${(currentSlide / slidesPerView) * 100}%)` }">
                            <div v-for="testimonial in testimonials" :key="testimonial.id"
                                class="min-w-full md:min-w-[50%] lg:min-w-[33.333%] px-2 sm:px-3">
                                <div class="card-hover p-5 sm:p-8 h-full flex flex-col">
                                    <!-- Stars -->
                                    <div class="flex gap-1 mb-4">
                                        <span v-for="i in 5" :key="i"
                                            class="text-yellow-400 text-base sm:text-lg">★</span>
                                    </div>
                                    <!-- Content -->
                                    <p
                                        class="text-gray-600 dark:text-gray-400 italic leading-relaxed flex-1 mb-6 text-sm sm:text-base">
                                        "{{ testimonial.content }}"
                                    </p>
                                    <!-- Client -->
                                    <div
                                        class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <div
                                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-copper to-electric flex items-center justify-center text-white font-bold text-base sm:text-lg flex-shrink-0">
                                            {{ testimonial.client_name.charAt(0) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-sm sm:text-base">{{ testimonial.client_name
                                                }}</div>
                                            <div class="text-xs sm:text-sm text-copper">{{ testimonial.client_position
                                                }}, {{ testimonial.client_company }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <button @click="prevSlide"
                        class="absolute -left-1 sm:-left-3 top-1/2 -translate-y-1/2 sm:-translate-x-3 w-8 h-8 sm:w-12 sm:h-12 rounded-full bg-white dark:bg-graphite border border-gray-200 dark:border-gray-700 shadow-lg flex items-center justify-center hover:bg-copper hover:text-white hover:border-copper transition-all z-10">
                        <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button @click="nextSlide"
                        class="absolute -right-1 sm:-right-3 top-1/2 -translate-y-1/2 sm:translate-x-3 w-8 h-8 sm:w-12 sm:h-12 rounded-full bg-white dark:bg-graphite border border-gray-200 dark:border-gray-700 shadow-lg flex items-center justify-center hover:bg-copper hover:text-white hover:border-copper transition-all z-10">
                        <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Dots -->
                    <div class="flex justify-center gap-2 mt-6 sm:mt-8">
                        <button v-for="(_, i) in Math.ceil(testimonials.length / slidesPerView)" :key="i"
                            @click="goToSlide(i)" :class="[
                                'w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full transition-all duration-300',
                                Math.floor(currentSlide / slidesPerView) === i ? 'bg-copper w-5 sm:w-6' : 'bg-gray-300 dark:bg-gray-600 hover:bg-copper/50'
                            ]"></button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Latest Blog -->
        <section class="section">
            <div class="container-custom">
                <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-12">
                    <h2 class="text-responsive-section font-display font-bold mb-4">
                        Latest <span class="text-gradient">Insights</span>
                    </h2>
                    <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400">
                        Thoughts, tutorials, and industry insights
                    </p>
                </div>

                <LoadingSpinner v-if="loadingBlogs" />

                <div v-else-if="latestBlogs.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                    <div v-for="blog in latestBlogs" :key="blog.id" @click="navigateToBlog(blog.slug)"
                        class="group cursor-pointer">
                        <div class="card-hover overflow-hidden">
                            <div
                                class="aspect-video relative overflow-hidden rounded-t-2xl bg-gray-100 dark:bg-gray-800">
                                <img v-if="blog.featured_image" :src="blog.featured_image" :alt="blog.title"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            </div>
                            <div class="p-4 sm:p-6">
                                <div class="flex items-center gap-2 mb-3 text-xs">
                                    <span class="px-2 py-1 rounded bg-electric/10 text-electric">{{ blog.category?.name
                                        }}</span>
                                    <span class="text-gray-500">{{ formatDate(blog.published_at) }}</span>
                                </div>
                                <h3
                                    class="font-display font-semibold text-base sm:text-lg mb-2 group-hover:text-copper transition-colors line-clamp-2">
                                    {{ blog.title }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2">
                                    {{ blog.excerpt }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-8 sm:mt-12">
                    <router-link to="/blog" class="btn-outline text-sm sm:text-base">
                        Read More Articles
                    </router-link>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="section bg-gradient-to-br from-copper/5 via-transparent to-electric/5 relative overflow-hidden">
            <div class="absolute inset-0 grid-bg opacity-20"></div>
            <div class="container-custom relative z-10">
                <div class="glass-card p-6 sm:p-8 md:p-16 text-center border-2 border-copper/20">
                    <h2 class="text-responsive-section font-display font-bold mb-6">
                        Let's Build Something <span class="text-gradient">Amazing</span>
                    </h2>
                    <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-6 sm:mb-8">
                        Have a project in mind? Let's discuss how we can work together to bring your vision to life.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                        <router-link to="/contact"
                            class="btn-primary text-sm sm:text-lg px-6 sm:px-8 py-3 sm:py-4 text-center">
                            Start a Project
                        </router-link>
                        <a href="#" class="btn-outline text-sm sm:text-lg px-6 sm:px-8 py-3 sm:py-4 text-center">
                            Download Resume
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
