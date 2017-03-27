<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 15.3.17
 * Time: 15:31
 */

namespace App\AdminModule\Model;


use App\Model\BaseManager;

/**
 * Class ImageManager
 * @package AdminModule\Model
 */
class ImageManager extends BaseManager
{
    /**
     * @return array
     */
    public function getHeaderImageNames(): array
    {
        $dir = opendir(__DIR__ . '/../../../www/img/top/');
        $images = [];

        while ($soubor = readdir($dir)) {
            if ($soubor !== '..' && $soubor !== '.') {
                $images[] = $soubor;
            }
        }
        return $images;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function deleteImage(string $path): bool
    {
        return unlink($path);
    }
}