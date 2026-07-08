<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApiImageUploader
{
    public static function store(Request $request, string $field, string $directory): ?string
    {
        if ($request->hasFile($field)) {
            return self::storeUploadedFile($request->file($field), $directory);
        }

        $value = $request->input($field);

        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        return self::storeBase64($value, $directory);
    }

    public static function storeUploadedFile(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public');
    }

    public static function storeBase64(string $value, string $directory): ?string
    {
        $extension = 'jpg';

        if (preg_match('/^data:image\/(\w+);base64,/', $value, $matches)) {
            $extension = self::normalizeExtension($matches[1]);
            $value = substr($value, strpos($value, ',') + 1);
        }

        $decoded = base64_decode($value, true);

        if ($decoded === false) {
            return null;
        }

        if (strlen($decoded) > 10 * 1024 * 1024) {
            return null;
        }

        $filename = trim($directory, '/') . '/' . Str::uuid() . '.' . $extension;
        Storage::disk('public')->put($filename, $decoded);

        return $filename;
    }

    public static function storeMany(Request $request, string $field, string $directory): array
    {
        $paths = [];

        if ($request->hasFile($field)) {
            foreach (Arr::wrap($request->file($field)) as $file) {
                if ($file instanceof UploadedFile && $file->isValid()) {
                    $paths[] = self::storeUploadedFile($file, $directory);
                }
            }
        }

        if ($paths !== []) {
            return $paths;
        }

        $value = $request->input($field);

        if (is_array($value)) {
            foreach ($value as $item) {
                if (is_string($item) && ($path = self::storeBase64($item, $directory))) {
                    $paths[] = $path;
                }
            }

            return $paths;
        }

        if (is_string($value) && ($path = self::storeBase64($value, $directory))) {
            return [$path];
        }

        return [];
    }

    public static function urls(mixed $paths): array
    {
        if (is_string($paths)) {
            $decoded = json_decode($paths, true);
            $paths = is_array($decoded) ? $decoded : ($paths !== '' ? [$paths] : []);
        }

        if (! is_array($paths)) {
            return [];
        }

        return array_values(array_filter(array_map(fn ($path) => self::url($path), $paths)));
    }

    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    private static function normalizeExtension(string $extension): string
    {
        return match (strtolower($extension)) {
            'jpeg' => 'jpg',
            'png', 'gif', 'webp' => strtolower($extension),
            default => 'jpg',
        };
    }
}
