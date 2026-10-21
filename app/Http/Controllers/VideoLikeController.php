<?php

namespace App\Http\Controllers;

use App\Models\VideoLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoLikeController extends Controller
{
    /**
     * Get like status and count for a video
     */
    public function show(Request $request, string $videoId)
    {
        $userId = Auth::id();
        $sessionId = $request->session()->getId();
        
        $userLiked = VideoLike::hasUserLiked($videoId, $userId, $sessionId);
        $totalLikes = VideoLike::getTotalLikes($videoId);
        
        return response()->json([
            'success' => true,
            'user_liked' => $userLiked,
            'total_likes' => $totalLikes,
        ]);
    }

    /**
     * Add a like to a video
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

        $success = VideoLike::addLike(
            $videoId,
            $videoUrl,
            $productId,
            $userId,
            $sessionId,
            $ipAddress
        );

        if ($success) {
            $totalLikes = VideoLike::getTotalLikes($videoId);
            
            return response()->json([
                'success' => true,
                'message' => 'Like added successfully',
                'total_likes' => $totalLikes,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to add like',
        ], 500);
    }

    /**
     * Remove a like from a video
     */
    public function destroy(Request $request, string $videoId)
    {
        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        $success = VideoLike::removeLike($videoId, $userId, $sessionId);

        if ($success) {
            $totalLikes = VideoLike::getTotalLikes($videoId);
            
            return response()->json([
                'success' => true,
                'message' => 'Like removed successfully',
                'total_likes' => $totalLikes,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to remove like',
        ], 500);
    }
}
