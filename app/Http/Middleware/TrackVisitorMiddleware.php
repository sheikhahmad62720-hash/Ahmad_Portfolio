<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Jenssegers\Agent\Agent;

class TrackVisitorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $ipAddress = $request->ip();
            if (!$ipAddress || $ipAddress === '127.0.0.1' || $ipAddress === '::1') {
                return $next($request);
            }

            // Avoid duplicate visits within 30 minutes
            $recent = Visitor::where('ip_address', $ipAddress)
                ->where('visited_at', '>=', now()->subMinutes(30))
                ->latest('visited_at')
                ->first();

            if ($recent) {
                PageView::create([
                    'visitor_id' => $recent->id,
                    'url' => $request->fullUrl(),
                    'page_title' => $request->header('x-page-title'),
                    'viewed_at' => now(),
                ]);
                return $next($request);
            }

            $agent = new Agent();

            $geo = $this->getGeoLocation($ipAddress);

            $visitor = Visitor::create([
                'ip_address' => $ipAddress,
                'user_agent' => $request->userAgent(),
                'device_type' => $agent->isMobile() ? 'mobile' : ($agent->isTablet() ? 'tablet' : 'desktop'),
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'os' => $agent->platform(),
                'os_version' => $agent->version($agent->platform()),
                'country' => $geo['country'] ?? null,
                'city' => $geo['city'] ?? null,
                'referrer' => $request->header('referer'),
                'visited_at' => now(),
            ]);

            PageView::create([
                'visitor_id' => $visitor->id,
                'url' => $request->fullUrl(),
                'page_title' => $request->header('x-page-title'),
                'viewed_at' => now(),
            ]);
        } catch (\Exception $e) {
            logger()->error('Visitor tracking failed: ' . $e->getMessage());
        }

        return $next($request);
    }

    private function getGeoLocation(string $ip): array
    {
        try {
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=country,city");
            if ($response) {
                $data = json_decode($response, true);
                if ($data && ($data['country'] ?? null)) {
                    return ['country' => $data['country'], 'city' => $data['city'] ?? null];
                }
            }
        } catch (\Exception $e) {
            // Silently fallback
        }
        return ['country' => null, 'city' => null];
    }
}
