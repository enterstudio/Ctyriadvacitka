<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 11.2.17
 * Time: 10:52
 */

namespace App\Model;


use Nette\Object;

/**
 * Class TableColumn
 * @package app\model
 */
class TableColumn extends Object
{
    private $name;
    private $type;
    private $properties;

    /**
     * TableColumn constructor.
     * @param string $name name of column
     * @param string $type type of column
     * @param int|null $properties properties of type eg. length of varchar
     */
    public function __construct(string $name, string $type, int $properties = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->properties = $properties;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int|null
     */
    public function getProperties()
    {
        return $this->properties;
    }

}
