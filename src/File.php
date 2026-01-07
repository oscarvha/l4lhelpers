<?php

namespace Osd\L4lHelpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class File
{
    /**
     * @param string $disk
     * @param UploadedFile $file
     * @param bool $timeInName
     * @param string $path
     * @return false|string
     */
    public static function upload(string $disk,
                                      UploadedFile $file,
                                      string $name = '',
                                      bool $timeInName = true,
                                      string $path =''): bool|string
    {
        $nameFile = '';

        if($timeInName) {
            $nameFile.= time().'-';
        }

        if($name) {
            $name = Str::slug($name);
            $nameFile.= $name.'.'.$file->extension();
        }else {
            $nameFile .= $file->getClientOriginalName();
        }

       return Storage::disk($disk)->putFileAs(
            $path,
            $file,
           $nameFile
        );
    }


    public static function pathFile(string $disk , string $file): string
    {
        return Storage::disk($disk)->path($file);
    }
    /**
     * @param string $disk
     * @param string $file
     */
    public static function delete(string $disk, string $file)
    {
        Storage::disk($disk)->delete($file);
    }

    /**
     * @param string $disk
     * @param string $directory
     */
    public static function deleteDirectory(string $disk, string $directory)
    {
        Storage::disk($disk)->deleteDirectory($directory);
    }

    public static function getMimeType(string $path): string
    {
        try {
            return mime_content_type($path);
        }catch (\Exception $e) {
            return 'application/octet-stream';
        }
    }
}
