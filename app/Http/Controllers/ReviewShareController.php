<?php

namespace App\Http\Controllers;

use App\Models\ReviewShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewShareController extends Controller
{
    /**
     * Get share status and count for a review
     */
    public function show(Request $request, int $reviewId)
    {
        $userId = Auth::id();
        $sessionId = $request->session()->getId();
        
        $userShared = ReviewShare::hasUserShared($reviewId, $userId, $sessionId);
        $totalShares = ReviewShare::getTotalShares($reviewId);
        
        return response()->json([
            'success' => true,
            'user_shared' => $userShared,
            'total_shares' => $totalShares,
        ]);
    }

    /**
     * Track a share for a review
     */
    public function store(Request $request)
    {
        $request->validate([
            'review_id' => 'required|integer',
        ]);

        $reviewId = $request->input('review_id');
        $userId = Auth::id();
        $sessionId = $request->session()->getId();
        $ipAddress = $request->ip();

        $success = ReviewShare::addShare(
            $reviewId,
            $userId,
            $sessionId,
            $ipAddress
        );

        if ($success) {
            $totalShares = ReviewShare::getTotalShares($reviewId);
            
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
