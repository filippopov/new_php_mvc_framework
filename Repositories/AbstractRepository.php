<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 29.11.2016 г.
 * Time: 13:57
 */

namespace FPopov\Repositories;

use FPopov\Adapter\DatabaseInterface;
use FPopov\Exceptions\ApplicationException;

abstract class AbstractRepository
{
    protected $tableName;
    protected $primaryKeyName;
    protected $db;
    protected $orderField = null;

    public function __construct(DatabaseInterface $db)
    {
        $options = $this->setOptions();
        $this->tableName = isset($options['tableName']) ? $options['tableName'] : '';
        $this->primaryKeyName = isset($options['primaryKeyName']) ? $options['primaryKeyName'] : '';
        $this->orderField = isset($options['orderField']) ? $options['orderField'] : $this->primaryKeyName;
        $this->db = $db;
    }

    abstract public function setOptions();

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


    public function findOneRowById(int $id, $dbClass = null)
    {
        $query = "
            SELECT * FROM {$this->tableName} WHERE {$this->primaryKeyName} = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);

        if (empty($dbClass)) {
            return $stmt->fetch();
        }

        return $stmt->fetchObject($dbClass);
    }

    public function findAll($dbClass = null)
    {
        $query = "
            SELECT * FROM {$this->tableName} ORDER BY {$this->primaryKeyName} ASC
        ";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        if (empty($dbClass)) {
            return $stmt->fetchAll();
        } else {
            return $stmt->fetchObject($dbClass);
        }
    }

    public function findByCondition($condition, $dbClass = null, $sortBy = null, $sortDir = 'asc', $limit = null, $offset = 0)
    {
        if (false === $where = $this->buildWhereCondition($condition)) {
            return false;
        }

        $sort = '';
        if (! is_null($sortBy)) {
            $sort = " ORDER BY {$sortBy} " . strtoupper($sortDir);
        }

        $limitBy = '';
        if (! is_null($limit)) {
            $limit = (int) $limit;
            $offset = (int) $offset;
            $limitBy = " LIMIT {$limit} OFFSET {$offset} ";
        }

        $query = "
            SELECT 
                *
            FROM
                {$this->tableName}
            WHERE
                {$where}
                {$sort}
                {$limitBy}
        ";

        $stmt = $this->db->prepare($query);

        $stmt->execute($condition);
        if (empty($dbClass)) {
            return $stmt->fetchAll();
        } else {
            return $stmt->fetchObject($dbClass);
        }
    }

    protected function buildQuery(array &$params, array $listOfFields, $searchFields = array(), $orderFields = array())
    {
        $limitBy = '';
        if (isset($params['page'])) {
            $limit = (int) $params['page']['limit'];
            $offset = (int) $params['page']['offset'];
            $limitBy = " LIMIT {$limit} OFFSET {$offset} ";
        }

        unset($params['page']);

        $where = '';
        if (isset($params['search'])) {
            $searches = $params['search'];
            $pattern = '/^\d+$/';
            foreach ($searches as $field => $search) {
                if (isset($searchFields[$field])) {
                    $match = preg_match($pattern, $search, $searchResult);
                    $comparator = $match ? '=' : 'LIKE';
                    $searchFieldName = $searchFields[$field];

                    $params[$field] = $match ? $search : '%' . $search . '%';

                    $where .= ' AND' . ' ' . $searchFieldName . ' ' . $comparator . ' :' . $field . ' ';
                }
            }

            unset($params['search']);
        }

        $orderBy = array();
        if (! empty($params['order'])) {
            if (empty($orderFields)) {
                $orderFields = $listOfFields;
            }
            foreach ($params['order'] AS $keyField => $valueOrder) {
                if (isset($orderFields[$keyField])) {
                    $orderBy[] = $orderFields[$keyField] . ' ' . $valueOrder;
                } else if (in_array($keyField, $orderFields)) {
                    $orderBy[] = $keyField . ' ' . $valueOrder;
                }
            }

        }
        if (empty($orderBy)) {
            if (isset($orderFields[$this->orderField])) {
                $orderBy[] = $orderFields[$this->orderField] . ' ASC';
            } else {
                $orderBy[] = $this->orderField . ' ASC';
            }
        }
        $orderBy = "
            ORDER BY
            " . implode(', ', $orderBy) ."
        ";
        unset($params['order']);


        $select = array();
        if (isset($params['onlyCount']) && $params['onlyCount']) {
            $select[] = 'COUNT(' . ($params['onlyCount'] !== true ? $params['onlyCount'] : $this->primaryKeyName) . ') AS count';
            $limitBy = '';
            $orderBy = '';
            unset($params['onlyCount']);
        } else {
            $select = $listOfFields;
        }

        return array(
            $select,
            $where,
            $orderBy,
            $limitBy
        );
    }

    private function buildWhereCondition(array &$conditions = array())
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
                    $valueData = [];
                    $valuesAsString = '(';
                    foreach ($value as $singleValue) {
                        $valuesAsString .= $placeholder . ', ';
                        $valueData[] = $singleValue;
                    }
                    $valuesAsString = substr($valuesAsString, 0, count($valuesAsString) - 3) . ')';
                    $where .= $and . $column . ' IN ' . $valuesAsString;
                    unset($conditions[$column]);
                    $conditions = $valueData;
                }
            } else {
                $where .= $and . $column . ' = ' . $placeholder;
                unset($conditions[$column]);
                $conditions[] = $value;
            }
            $and = ' AND ';

        }

        return $where;
    }
}
