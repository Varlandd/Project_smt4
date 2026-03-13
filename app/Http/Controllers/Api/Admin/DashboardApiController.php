<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rumah;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    public function index()
    {
        $totalRumah = Rumah::count();
        $totalUser = User::count();
        $totalFavorit = DB::table('favorits')->count();
        $recentRumah = Rumah::orderBy('created_at', 'desc')->take(5)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_rumah' => $totalRumah,
                'total_user' => $totalUser,
                'total_favorit' => $totalFavorit,
                'recent_rumah' => $recentRumah,
            ]
        ]);
    }
}
