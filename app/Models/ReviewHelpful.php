<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ReviewHelpful extends Model
{
    protected $table = 'review_helpful';
    
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
     * Get total helpful marks for a review
     */
    public static function getTotalMarks(int $reviewId): int
    {
        return self::where('review_id', $reviewId)->count();
    }

    /**
     * Check if user/session has marked a review as helpful
     */
    public static function hasUserMarked(int $reviewId, ?int $userId, ?string $sessionId): bool
    {
        $query = self::where('review_id', $reviewId);
        
        if ($userId) {
            // For logged-in users, check ONLY by user_id
            $query->where('user_id', $userId);
        } else {
            // For guests, check by session_id only
            $query->where('session_id', $sessionId)
                  ->whereNull('user_id');
        }
        
        $exists = $query->exists();
        
        \Log::info('ReviewHelpful::hasUserMarked DEBUG', [
            'review_id' => $reviewId,
            'user_id' => $userId,
            'session_id' => $sessionId,
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'result' => $exists,
            'actual_records' => self::where('review_id', $reviewId)->get()->toArray()
        ]);
        
        return $exists;
    }

    /**
     * Add a helpful mark
     */
    public static function addMark(int $reviewId, ?int $userId, ?string $sessionId, ?string $ipAddress): bool
    {
        try {
            self::updateOrCreate(
                [
                    'review_id' => $reviewId,
                    'user_id' => $userId,
                    'session_id' => $userId ? null : $sessionId,
                ],
                [
                    'ip_address' => $ipAddress,
                ]
            );
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to add review helpful mark: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove a helpful mark
     */
    public static function removeMark(int $reviewId, ?int $userId, ?string $sessionId): bool
    {
        try {
            $query = self::where('review_id', $reviewId);
            
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
            
            $query->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to remove review helpful mark: ' . $e->getMessage());
            return false;
        }
    }
}
