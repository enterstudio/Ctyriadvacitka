<?php
/**
 * Created by PhpStorm.
 * User: krouma
 * Date: 11.2.17
 * Time: 11:55
 */

namespace App\Model;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class DatabaseHelper
 * @package App\Model
 */
class DatabaseHelper
{
    private $tables;

    public function __construct()
    {
        $tableArticles = new Table('article');
        $tableArticles
            ->addColumn('article_id', 'integer', 11)->setPrimaryKey()->setAutoIncrement()
            ->addColumn('title', 'string', 255)
            ->addColumn('content', 'text')
            ->addColumn('url', 'string', 255)->addToUniques()
            ->addColumn('description', 'string', 255)
            ->addColumn('requestable', 'boolean');
        $tableNews = new Table('news');
        $tableNews
            ->addColumn('news_id', 'integer', 11)->setPrimaryKey()->setAutoIncrement()
            ->addColumn('title', 'string', 255)
            ->addColumn('content', 'text')
            ->addColumn('url', 'string', 255)->addToUniques()
            ->addColumn('description', 'string', 255)
            ->addColumn('requestable', 'boolean');
        $tableUsers = new Table('user');
        $tableUsers
            ->addColumn('user_id', 'integer', 11)->setPrimaryKey()->setAutoIncrement()
            ->addColumn('username', 'string', 255)->addToUniques()
            ->addColumn('password', 'string', 255)
            ->addColumn('role', 'string', 255)
            ->addColumn('name', 'string', 255)
            ->addColumn('surname', 'string', 255)
            ->addColumn('nickname', 'string', 255)
            ->addColumn('email', 'string', 255)->addToUniques();
        $this->tables = [
            $tableArticles->getName() => $tableArticles,
            $tableNews->getName() => $tableNews,
            $tableUsers->getName() => $tableUsers
        ];
    }

    /**
     * @return array
     */
    public function getTables(): array
    {
        return $this->tables;
    }

    /**
     * @param string $tableName
     * @return Table
     */
    public function getTable(string $tableName): Table
    {
        return $this->tables[$tableName];
    }

    /**
     *
     */
    public function generateInstallQueries(): array
    {
        $schema = new Schema();

        /** @var Table $table */
        foreach ($this->tables as $table) {
            $schema->createTable($table->getName());

            /** @var TableColumn $column */
            foreach ($table->getColumns() as $column) {
                $schema->getTable($table->getName())
                    ->addColumn($column->getName(), $column->getType(), array(
                            $column->getProperties(), 'autoincrement' => ($column->getName() == $table->getPrimaryKey()) ? true : false)
                    )
                    ->setNotnull(false);
            }

            $schema->getTable($table->getName())
                ->setPrimaryKey([$table->getPrimaryKey()])
                ->addUniqueIndex($table->getUniques())
                ->addOption('engine', 'MyISAM')
                ->addOption('collate', 'utf8_czech_ci');
        }
        return $schema->toSql(new MySqlPlatform());
    }

}