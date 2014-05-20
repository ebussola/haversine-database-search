<?php

/**
 * Created by PhpStorm.
 * User: Leonardo Shinagawa
 * Date: 19/05/14
 * Time: 21:11
 */

class SearchTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \ebussola\haversine\database\Search
     */
    protected $haversine_search;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $conn;

    public function setUp()
    {
        $config = include __DIR__ . '/config.php';
        $this->conn = \Doctrine\DBAL\DriverManager::getConnection($config);
        $schema = new \ebussola\haversine\database\TableSchema('markers', 'id', 'lat', 'lng');
        $this->conn->exec($schema->generateCreateTableQuery($this->conn));
        $this->populateDb();
        $this->haversine_search = new \ebussola\haversine\database\Search($this->conn, $schema);
    }

    public function tearDown()
    {
        $this->conn->exec('DROP TABLE markers');
    }

    public function testNearBy()
    {
        $markers = $this->haversine_search->nearBy('37.39', '-122.08', 1);
        $this->assertCount(3, $markers);

        $markers = $this->haversine_search->nearBy('37.39', '-122.08', 2);
        $this->assertCount(5, $markers);

        $markers = $this->haversine_search->nearBy('37.39', '-122.08', 5);
        $this->assertCount(6, $markers);
    }

    private function populateDb()
    {
        $this->conn->insert('markers', ['lat'=>'37.386339', 'lng'=>'-122.085823']);
        $this->conn->insert('markers', ['lat'=>'37.38714', 'lng'=>'-122.083235']);
        $this->conn->insert('markers', ['lat'=>'37.393885', 'lng'=>'-122.078916']);
        $this->conn->insert('markers', ['lat'=>'37.402653', 'lng'=>'-122.079354']);
        $this->conn->insert('markers', ['lat'=>'37.394011', 'lng'=>'-122.095528']);
        $this->conn->insert('markers', ['lat'=>'37.401724', 'lng'=>'-122.114646']);
    }

}
 