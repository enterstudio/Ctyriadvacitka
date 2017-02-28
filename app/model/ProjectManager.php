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
    private $table;

    /**
     * @param Context $database automatically injected class to work with DB
     * @param DatabaseHelper $databaseHelper automatically injected class to help with DB
     */
    public function __construct(Context $database, DatabaseHelper $databaseHelper)
    {
        parent::__construct($database, $databaseHelper);
        $this->settingsFilePath = "../project.json";
        $this->table = $this->database->table('parameters');
    }

    /**
     * @return string Name of web
     */
    public function getProjectName(): string
    {
        $file = fopen($this->settingsFilePath, 'r');
        $text = fread($file, filesize($this->settingsFilePath));
        $values = Json::decode($text);
        return $values->projectName;
    }

    /**
     * @param string $name
     */
    public function setProjectName(string $name)
    {
        $file = fopen($this->settingsFilePath, 'r+');
        $text = fread($file, filesize($this->settingsFilePath));
        $file = fopen($this->settingsFilePath, 'w');
        $values = Json::decode($text);
        $values->projectName = $name;
        fwrite($file, Json::encode($values));
    }

    public function saveParameterer(array $parameter)
    {
        if ($this->isParameterInDB($parameter['key'])) {
            $this->table->where('key', $parameter['key'])->update($parameter);
        } else {
            $this->table->insert($parameter);
        }
    }

    public function isParameterInDB($key): bool
    {
        $value = $this->table->where('key', $key)->fetch();
        if ($value) {
            return true;
        } else {
            return false;
        }
    }
}