<?php

namespace App\Libs;

class ThumbnailsMaker 
{
    
    private $file;
    private $new_w;
    private $new_h;
    private $save;
    
public function __construct($file, $new_w, $new_h, $save)
 {
    $this->file = $file;
    $this->new_w = $new_w;
    $this->new_h = $new_h;
    $this->save = $save;
    
    if (!is_readable($this->file))
    {
     return FALSE;
    }

    $info = @getimagesize($this->file);
    switch ($info['mime']) 
    {
    case "image/gif":
        $this->file = imagecreatefromgif($this->file);
        break;
    case "image/jpeg":
        $this->file = imagecreatefromjpeg($this->file);
        break;
    case "image/png":
        $this->file = imagecreatefrompng($this->file);
        break;
    }

    $old_x = imageSX($this->file);
    $old_y = imageSY($this->file);

    if ($old_x > $old_y) 
    {
        $thumb_w=$this->new_w;
        $thumb_h=$old_y*($this->new_h/$old_x);
    }
    
    if ($old_x < $old_y) 
    {
        $thumb_w=$old_x*($this->new_w/$old_y);
        $thumb_h=$this->new_h;
    }
    
    if ($old_x == $old_y) 
    {
        $thumb_w=$this->new_w;
        $thumb_h=$this->new_h;
    }
    
    $th = ImageCreateTrueColor($thumb_w, $thumb_h);
    @imagecopyresampled($th, $this->file, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
    @imagejpeg($th, $this->save);
    @imagedestroy($this->file); 
    @imagedestroy($th);
    return TRUE;
 }

/* Uzycie */
//for($i=1; $i<11; $i++)
//{
//resize_ratio("m3.jpg", "300", "300", "min-m3.jpg");
//}
}

