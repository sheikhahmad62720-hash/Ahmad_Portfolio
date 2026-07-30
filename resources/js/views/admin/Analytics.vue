<script setup lang="ts">
import { ref, onMounted, nextTick } from 'vue';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const loading = ref(true);

const visitors = ref<any>({ total: 0, today: 0, chart: [] });
const pageViews = ref<any>({ total: 0, today: 0, top_pages: [], chart: [] });
const geography = ref<any>({ countries: [], cities: [] });
const devices = ref<any[]>([]);
const browsers = ref<any[]>([]);
const content = ref<any>({ projects: {}, blogs: {} });

const visitorChartCanvas = ref<HTMLCanvasElement | null>(null);
const pageViewChartCanvas = ref<HTMLCanvasElement | null>(null);

let visitorChart: Chart | null = null;
let pageViewChart: Chart | null = null;

function getCountryPercent(count: number) {
  const max = Math.max(...geography.value.countries.map((c: any) => c.count), 1);
  return (count / max) * 100;
}

function createChart(canvas: HTMLCanvasElement, labels: string[], data: number[], color: string) {
  return new Chart(canvas, {
    type: 'line',
    data: {
      labels,
      datasets: [{
        label: 'Views',
        data,
        borderColor: color,
        backgroundColor: color + '20',
        fill: true,
        tension: 0.4,
        pointRadius: 3,
        borderWidth: 2,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } },
        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
      },
    },
  });
}

async function fetchAnalytics() {
  loading.value = true;
  try {
    const [visitorsRes, pageViewsRes, geographyRes, devicesRes, browsersRes, contentRes] = await Promise.all([
      api.get('/admin/analytics/visitors'),
      api.get('/admin/analytics/page-views'),
      api.get('/admin/analytics/geography'),
      api.get('/admin/analytics/devices'),
      api.get('/admin/analytics/browsers'),
      api.get('/admin/analytics/content'),
    ]);

    if (visitorsRes.data.success) visitors.value = visitorsRes.data.data;
    if (pageViewsRes.data.success) pageViews.value = pageViewsRes.data.data;
    if (geographyRes.data.success) geography.value = geographyRes.data.data;
    if (devicesRes.data.success) devices.value = devicesRes.data.data;
    if (browsersRes.data.success) browsers.value = browsersRes.data.data;
    if (contentRes.data.success) content.value = contentRes.data.data;
  } catch (error) {
    console.error('Error fetching analytics:', error);
  } finally {
    loading.value = false;
  }
}

onMounted(async () => {
  await fetchAnalytics();
  await nextTick();

  if (visitorChartCanvas.value && visitors.value.chart?.length) {
    const labels = visitors.value.chart.map((d: any) => d.date);
    const data = visitors.value.chart.map((d: any) => d.count);
    visitorChart = createChart(visitorChartCanvas.value, labels, data, '#d97706');
  }

  if (pageViewChartCanvas.value && pageViews.value.chart?.length) {
    const labels = pageViews.value.chart.map((d: any) => d.date);
    const data = pageViews.value.chart.map((d: any) => d.count);
    pageViewChart = createChart(pageViewChartCanvas.value, labels, data, '#06b6d4');
  }
});
</script>

<template>
  <div class="space-y-8">
    <!-- Header -->
    <div>
      <h1 class="text-3xl sm:text-4xl font-display font-bold text-gradient mb-2">Analytics</h1>
      <p class="text-gray-600 dark:text-gray-400">Track your portfolio performance</p>
    </div>

    <LoadingSpinner v-if="loading" />

    <template v-else>
      <!-- Overview Stats -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card-hover p-6 bg-gradient-to-br from-copper/10 to-copper/5 border-copper/20">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-copper/20 flex items-center justify-center text-2xl">👥</div>
          </div>
          <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Total Visitors</h3>
          <p class="text-3xl font-bold text-copper">{{ visitors.total }}</p>
          <p class="text-xs text-gray-500 mt-2">{{ visitors.today }} today</p>
        </div>

        <div class="card-hover p-6 bg-gradient-to-br from-electric/10 to-electric/5 border-electric/20">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-electric/20 flex items-center justify-center text-2xl">👁️</div>
          </div>
          <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Page Views</h3>
          <p class="text-3xl font-bold text-electric">{{ pageViews.total }}</p>
          <p class="text-xs text-gray-500 mt-2">{{ pageViews.today }} today</p>
        </div>

        <div class="card-hover p-6 bg-gradient-to-br from-purple-500/10 to-purple-500/5 border-purple-500/20">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center text-2xl">📄</div>
          </div>
          <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Total Projects</h3>
          <p class="text-3xl font-bold text-purple-600">{{ content.projects?.total || 0 }}</p>
          <p class="text-xs text-gray-500 mt-2">{{ content.projects?.published || 0 }} published</p>
        </div>

        <div class="card-hover p-6 bg-gradient-to-br from-green-500/10 to-green-500/5 border-green-500/20">
          <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center text-2xl">📝</div>
          </div>
          <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Blog Posts</h3>
          <p class="text-3xl font-bold text-green-600">{{ content.blogs?.total || 0 }}</p>
          <p class="text-xs text-gray-500 mt-2">{{ content.blogs?.published || 0 }} published</p>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card-hover p-6">
          <h3 class="text-xl font-display font-bold mb-4">Visitors (30 Days)</h3>
          <div class="h-64">
            <canvas ref="visitorChartCanvas"></canvas>
          </div>
        </div>

        <div class="card-hover p-6">
          <h3 class="text-xl font-display font-bold mb-4">Page Views (30 Days)</h3>
          <div class="h-64">
            <canvas ref="pageViewChartCanvas"></canvas>
          </div>
        </div>
      </div>

      <!-- Geography & Devices -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card-hover p-6">
          <h3 class="text-xl font-display font-bold mb-4">Top Countries</h3>
          <div v-if="geography.countries?.length" class="space-y-4">
            <div v-for="country in geography.countries" :key="country.country" class="flex items-center justify-between">
              <span class="text-sm font-medium">{{ country.country || 'Unknown' }}</span>
              <div class="flex items-center gap-3">
                <div class="w-24 h-2 rounded-full bg-gray-200 dark:bg-gray-700">
                  <div class="h-full rounded-full bg-gradient-to-r from-copper to-electric" :style="{ width: getCountryPercent(country.count) + '%' }"></div>
                </div>
                <span class="text-sm text-gray-500 w-8 text-right">{{ country.count }}</span>
              </div>
            </div>
          </div>
          <p v-else class="text-gray-500 text-sm">No data yet</p>
        </div>

        <div class="card-hover p-6">
          <h3 class="text-xl font-display font-bold mb-4">Devices</h3>
          <div v-if="devices.length" class="space-y-4">
            <div v-for="device in devices" :key="device.device" class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="text-xl">{{ device.device === 'mobile' ? '📱' : device.device === 'tablet' ? '📟' : '💻' }}</span>
                <span class="text-sm font-medium capitalize">{{ device.device }}</span>
              </div>
              <div class="flex items-center gap-3">
                <div class="w-20 h-2 rounded-full bg-gray-200 dark:bg-gray-700">
                  <div class="h-full rounded-full bg-gradient-to-r from-copper to-electric" :style="{ width: device.percentage + '%' }"></div>
                </div>
                <span class="text-sm text-gray-500">{{ device.percentage }}%</span>
              </div>
            </div>
          </div>
          <p v-else class="text-gray-500 text-sm">No data yet</p>
        </div>

        <div class="card-hover p-6">
          <h3 class="text-xl font-display font-bold mb-4">Browsers</h3>
          <div v-if="browsers.length" class="space-y-4">
            <div v-for="browser in browsers" :key="browser.browser" class="flex items-center justify-between">
              <span class="text-sm font-medium">{{ browser.browser }}</span>
              <span class="text-sm text-gray-500">{{ browser.count }}</span>
            </div>
          </div>
          <p v-else class="text-gray-500 text-sm">No data yet</p>
        </div>
      </div>

      <!-- Top Pages & Most Viewed -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card-hover p-6">
          <h3 class="text-xl font-display font-bold mb-4">Top Pages</h3>
          <div v-if="pageViews.top_pages?.length" class="space-y-3">
            <div v-for="(page, i) in pageViews.top_pages" :key="page.url" class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-800 last:border-0">
              <div class="flex items-center gap-3 min-w-0">
                <span class="text-xs font-mono text-copper w-5">{{ i + 1 }}.</span>
                <div class="min-w-0">
                  <p class="text-sm font-medium truncate">{{ page.page_title || page.url }}</p>
                  <p class="text-xs text-gray-500 truncate">{{ page.url }}</p>
                </div>
              </div>
              <span class="text-sm font-mono text-gray-500 ml-4">{{ page.views }}</span>
            </div>
          </div>
          <p v-else class="text-gray-500 text-sm">No data yet</p>
        </div>

        <div class="card-hover p-6">
          <h3 class="text-xl font-display font-bold mb-4">Most Viewed Content</h3>
          <div class="space-y-6">
            <div>
              <h4 class="text-sm font-semibold text-copper mb-3">Projects</h4>
              <div v-if="content.projects?.most_viewed?.length" class="space-y-2">
                <div v-for="p in content.projects.most_viewed" :key="p.id" class="flex items-center justify-between py-1">
                  <span class="text-sm truncate mr-4">{{ p.title }}</span>
                  <span class="text-xs font-mono text-gray-500">{{ p.views_count }} views</span>
                </div>
              </div>
              <p v-else class="text-xs text-gray-500">No projects</p>
            </div>
            <div>
              <h4 class="text-sm font-semibold text-electric mb-3">Blogs</h4>
              <div v-if="content.blogs?.most_viewed?.length" class="space-y-2">
                <div v-for="b in content.blogs.most_viewed" :key="b.id" class="flex items-center justify-between py-1">
                  <span class="text-sm truncate mr-4">{{ b.title }}</span>
                  <span class="text-xs font-mono text-gray-500">{{ b.views_count }} views</span>
                </div>
              </div>
              <p v-else class="text-xs text-gray-500">No blogs</p>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>
