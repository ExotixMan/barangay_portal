<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

class UploadedImageComparator
{
    /**
     * Reject exact duplicate uploads and, when GD is available, very close
     * image duplicates that were re-encoded or captured a moment apart.
     * The perceptual check is intentionally conservative to avoid rejecting
     * legitimate front/back sides that share the same card design.
     */
    public static function areSameOrNearDuplicate(?UploadedFile $front, ?UploadedFile $back): bool
    {
        if (! $front || ! $back || ! $front->isValid() || ! $back->isValid()) {
            return false;
        }

        $frontPath = $front->getRealPath();
        $backPath = $back->getRealPath();

        if (! $frontPath || ! $backPath || ! is_file($frontPath) || ! is_file($backPath)) {
            return false;
        }

        if (hash_file('sha256', $frontPath) === hash_file('sha256', $backPath)) {
            return true;
        }

        if (! str_starts_with((string) $front->getMimeType(), 'image/')
            || ! str_starts_with((string) $back->getMimeType(), 'image/')) {
            return false;
        }

        if (! function_exists('imagecreatefromstring')) {
            return false;
        }

        $frontBytes = @file_get_contents($frontPath);
        $backBytes = @file_get_contents($backPath);

        if ($frontBytes === false || $backBytes === false) {
            return false;
        }

        $frontImage = @imagecreatefromstring($frontBytes);
        $backImage = @imagecreatefromstring($backBytes);

        if (! $frontImage || ! $backImage) {
            if ($frontImage) {
                imagedestroy($frontImage);
            }
            if ($backImage) {
                imagedestroy($backImage);
            }

            return false;
        }

        try {
            $frontSignature = self::signature($frontImage);
            $backSignature = self::signature($backImage);

            if (! $frontSignature || ! $backSignature) {
                return false;
            }

            $hamming = 0;
            $absoluteDifference = 0.0;
            $count = count($frontSignature['bits']);

            for ($i = 0; $i < $count; $i++) {
                if ($frontSignature['bits'][$i] !== $backSignature['bits'][$i]) {
                    $hamming++;
                }

                $absoluteDifference += abs(
                    $frontSignature['normalized'][$i] - $backSignature['normalized'][$i]
                );
            }

            $meanAbsoluteDifference = $count > 0 ? $absoluteDifference / $count : 1.0;

            // 256-bit perceptual signature. Both checks must agree before the
            // server rejects the pair as a near-duplicate.
            return $hamming <= 14 && $meanAbsoluteDifference <= 0.055;
        } finally {
            imagedestroy($frontImage);
            imagedestroy($backImage);
        }
    }

    private static function signature($image): ?array
    {
        $size = 16;
        $sample = imagecreatetruecolor($size, $size);

        if (! $sample) {
            return null;
        }

        try {
            imagecopyresampled(
                $sample,
                $image,
                0,
                0,
                0,
                0,
                $size,
                $size,
                imagesx($image),
                imagesy($image)
            );

            $values = [];
            $sum = 0.0;

            for ($y = 0; $y < $size; $y++) {
                for ($x = 0; $x < $size; $x++) {
                    $rgb = imagecolorat($sample, $x, $y);
                    $r = ($rgb >> 16) & 0xFF;
                    $g = ($rgb >> 8) & 0xFF;
                    $b = $rgb & 0xFF;
                    $gray = (($r * 0.299) + ($g * 0.587) + ($b * 0.114)) / 255;
                    $values[] = $gray;
                    $sum += $gray;
                }
            }

            $count = count($values);
            if ($count === 0) {
                return null;
            }

            $mean = $sum / $count;
            $variance = 0.0;

            foreach ($values as $value) {
                $variance += ($value - $mean) ** 2;
            }

            $stdDev = sqrt(max($variance / $count, 0.000001));
            $bits = [];
            $normalized = [];

            foreach ($values as $value) {
                $bits[] = $value >= $mean ? 1 : 0;
                $normalized[] = max(-1.0, min(1.0, ($value - $mean) / (3 * $stdDev)));
            }

            return compact('bits', 'normalized');
        } finally {
            imagedestroy($sample);
        }
    }
}
