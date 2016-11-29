<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 29.11.2016 г.
 * Time: 11:01
 */

namespace FPopov\Services\Category;


use FPopov\Adapter\DatabaseInterface;
use FPopov\Models\Binding\Category\CategoryAddBindingModel;
use FPopov\Models\DB\Category\Category;

class CategoryService implements CategoryServiceInterface
{
    private $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function add(CategoryAddBindingModel $bindingModel) : bool
    {
        $query = "
            INSERT INTO 
                categories (name) 
            VALUES (?)
        ";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $bindingModel->getName()
        ]);
    }

    public function findAll()
    {
        $query = "
            SELECT 
                c.id,
                c.name 
            FROM 
                categories AS c 
            ORDER BY 
                c.name
        ";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        while ($result = $stmt->fetchObject(Category::class)) {
            yield $result;
        }
    }
}