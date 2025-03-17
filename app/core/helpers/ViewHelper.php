<?php
namespace App\Core\Helpers;

class ViewHelper
{
    public static function viewPlugin($fileName, $data = [])
    {
        if (!empty($data)) {
            extract($data);
        }

        if (file_exists($fileName))
        {
            require $fileName;
        }
    }
}