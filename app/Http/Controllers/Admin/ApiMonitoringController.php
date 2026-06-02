<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

class ApiMonitoringController extends Controller
{
    /**
     * Tampilkan halaman API Monitoring.
     */
    public function index()
    {
        return view('admin.pages.api-monitoring');
    }

    /**
     * Test endpoint API secara internal.
     * 
     * Menggunakan Laravel internal dispatcher dengan Sanctum auth yang proper.
     * Protected endpoints mendapat Bearer token sehingga auth:sanctum middleware lolos.
     */
    public function testEndpoint(Request $request)
    {
        $validated = $request->validate([
            'method'  => 'required|string|in:GET,POST,PUT,DELETE',
            'path'    => 'required|string',
            'body'    => 'nullable|array',
        ]);

        $method = strtoupper($validated['method']);
        $path   = '/' . ltrim($validated['path'], '/');
        $body   = $validated['body'] ?? [];

        try {
            $startTime = microtime(true);

            // Simpan user admin saat ini
            $originalUser = auth()->user();

            // Buat server params
            $server = [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT'  => 'application/json',
            ];

            // Buat internal request
            $internalRequest = Request::create($path, $method, [], [], [], $server, json_encode($body));

            // Cek apakah route ini protected oleh Sanctum
            $isProtected = false;
            try {
                $route = Route::getRoutes()->match($internalRequest);
                if ($route) {
                    $middleware = $route->gatherMiddleware();
                    $isProtected = in_array('auth:sanctum', $middleware);
                }
            } catch (\Throwable $e) {
                // Fallback jika matching gagal (misal route parameter)
                $protectedPaths = ['/api/logout', '/api/favorit', '/api/user', '/api/rumah/search'];
                foreach ($protectedPaths as $p) {
                    if (str_contains($path, $p)) {
                        $isProtected = true;
                        break;
                    }
                }
            }

            // Force authentication untuk internal request menggunakan Sanctum::actingAs jika protected
            if ($isProtected && $originalUser) {
                \Laravel\Sanctum\Sanctum::actingAs($originalUser, ['*']);
            }

            // Copy session dari request admin
            if ($request->hasSession()) {
                $internalRequest->setLaravelSession($request->session());
            }

            // Dispatch request secara internal melalui Laravel kernel
            /** @var \Illuminate\Http\Response $response */
            $response = app()->handle($internalRequest);

            // Kembalikan auth admin (agar tidak ter-overwrite oleh login/register endpoint)
            if ($originalUser) {
                auth('web')->login($originalUser);
            }

            $endTime = microtime(true);
            $duration = round(($endTime - $startTime) * 1000);

            // Parse response body
            $responseBody = json_decode($response->getContent(), true);
            if ($responseBody === null) {
                $responseBody = $response->getContent();
            }

            // Hapus token sementara setelah test
            if ($originalUser && method_exists($originalUser, 'tokens')) {
                $originalUser->tokens()->where('name', 'api-monitoring-test')->delete();
            }

            return response()->json([
                'success'       => true,
                'status_code'   => $response->getStatusCode(),
                'response_time' => $duration,
                'response_body' => $responseBody,
            ]);
        } catch (\Throwable $e) {
            $endTime = microtime(true);
            $duration = round(($endTime - ($startTime ?? $endTime)) * 1000);

            // Cleanup token sementara jika ada error
            if (isset($originalUser) && $originalUser && method_exists($originalUser, 'tokens')) {
                $originalUser->tokens()->where('name', 'api-monitoring-test')->delete();
            }

            // Kembalikan auth admin
            if (isset($originalUser) && $originalUser) {
                auth('web')->login($originalUser);
            }

            return response()->json([
                'success'       => false,
                'status_code'   => 500,
                'response_time' => $duration,
                'response_body' => ['error' => $e->getMessage()],
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
