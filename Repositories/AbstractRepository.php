<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 29.11.2016 г.
 * Time: 13:57
 */

namespace FPopov\Repositories;


use FPopov\Adapter\Database;
use FPopov\Adapter\DatabaseInterface;
use FPopov\UserExceptions\ApplicationException;

abstract class AbstractRepository
{
    protected $tableName;
    protected $primaryKeyName;
    protected $db;

    public function __construct(DatabaseInterface $db)
    {
        $options = $this->setOptions();
        $this->tableName = isset($options['tableName']) ? $options['tableName'] : '';
        $this->primaryKeyName = isset($options['primaryKeyName']) ? $options['primaryKeyName'] : '';
        $this->db = $db;
    }

    abstract public function setOptions();

    public function buildWhereCondition(array $conditions = array())
    {
        if (count($conditions) == 0) {
            return false;
        }

        $and = '';
        $where = '';
        $placeholder = '?';

        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                if (isset($value['comparator']) && isset($value['value'])) {
                    $where .= $and . $column . ' ' . $value['comparator'] . ' ' . $placeholder;
                } else {
                    $valuesAsString = '(';
                    foreach ($value as $singleValue) {
                        $valuesAsString .= $placeholder . ', ';
                    }
                    $valuesAsString = substr($valuesAsString, 0, count($valuesAsString) - 3) . ')';
                    $where .= $and . $column . ' IN ' . $valuesAsString;

                }
            } else {
                $where .= $and . $column . ' = ' . $placeholder;
            }
            $and = ' AND ';
        }

        return $where;
    }

    public function create(array $bindParams = array()) : bool
    {
        if (count($bindParams) == 0) {
            throw new ApplicationException('Please set params');
        }

        $comma = '';
        $cols = '';
        $placeholders = '';
        $placeholdersValues = [];

        foreach ($bindParams as $key => $value) {
            $cols .= $comma . "{$key}";
            $placeholders .= $comma . "?";
            $comma = ', ';
            $placeholdersValues[] = $value;
        }

        $placeholders = '(' . $placeholders . ')';
        $cols = '(' . $cols . ')';

        $query = "
            INSERT INTO {$this->tableName} {$cols} VALUES {$placeholders}
        ";

        $stmt = $this->db->prepare($query);

        return $stmt->execute($placeholdersValues);
    }

    public function update(int $id, array $bindParams = array()) : bool
    {
        if (count($bindParams) == 0) {
            throw new ApplicationException('Please set params');
        }

        $comma = '';
        $placeholders = '';
        $placeholdersValues = [];
        foreach ($bindParams AS $key => $value) {
            $placeholders .= $comma . "{$key} = ?,";
            $placeholdersValues[] = $value;
        }
        $placeholders = substr($placeholders, 0, count($placeholders) - 2);
        $placeholdersValues[] = $id;

        $query = "
            UPDATE
                {$this->tableName}
            SET
                {$placeholders}
            WHERE
                id = ?
        ";

        $stmt = $this->db->prepare($query);

        return $stmt->execute($placeholdersValues);
    }

    public function delete($id) : bool
    {
        $query = "
            DELETE FROM
                {$this->tableName}
            WHERE
                id = ?
        ";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function findOneRowById(int $id, $dbClass)
    {
        $query = "
            SELECT * FROM {$this->tableName} WHERE {$this->primaryKeyName} = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetchObject($dbClass);
    }

    /**
     * @param $dbClass
     * @return \Generator
     */
    public function findAll($dbClass)
    {
        $query = "
            SELECT * FROM {$this->tableName} ORDER BY {$this->primaryKeyName} ASC
        ";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        while ($result = $stmt->fetchObject($dbClass)) {
            yield $result;
        }
    }

//    public function findBy($conditions, $assoc = true, $forceIndex = null, $sortBy = null, $sortDir = 'asc', $limit = null, $offset = 0)
//    {
//        $this->checkSetup(__METHOD__);
//
//        if (false === $where = $this->buildWhereExpressionFromConditions($conditions)) {
//            return array();
//        }
//
//        $fi = $this->buildForceIndex($forceIndex);
//
//        $sort = '';
//        if(! is_null($sortBy)) {
//            $sort = " ORDER BY {$sortBy} " . strtoupper($sortDir);
//        }
//
//        $limitBy = '';
//        if (! is_null($limit)) {
//            $limit = (int) $limit;
//            $offset = (int) $offset;
//            $limitBy = " LIMIT {$limit} OFFSET {$offset} ";
//        }
//
//        $sql = "SELECT * FROM {$this->tableName} {$fi} WHERE {$where}{$sort}{$limitBy}";
//        if ($assoc === PDO::FETCH_CLASS) {
//            if (! class_exists($this->entityClassName)) {
//                throw new DatabaseException('Could not use loaders to repo which do not have entityClassName info.');
//            }
//            return $this->adapter->exec($sql, $conditions)->fetchAll(PDO::FETCH_CLASS, $this->entityClassName);
//        } else if ($assoc) {
//            return $this->adapter->exec($sql, $conditions)->fetchAll(PDO::FETCH_ASSOC);
//        } else {
//            return $this->adapter->exec($sql, $conditions)->fetchAll();
//        }
//    }
}
