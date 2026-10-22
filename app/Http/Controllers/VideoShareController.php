<?php

namespace App\Http\Controllers;

use App\Models\VideoShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoShareController extends Controller
{
    /**
     * Get share count for a video
     */
    public function show(Request $request, string $videoId)
    {
        $totalShares = VideoShare::getTotalShares($videoId);
        
        return response()->json([
            'success' => true,
            'total_shares' => $totalShares,
        ]);
    }

    /**
     * Track a share for a video
     */
    public function store(Request $request)
    {
        $request->validate([
            'video_id' => 'required|string',
            'video_url' => 'required|string',
            'product_id' => 'nullable|integer',
        ]);

        $videoId = $request->input('video_id');
        $videoUrl = $request->input('video_url');
        $productId = $request->input('product_id');
        $userId = Auth::id();
        $sessionId = $request->session()->getId();
        $ipAddress = $request->ip();

        $success = VideoShare::addShare(
            $videoId,
            $videoUrl,
            $productId,
            $userId,
            $sessionId,
            $ipAddress
        );

        if ($success) {
            $totalShares = VideoShare::getTotalShares($videoId);
            
            return response()->json([
                'success' => true,
                'message' => 'Share tracked successfully',
                'total_shares' => $totalShares,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to track share',
        ], 500);
    }
}
