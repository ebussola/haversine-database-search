<?php
/**
 * Created by PhpStorm.
 * User: Leonardo Shinagawa
 * Date: 19/05/14
 * Time: 20:32
 */

namespace ebussola\haversine\database;


use Doctrine\DBAL\Connection;

class Search
{

    const KILOMETER = 6371;
    const MILES = 3959;

    /**
     * @var Connection
     */
    protected $conn;

    /**
     * @var TableSchema
     */
    protected $schema;

    /**
     * @var int
     */
    protected $measure_unit;

    public function __construct(Connection $conn, TableSchema $schema, $measure_unit = self::KILOMETER)
    {
        $this->conn = $conn;
        $this->schema = $schema;
        $this->measure_unit = $measure_unit;
    }

    /**
     * @param string $latitude
     * @param string $longitude
     * @param int $radius
     * @return array
     */
    public function nearBy($latitude, $longitude, $radius)
    {
        $query = $this->conn->createQueryBuilder()
            ->select(
                [
                    $this->schema->id,
                    '( ' . $this->measure_unit . ' * acos( cos( radians(?) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(?) ) + sin( radians(?) ) * sin( radians( lat ) ) ) ) as distance'
                ]
            )
            ->from($this->schema->table_name, 'markers')
            ->having('distance < ?')
            ->orderBy('distance');

        $result = $this->conn->executeQuery(
            $query,
            [
                $latitude,
                $longitude,
                $latitude,
                $radius
            ]
        )->fetchAll(\PDO::FETCH_CLASS);

        return $result;
    }

}