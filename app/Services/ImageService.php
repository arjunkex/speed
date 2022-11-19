<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageService
{
    /**
     * Upload file using base64 string.
     *
     * @param $image
     * @param  string|null  $folder
     * @return string
     */
    public function uploadImageAndGetPath($image, string $folder = null): string
    {
        $imageName = Str::uuid() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        if ($folder) {
            $folderPathString = 'images/' . $folder . '/';
        } else {
            $folderPathString = 'images/';
        }
        $folderPath = public_path($folderPathString);

        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true, true);
        }
        $imageNameWithRealFolderPath = $folderPath . $imageName;
        Image::make($image)->save($imageNameWithRealFolderPath);

        return $folderPathString . $imageName;
    }

    /**
     * Upload image file directly using binary data
     *
     * @param $image
     * @param  string|null  $folder
     * @return string
     */
    public function uploadImageFileAndGetPath($image, string $folder = null): string
    {
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        if ($folder) {
            $folderPathString = 'images/' . $folder . '/';
        } else {
            $folderPathString = 'images/';
        }
        $folderPath = public_path($folderPathString);
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true, true);
        }
        $imageNameWithRealFolderPath = $folderPath . $imageName;
        Image::make($image)->save($imageNameWithRealFolderPath);

        return $folderPathString . $imageName;
    }

    /**
     * @throws Exception
     */
    public function validateBase64Image(string $image)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            //take out the base64 encoded text without the mine type
            $image = substr($image, strpos($image, ',') + 1);
            //get file extension
            $type = strtolower($type[1]); // jpg, png, gif
            // check file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new Exception('invalid image type');
            }
            //image replaced space with + sign if exists
            $image = str_replace(' ', '+', $image);
            // decoding the string to image
            $image = base64_decode($image);
            if ($image === false) {
                throw new Exception('base64_decode failed');
            }
        } else {
            throw new Exception('did not match data URI with image data');
        }

        return true;
    }

    public function checkImageExistsAndDelete(string $imagePath, $folder = null): void
    {
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
    }
}