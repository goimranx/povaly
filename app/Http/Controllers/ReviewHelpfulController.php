<?php

namespace App\Http\Controllers;

use App\Models\ReviewHelpful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewHelpfulController extends Controller
{
    /**
     * Get helpful status and count for a review
     */
    public function show(Request $request, int $reviewId)
    {
        $userId = auth('customer')->id();
        $sessionId = $request->session()->getId();
        
        $userMarked = ReviewHelpful::hasUserMarked($reviewId, $userId, $sessionId);
        $totalMarks = ReviewHelpful::getTotalMarks($reviewId);
        
        return response()->json([
            'success' => true,
            'user_marked' => $userMarked,
            'total_count' => $totalMarks,
        ]);
    }

    /**
     * Mark a review as helpful
     */
    public function store(Request $request)
    {
        $request->validate([
            'review_id' => 'required|integer',
        ]);

        $reviewId = $request->input('review_id');
        $userId = auth('customer')->id();
        $sessionId = $request->session()->getId();
        $ipAddress = $request->ip();

        $success = ReviewHelpful::addMark(
            $reviewId,
            $userId,
            $sessionId,
            $ipAddress
        );

        if ($success) {
            $totalMarks = ReviewHelpful::getTotalMarks($reviewId);
            $userMarked = ReviewHelpful::hasUserMarked($reviewId, $userId, $sessionId);
            
            return response()->json([
                'success' => true,
                'message' => 'Review marked as helpful',
                'total_count' => $totalMarks,
                'user_marked' => $userMarked,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to mark review as helpful',
        ], 500);
    }

    /**
     * Remove helpful mark from a review
     */
    public function destroy(Request $request, int $reviewId)
    {
        $userId = auth('customer')->id();
        $sessionId = $request->session()->getId();

        $success = ReviewHelpful::removeMark($reviewId, $userId, $sessionId);

        if ($success) {
            $totalMarks = ReviewHelpful::getTotalMarks($reviewId);
            
            return response()->json([
                'success' => true,
                'message' => 'Helpful mark removed',
                'total_count' => $totalMarks,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to remove helpful mark',
        ], 500);
    }
}
