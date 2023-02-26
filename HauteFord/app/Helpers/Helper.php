<?php

namespace app\Helpers;

class Helper
{
    /**
     * @return string
     */
    public static function getMaxFileSizeHumanReadable(): string
    {
        return min(ini_get('post_max_size'), ini_get('upload_max_filesize'));
    }

    /**
     * @param $size
     * @return float
     */
    public static function parseSizeFromInitFile($size): float
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
            return round($size);
        }
    }

    /**
     * @return float
     */
    public static function getMaxFileSize(): float
    {
        return min(self::parseSizeFromInitFile(ini_get('post_max_size')), self::parseSizeFromInitFile(ini_get('upload_max_filesize')));
    }
}