<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 21.2.17
 * Time: 21:29
 */

namespace App\Model;


use Nette\Database\Context;

/**
 * Class ProjectManager
 * @package App\Model
 */
class ProjectManager extends BaseManager
{
    private $table;

    /**
     * @param Context $database automatically injected class to work with DB
     * @param DatabaseHelper $databaseHelper automatically injected class to help with DB
     */
    public function __construct(Context $database, DatabaseHelper $databaseHelper)
    {
        parent::__construct($database, $databaseHelper);
        $this->table = $this->database->table('parameters');
    }

    /**
     * @param $key
     * @param $value
     * @internal param array $parameter
     */
    public function saveParameter($key, $value)
    {
        $parameter = array(
            'key' => $key,
            'value' => $value
        );
        $table = clone $this->table;
        if ($this->isParameterInDB($key)) {
            $table->where('key', $key)->update($parameter);
        } else {
            $table->insert($parameter);
        }
    }

    /**
     * @param $key
     * @return string|mixed|null
     */
    public function getParameter($key)
    {
        $table = clone $this->table;
        $row = $table->where('key', $key)->fetch();
        if ($row == null) {
            return false;
        }
        return $row->value;
    }

    /**
     * @param $key
     * @return bool
     */
    public function isParameterInDB($key): bool
    {
        $table = clone $this->table;
        $value = $table->where('key', $key)->fetch();
        if ($value) {
            return true;
        } else {
            return false;
        }
    }
}