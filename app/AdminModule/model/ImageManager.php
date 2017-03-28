<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 15.3.17
 * Time: 15:31
 */

namespace App\AdminModule\Model;


use App\Model\BaseManager;
use App\Model\DatabaseHelper;
use Nette\Database\Context;

/**
 * Class ImageManager
 * @property string wwwDir
 * @package AdminModule\Model
 */
class ImageManager extends BaseManager
{
    /** @var string */
    private $wwwDir;

    /**
     * ImageManager constructor.
     * @param string $wwwDir
     * @param Context $database
     * @param DatabaseHelper $databaseHelper
     */
    public function __construct(string $wwwDir, Context $database, DatabaseHelper $databaseHelper)
    {
        parent::__construct($database, $databaseHelper);
        $this->wwwDir = $wwwDir;
    }

    /**
     * @return array
     */
    public function getHeaderImageNames(): array
    {
        $dir = opendir($this->wwwDir . '/img/top/');
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