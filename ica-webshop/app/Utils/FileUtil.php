<?php 

namespace App\Utils;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUtil {

    /**
     * Store the file in public folder to path.
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folderPath
     * @return string name of file
     */
    public static function storeFile(UploadedFile $file, string $folderPath)
    {
        $hashName = $file->hashName();
        $file->storeAs('public/' . $folderPath, $hashName);
        return $hashName;
    }

    /**
     * Delete a stored file if exists.
     * 
     * @param string $path path to image in public folder
     * @return boolean successful
     */
    public static function deleteFile(string $path)
    {
        $fullPath = 'public/' . $path;
        if(!Storage::exists($fullPath)) {
            return false;
        }

        Storage::delete($fullPath);
        return true;
    }

}

?>