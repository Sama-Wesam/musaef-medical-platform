<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UploadTrait
{
    /**
     * رفع ملف إلى مجلد محدد وإرجاع مساره
     */
    public function uploadFile(UploadedFile $file, string $folder = 'uploads', string $disk = 'public'): string
    {
        $fileName = Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        $path = $file->storeAs($folder, $fileName, $disk);

        return $path;
    }

    /**
     * حذف ملف من السيرفر
     */
    public function deleteFile(?string $path, string $disk = 'public'): bool
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }

    /**
     * الحصول على الرابط الكامل للملف (URL)
     */
    public function getFileUrl(?string $path, string $disk = 'public'): ?string
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->url($path);
        }

        return null;
    }
}