<?php

namespace app\Helpers;

class Image {

  public static function isImageFile(array $file) : bool {
    return (
      $file['tmp_name'] && 
      $file['name'] && 
      exif_imagetype($file['tmp_name']));
  }
}
