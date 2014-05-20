<?php
/**
 * Created by PhpStorm.
 * User: Leonardo Shinagawa
 * Date: 19/05/14
 * Time: 20:46
 */

namespace ebussola\haversine\database;


use Doctrine\DBAL\Connection;

class TableSchema
{

    /**
     * @var string
     */
    public $table_name;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $latitude;

    /**
     * @var string
     */
    public $longitude;

    public function __construct($table_name, $id, $latitude, $longitude)
    {
        $this->table_name = $table_name;
        $this->id = $id;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function generateCreateTableQuery(Connection $conn)
    {
        $schema = $conn->getSchemaManager()->createSchema();
        $table = $schema->createTable($this->table_name);
        $table->addColumn($this->id, 'integer', ['autoincrement' => true]);
        $table->addColumn($this->latitude, 'float');
        $table->addColumn($this->longitude, 'float');
        $table->setPrimaryKey([$this->id]);

        $sql = $schema->toSql($conn->getDatabasePlatform());
        return reset($sql);
    }

}