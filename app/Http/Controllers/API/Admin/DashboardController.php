<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Models\Project;
use App\Models\Blog;
use App\Models\Message;
use App\Models\Visitor;
use App\Models\PageView;
use App\Models\Newsletter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{
    /**
     * Get dashboard statistics
     */
    public function index(): JsonResponse
    {
        $data = [
            'stats' => [
                'projects' => [
                    'total' => Project::count(),
                    'published' => Project::where('is_published', true)->count(),
                    'featured' => Project::where('is_featured', true)->count(),
                    'this_month' => Project::whereMonth('created_at', now()->month)->count(),
                ],
                'blogs' => [
                    'total' => Blog::count(),
                    'published' => Blog::where('status', 'published')->count(),
                    'draft' => Blog::where('status', 'draft')->count(),
                    'this_month' => Blog::whereMonth('created_at', now()->month)->count(),
                ],
                'messages' => [
                    'total' => Message::count(),
                    'unread' => Message::where('status', 'unread')->count(),
                    'today' => Message::whereDate('created_at', today())->count(),
                ],
                'visitors' => [
                    'total' => Visitor::count(),
                    'today' => Visitor::whereDate('visited_at', today())->count(),
                    'this_week' => Visitor::whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                    'this_month' => Visitor::whereMonth('visited_at', now()->month)->count(),
                ],
                'page_views' => [
                    'total' => PageView::count(),
                    'today' => PageView::whereDate('viewed_at', today())->count(),
                ],
                'newsletter' => [
                    'subscribers' => Newsletter::where('status', 'subscribed')->count(),
                ],
            ],
            'recent_projects' => Project::latest()
                ->limit(5)
                ->get(),
            'recent_blogs' => Blog::with('user:id,name')
                ->latest()
                ->limit(5)
                ->get(),
            'recent_messages' => Message::latest()
                ->limit(5)
                ->get(),
            'visitor_chart' => $this->getVisitorChartData(),
            'page_view_chart' => $this->getPageViewChartData(),
            'top_pages' => $this->getTopPages(),
            'device_stats' => $this->getDeviceStats(),
            'browser_stats' => $this->getBrowserStats(),
            'country_stats' => $this->getCountryStats(),
        ];

        return $this->sendResponse($data, 'Dashboard data retrieved successfully');
    }

    /**
     * Get visitor chart data (last 7 days)
     */
    private function getVisitorChartData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Visitor::whereDate('visited_at', $date)->count();
            $data[] = [
                'date' => now()->subDays($i)->format('M d'),
                'count' => $count,
            ];
        }
        return $data;
    }

    /**
     * Get page view chart data (last 7 days)
     */
    private function getPageViewChartData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = PageView::whereDate('viewed_at', $date)->count();
            $data[] = [
                'date' => now()->subDays($i)->format('M d'),
                'count' => $count,
            ];
        }
        return $data;
    }

    /**
     * Get top pages by views
     */
    private function getTopPages(): array
    {
        return PageView::select('url', 'page_title', DB::raw('count(*) as views'))
            ->groupBy('url', 'page_title')
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Get device statistics
     */
    private function getDeviceStats(): array
    {
        return Visitor::select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->orderBy('count', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Get browser statistics
     */
    private function getBrowserStats(): array
    {
        return Visitor::select('browser', DB::raw('count(*) as count'))
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    /**
     * Get country statistics
     */
    private function getCountryStats(): array
    {
        return Visitor::select('country', DB::raw('count(*) as count'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }
}
