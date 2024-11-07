<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


/**
 * Get Image Url
 *
 * @param $path
 *
 * @return string
 */
if (!function_exists('getUrl')) {
    function getUrl($path)
    {
        return Storage::disk('public')->url($path);
    }
}

/**
 * Remove File Form Folder
 *
 * @param $path
 *
 * @return bool
 */
if (!function_exists('removeFile')) {
    function removeFile($path)
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        return true;
    }
}

/**
 * Upload File Form Folder
 *
 * @param $image
 * @param $folder
 *
 * @return bool
 */
if (!function_exists('uploadFileToFolder')) {
    function uploadFileToFolder($file, $folder)
    {
        $getClientOriginalName = str_replace(' ', '_', $file->getClientOriginalName());
        $path = Storage::disk('public')->putFileAs($folder, $file, rand(0, 999999) . "_" . $getClientOriginalName);
        return $path;
    }
}
