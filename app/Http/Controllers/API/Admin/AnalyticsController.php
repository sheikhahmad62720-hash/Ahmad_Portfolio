<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Models\Visitor;
use App\Models\PageView;
use App\Models\Project;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends BaseController
{
    public function visitors(): JsonResponse
    {
        $data = [
            'total' => Visitor::count(),
            'today' => Visitor::whereDate('visited_at', today())->count(),
            'yesterday' => Visitor::whereDate('visited_at', today()->subDay())->count(),
            'this_week' => Visitor::whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Visitor::whereMonth('visited_at', now()->month)->count(),
            'chart' => $this->getVisitorChart(30),
        ];

        return $this->sendResponse($data, 'Visitor analytics retrieved successfully');
    }

    public function pageViews(): JsonResponse
    {
        $data = [
            'total' => PageView::count(),
            'today' => PageView::whereDate('viewed_at', today())->count(),
            'top_pages' => PageView::select('url', 'page_title', DB::raw('count(*) as views'))
                ->groupBy('url', 'page_title')
                ->orderBy('views', 'desc')
                ->limit(10)
                ->get(),
            'chart' => $this->getPageViewChart(30),
        ];

        return $this->sendResponse($data, 'Page view analytics retrieved successfully');
    }

    public function geography(): JsonResponse
    {
        $countries = Visitor::select('country', DB::raw('count(*) as count'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $cities = Visitor::select('city', 'country', DB::raw('count(*) as count'))
            ->whereNotNull('city')
            ->groupBy('city', 'country')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return $this->sendResponse([
            'countries' => $countries,
            'cities' => $cities,
        ], 'Geographic analytics retrieved successfully');
    }

    public function devices(): JsonResponse
    {
        $data = Visitor::select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->get()
            ->map(function ($item) {
                return [
                    'device' => $item->device_type ?? 'Unknown',
                    'count' => $item->count,
                    'percentage' => round(($item->count / Visitor::count()) * 100, 2),
                ];
            });

        return $this->sendResponse($data, 'Device analytics retrieved successfully');
    }

    public function browsers(): JsonResponse
    {
        $data = Visitor::select('browser', DB::raw('count(*) as count'))
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return $this->sendResponse($data, 'Browser analytics retrieved successfully');
    }

    public function content(): JsonResponse
    {
        $data = [
            'projects' => [
                'total' => Project::count(),
                'published' => Project::where('is_published', true)->count(),
                'featured' => Project::where('is_featured', true)->count(),
                'most_viewed' => Project::orderBy('views_count', 'desc')->limit(5)->get(['id', 'title', 'views_count']),
            ],
            'blogs' => [
                'total' => Blog::count(),
                'published' => Blog::where('status', 'published')->count(),
                'draft' => Blog::where('status', 'draft')->count(),
                'most_viewed' => Blog::orderBy('views_count', 'desc')->limit(5)->get(['id', 'title', 'views_count']),
            ],
        ];

        return $this->sendResponse($data, 'Content analytics retrieved successfully');
    }

    private function getVisitorChart(int $days): array
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Visitor::whereDate('visited_at', $date)->count();
            $data[] = [
                'date' => now()->subDays($i)->format('M d'),
                'count' => $count,
            ];
        }
        return $data;
    }

    private function getPageViewChart(int $days): array
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = PageView::whereDate('viewed_at', $date)->count();
            $data[] = [
                'date' => now()->subDays($i)->format('M d'),
                'count' => $count,
            ];
        }
        return $data;
    }
}
