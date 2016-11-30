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

    public function testGrid()
    {
        $query = "
            SELECT
                c.id,
                c.name
            FROM
                categories AS c
        ";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll();
    }
}