<?php

namespace App\Traits;

trait Timeable
{
    /**
     * Format time remaining in minutes and seconds
     */
    public function formatTimeRemaining($seconds): string
    {
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;
        
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Check if time has expired
     */
    public function isTimeExpired($startedAt, $timeLimit): bool
    {
        if (!$timeLimit || !$startedAt) {
            return false;
        }

        $elapsed = now()->diffInMinutes($startedAt);
        return $elapsed >= $timeLimit;
    }

    /**
     * Get remaining time in seconds
     */
    public function getRemainingTime($startedAt, $timeLimit): int
    {
        if (!$timeLimit || !$startedAt) {
            return 0;
        }

        $totalSeconds = $timeLimit * 60;
        $elapsed = now()->diffInSeconds($startedAt);
        $remaining = $totalSeconds - $elapsed;

        return max(0, $remaining);
    }

    /**
     * Calculate time taken in seconds
     */
    public function calculateTimeTaken($startedAt, $completedAt = null): int
    {
        if (!$startedAt) {
            return 0;
        }

        $endTime = $completedAt ?? now();
        return $startedAt->diffInSeconds($endTime);
    }
}
