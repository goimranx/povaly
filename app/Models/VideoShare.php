<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class VideoShare extends Model
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
     * Get total shares for a video
     */
    public static function getTotalShares(string $videoId): int
    {
        return self::where('video_id', $videoId)->count();
    }

    /**
     * Track a share
     */
    public static function addShare(string $videoId, string $videoUrl, ?int $productId, ?int $userId, ?string $sessionId, ?string $ipAddress): bool
    {
        try {
            self::create([
                'video_id' => $videoId,
                'video_url' => $videoUrl,
                'product_id' => $productId,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'ip_address' => $ipAddress,
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to track video share: ' . $e->getMessage());
            return false;
        }
    }
}
