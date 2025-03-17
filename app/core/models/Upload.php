<?php
namespace App\Core\Models;

use Verot\Upload\Upload as VerotUpload;

defined('ROOT') or die("Direct script access denied");

class Upload
{
    private static Upload $instance;
    public VerotUpload $upload;
    public string $file;

    public function __construct($name)
    {
        $this->upload = new VerotUpload($name);
    }

    public static function getInstance($name)
    {
        if(!isset(self::$instance)){
            self::$instance = new self($name);
        }
        return self::$instance;
    }

    public function allowed($mimes = [])
    {
        if (!empty($mimes)) {
            $this->upload->allowed = $mimes;
        } else {
            $this->upload->allowed = ['image/*', 'application/*', 'text/*', 'audio/*', 'video/*', 'application/*'];
        }
        return $this;
    }

    public function rename($name)
    {
        $this->upload->file_new_name_body = $name;
        return $this;
    }

    public function resize($width, $height = null, $crop = true)
    {
        $this->upload->image_resize = true;
        $this->upload->image_x = $width;
        if($height)
        {
            $this->upload->image_y = $height;
            $this->upload->image_ratio_crop = $crop;
        }            
        else
            $this->upload->image_ratio_y = true;

        return $this;
    }

    public function convert($ext)
    {
        $this->upload->image_convert = $ext;
        return $this;
    }

    public function prefix($prefix)
    {
        $this->upload->file_name_body_pre =$prefix;
        return $this;
    }

    public function watermark($text = null)
    {
        if($text)
        {
            $this->upload->image_unsharp         = true;
            $this->upload->image_border          = '0 0 16 0';
            $this->upload->image_border_color    = '#000000';
            $this->upload->image_text            = $text;
            $this->upload->image_text_font       = 2;
            $this->upload->image_text_position   = 'B';
            $this->upload->image_text_padding_y  = 2;
        }

        return $this;
    }

    public function getFile()
    {
        return $this->upload->file_dst_name;
    }

    public function getFilePath()
    {
        return $this->upload->file_dst_pathname;
    }


    public function options(array $options)
    {
        foreach ($options as $key => $option) {
            $this->upload->{$key} = $option;
        }
        return $this;
    }

    public function to($path)
    {
        if($this->upload->uploaded)
        {
            $this->upload->process(realpath('.').$path);
            if($this->upload->processed)
            {
                $this->file = $this->upload->file_dst_name;
            }
        }
        return $this;
    }

    public function error()
    {
        return $this->upload->error;
    }

    public function __destruct()
    {
        $this->upload->clean();
    }

}
