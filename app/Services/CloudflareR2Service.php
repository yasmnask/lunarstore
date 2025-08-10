<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CloudflareR2Service
{
    protected $disk;

    public function __construct()
    {
        $this->disk = Storage::disk('r2');
    }

    /**
     * Upload file to Cloudflare R2
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string|false
     */
    public function uploadFile(UploadedFile $file, string $folder = 'images'): string|false
    {
        try {
            // Generate unique filename
            $filename = $this->generateUniqueFilename($file);
            $path = $folder . '/' . $filename;

            // Upload file to R2
            $uploaded = $this->disk->put($path, file_get_contents($file->getRealPath()), 'public');

            if ($uploaded) {
                return $this->getFileUrl($path);
            }

            return false;
        } catch (\Exception $e) {
            Log::error('R2 Upload Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete file from Cloudflare R2
     *
     * @param string $url
     * @return bool
     */
    public function deleteFile(string $url): bool
    {
        try {
            $path = $this->getPathFromUrl($url);

            if ($path && $this->disk->exists($path)) {
                return $this->disk->delete($path);
            }

            return false;
        } catch (\Exception $e) {
            Log::error('R2 Delete Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get file URL
     *
     * @param string $path
     * @return string
     */
    public function getFileUrl(string $path): string
    {
        $baseUrl = rtrim(config('filesystems.disks.r2.url'), '/');
        return $baseUrl . '/' . ltrim($path, '/');
    }

    /**
     * Generate unique filename
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '_' . time();

        return $filename . '.' . $extension;
    }

    /**
     * Extract path from URL
     *
     * @param string $url
     * @return string|null
     */
    protected function getPathFromUrl(string $url): ?string
    {
        $baseUrl = rtrim(config('filesystems.disks.r2.url'), '/');

        if (str_starts_with($url, $baseUrl)) {
            return ltrim(str_replace($baseUrl, '', $url), '/');
        }

        return null;
    }

    /**
     * Validate image file
     *
     * @param UploadedFile $file
     * @return bool
     */
    public function validateImage(UploadedFile $file): bool
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 3 * 1024 * 1024; // 3MB

        return in_array($file->getMimeType(), $allowedMimes) && $file->getSize() <= $maxSize;
    }
}
