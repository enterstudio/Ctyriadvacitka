<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 11.2.17
 * Time: 12:11
 */

namespace App\Model;


use Nette\Object;

class Table extends Object
{
    /** @var array */
    private $columns;
    private $name;
    private $primaryKey;
    private $uniques;
    private $autoIncrement;

    public function __construct(string $tableName)
    {
        $this->columns = [];
        $this->name = $tableName;
    }

    /**
     * Adds column to table
     * @param string $name
     * @param string $type
     * @param int|null $properties
     * @return $this|Table
     */
    public function addColumn(string $name, string $type, int $properties = null): Table
    {
        $this->columns[] = new TableColumn($name, $type, $properties);
        return $this;
    }

    /**
     * @return $this|Table
     * @internal param string $primary
     */
    public function setPrimaryKey(): Table
    {
        $this->primaryKey = end($this->columns)->name;
        return $this;
    }

    /**
     * @return $this|Table
     * @internal param array $uniques
     */
    public function addToUniques(): Table
    {
        $this->uniques[] = end($this->columns)->name;
        return $this;
    }

    /**
     * @return Table
     */
    public function setAutoIncrement(): Table
    {
        $this->autoIncrement = end($this->columns)->name;
        return $this;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getComlumnNames(): array
    {
        $names = [];
        /** @var TableColumn $column */
        foreach ($this->columns as $column) {
            $names[] = $column->getName();
        }
        return $names;
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
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * @return array
     */
    public function getUniques(): array
    {
        return $this->uniques;
    }
}