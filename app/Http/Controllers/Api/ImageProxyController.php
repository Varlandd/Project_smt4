<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ImageProxyController extends Controller
{
    /**
     * Proxy external images to bypass CORS restrictions for Flutter Web.
     * Caches images for 24 hours to reduce external requests.
     */
    public function show(Request $request)
    {
        $url = $request->query('url');

        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return response('Invalid URL', 400);
        }

        // Only allow whitelisted domains
        $allowedDomains = [
            'picture.rumah123.com',
            'img.rumah123.com',
            'rumah123.com',
        ];

        $host = parse_url($url, PHP_URL_HOST);
        if (!in_array($host, $allowedDomains)) {
            return response('Domain not allowed', 403);
        }

        // Cache key based on URL hash
        $cacheKey = 'img_proxy_' . md5($url);

        $cached = Cache::get($cacheKey);
        if ($cached) {
            return response($cached['body'])
                ->header('Content-Type', $cached['content_type'])
                ->header('Cache-Control', 'public, max-age=86400')
                ->header('Access-Control-Allow-Origin', '*');
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Referer' => 'https://www.rumah123.com/',
                ])
                ->get($url);

            if ($response->successful()) {
                $contentType = $response->header('Content-Type') ?? 'image/jpeg';
                $body = $response->body();

                // Cache for 24 hours
                Cache::put($cacheKey, [
                    'body' => $body,
                    'content_type' => $contentType,
                ], now()->addHours(24));

                return response($body)
                    ->header('Content-Type', $contentType)
                    ->header('Cache-Control', 'public, max-age=86400')
                    ->header('Access-Control-Allow-Origin', '*');
            }

            return response('Image not found', 404);
        } catch (\Exception $e) {
            return response('Failed to fetch image', 500);
        }
    }
}
