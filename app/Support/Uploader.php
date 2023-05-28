<?php

namespace Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Uploader
{
    /**
     * @param UploadedFile $file
     * @param string $path
     * @return string
     */
    public static function upload(UploadedFile $file, string $path): string
    {
        $fileNameWithExt = $file->getClientOriginalName();
        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileNameToStore = $fileName.'_'.time().'.'.$extension;

        $file->storeAs($path, $fileNameToStore, 'public');

        if (! File::isFile(Storage::disk('public')->path("{$path}/{$fileNameToStore}"))) {
            throw new \RuntimeException("Le fichier n'a pas pu être enregistré.");
        }

        return Storage::disk('public')->path("{$path}/{$fileNameToStore}");
    }

    /**
     * @param UploadedFile[] $files
     * @param string $path
     * @return array
     * @throws \Exception
     */
    public static function uploadMultiple(array $files, string $path): array
    {
        return array_map(fn ($file) => self::upload($file, $path), $files);
    }
}
