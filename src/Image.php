<?php

namespace Osd\L4lHelpers;

use App\Exceptions\ImageCreateErrorException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Interfaces\ImageInterface;

class Image
{
    public static function uploadAndResize(
        UploadedFile $file,
        string $path,
        int $width = 400,
        int $height = 400,
        string $name = '',
        bool $timeInName = true,
        bool $cropping = true,
        string $driver = 'gd'
    ): string {
        try {
            $nameFile = '';
            if ($timeInName) { $nameFile .= time() . '-'; }
            if ($name) {
                $slug = Str::slug($name);
                $ext = $file->getClientOriginalExtension() ?: $file->extension();
                $nameFile .= $slug . '.' . strtolower($ext);
            } else {
                $orig = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION) ?: $file->extension();
                $nameFile .= Str::slug($orig) . '.' . strtolower($ext);
            }

            $fullPath = rtrim(storage_path($path), '/');
            File::ensureDirectoryExists($fullPath);

            $manager = new ImageManager($driver === 'gd' ? GdDriver::class : ImagickDriver::class);

            $result = $cropping
                ? self::crop($manager, $file, $width, $height, $fullPath, $nameFile)
                : self::resize($manager, $file, $width, $height, $fullPath, $nameFile);

            if (!$result) {
                throw new \RuntimeException('Crop/resize returned false');
            }

            Log::info($nameFile . ' uploaded and resized to ' . $path);
            return $nameFile;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('uploadAndResize failed', [
                'error' => $e->getMessage(),
                'file' => $file?->getClientOriginalName(),
                'mime' => $file?->getMimeType(),
                'ext' => $file?->getClientOriginalExtension(),
                'size' => $file?->getSize(),
                'path' => $path,
                'fullPath' => $fullPath ?? null,
                'is_dir' => isset($fullPath) ? is_dir($fullPath) : null,
                'is_writable' => isset($fullPath) ? is_writable($fullPath) : null,
                'driver_param' => $driver,
                'gd_loaded' => extension_loaded('gd'),
                'imagick_loaded' => extension_loaded('imagick'),
                'intervention_version' => class_exists(\Composer\InstalledVersions::class) ? (\Composer\InstalledVersions::isInstalled('intervention/image') ? \Composer\InstalledVersions::getPrettyVersion('intervention/image') : null) : null,
                'trace' => $e->getTraceAsString(),
            ]);
            throw new \RuntimeException('uploadAndResize: '.$e->getMessage(), 0, $e);
        }
    }


    protected static function crop(
        ImageManager $manager,
        UploadedFile $file,
        int $width,
        int $height,
        string $path,
        string $nameFile
    ): ImageInterface {
        return $manager->read($file->getRealPath())
            ->cover($width, $height, 'center')
            ->save($path . '/' . $nameFile);
    }

    protected static function resize(
        ImageManager $manager,
        UploadedFile $file,
        int $width,
        int $height,
        string $path,
        string $nameFile
    ): ImageInterface {
        $img = $manager->read($file->getRealPath());
        $img->scaleDown(width: $width, height: $height);
        return $img->save($path . '/' . $nameFile);
    }
}
