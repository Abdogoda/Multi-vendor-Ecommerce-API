<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileControlTrait{
    /**
     * Handle the upload of a file.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string|null
     */
    public function uploadFile(UploadedFile $file, string $directory): ?string{
        if ($file) {
            $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            return $file->storeAs("uploads/".$directory, $filename, 'public');
        }
        return null;
    }


    /**
     * Delete a file if it exists.
     *
     * @param string|null $filePath
     * @return void
     */
    public function deleteFile(?string $filePath): void{
        if ($filePath && Storage::exists('public/' . $filePath)) {
            Storage::delete('public/' . $filePath);
        }
    }
}