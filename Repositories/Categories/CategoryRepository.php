<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 29.11.2016 г.
 * Time: 22:30
 */

namespace FPopov\Repositories\Categories;


use FPopov\Adapter\DatabaseInterface;
use FPopov\Repositories\AbstractRepository;

class CategoryRepository extends AbstractRepository implements CategoryRepositoryInterface
{
    protected $db;

    public function __construct(DatabaseInterface $db)
    {
        parent::__construct($db);
        $this->db = $db;
    }

    public function setOptions()
    {
        return array(
            'tableName' => 'categories',
            'primaryKeyName' => 'id'
        );
    }

    public function testGrid($params = array())
    {
        $listOfFields = [
            'c.id',
            'c.name'
        ];

        $searchFields = [
            'id' => 'c.id',
            'name' => 'c.name'
        ];

        $orderFields = [
            'id' => 'c.id',
            'name' => 'c.name'
        ];

        $onlyCount = isset($params['onlyCount']) ? true : false;

        list($select, $where, $order, $limit) = $this->buildQuery($params, $listOfFields, $searchFields, $orderFields);

        $query = "
            SELECT
                " . implode(', ', $select) . "
            FROM
                categories AS c
            WHERE 
                TRUE    
        ";

        $query .= $where . $order . $limit;

        $stmt = $this->db->prepare($query);

        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function testGridCount($params = array())
    {
        $params['onlyCount'] = '*';

        $result = $this->testGrid($params);

        return isset($result[0]) ? $result[0]['count'] : 0;
    }
}