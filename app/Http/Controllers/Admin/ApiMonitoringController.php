<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
     * Test endpoint API secara internal (tanpa HTTP request keluar).
     * Menggunakan Laravel internal dispatcher agar tidak deadlock
     * pada single-threaded php artisan serve.
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

            // Buat internal request dengan format JSON body yang benar
            $server = [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT'  => 'application/json',
            ];
            $internalRequest = Request::create($path, $method, [], [], [], $server, json_encode($body));

            // Copy session & auth dari request admin yang sedang login
            $internalRequest->setLaravelSession($request->session());

            // Untuk endpoint yang butuh Sanctum auth, buat token sementara

            // Simpan session admin saat ini agar tidak ter-overwrite oleh endpoint login/register
            $originalUser = auth()->user();

            // Dispatch request secara internal melalui Laravel router
            $response = app()->handle($internalRequest);

            // Kembalikan session admin
            if ($originalUser) {
                auth()->login($originalUser);
            }

            $endTime = microtime(true);
            $duration = round(($endTime - $startTime) * 1000);

            // Parse response body
            $responseBody = json_decode($response->getContent(), true);
            if ($responseBody === null) {
                $responseBody = $response->getContent();
            }

            // Hapus token sementara setelah test
            if ($user) {
                $user->tokens()->where('name', 'api-monitoring-test')->delete();
            }

            return response()->json([
                'success'       => true,
                'status_code'   => $response->getStatusCode(),
                'response_time' => $duration,
                'response_body' => $responseBody,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success'       => false,
                'status_code'   => 500,
                'response_time' => 0,
                'response_body' => ['error' => $e->getMessage()],
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
