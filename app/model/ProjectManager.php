<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 21.2.17
 * Time: 21:29
 */

namespace App\Model;


use Nette\Database\Context;
use Nette\Utils\Json;

class ProjectManager extends BaseManager
{
    private $settingsFilePath;

    public function __construct(Context $database, DatabaseHelper $databaseHelper)
    {
        parent::__construct($database, $databaseHelper);
        $this->settingsFilePath = "../project.json";
    }

    public function getProjectName(): string
    {
        $file = fopen($this->settingsFilePath, 'r');
        $text = fread($file, filesize($this->settingsFilePath));
        $values = Json::decode($text);
        return $values->projectName;
    }
}