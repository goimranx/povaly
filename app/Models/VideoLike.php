<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class VideoLike extends Model
{
    protected $fillable = [
        'video_id',
        'video_url',
        'product_id',
        'user_id',
        'session_id',
        'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get total likes for a video
     */
    public static function getTotalLikes(string $videoId): int
    {
        return self::where('video_id', $videoId)->count();
    }

    /**
     * Check if user/session has liked a video
     */
    public static function hasUserLiked(string $videoId, ?int $userId, ?string $sessionId): bool
    {
        $query = self::where('video_id', $videoId);
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        
        return $query->exists();
    }

    /**
     * Add a like
     */
    public static function addLike(string $videoId, string $videoUrl, ?int $productId, ?int $userId, ?string $sessionId, ?string $ipAddress): bool
    {
        try {
            self::updateOrCreate(
                [
                    'video_id' => $videoId,
                    'user_id' => $userId,
                    'session_id' => $userId ? null : $sessionId,
                ],
                [
                    'video_url' => $videoUrl,
                    'product_id' => $productId,
                    'ip_address' => $ipAddress,
                ]
            );
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to add video like: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove a like
     */
    public static function removeLike(string $videoId, ?int $userId, ?string $sessionId): bool
    {
        try {
            $query = self::where('video_id', $videoId);
            
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
            
            $query->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to remove video like: ' . $e->getMessage());
            return false;
        }
    }
}
