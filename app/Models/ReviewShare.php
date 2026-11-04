<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ReviewShare extends Model
{
    protected $fillable = [
        'review_id',
        'user_id',
        'session_id',
        'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get total shares for a review
     */
    public static function getTotalShares(int $reviewId): int
    {
        return self::where('review_id', $reviewId)->count();
    }

    /**
     * Check if user/session has shared a review
     */
    public static function hasUserShared(int $reviewId, ?int $userId, ?string $sessionId): bool
    {
        $query = self::where('review_id', $reviewId);
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        
        return $query->exists();
    }

    /**
     * Track a share
     */
    public static function addShare(int $reviewId, ?int $userId, ?string $sessionId, ?string $ipAddress): bool
    {
        try {
            self::create([
                'review_id' => $reviewId,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'ip_address' => $ipAddress,
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to track review share: ' . $e->getMessage());
            return false;
        }
    }
}
