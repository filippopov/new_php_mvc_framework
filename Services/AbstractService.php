<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 30.11.2016 г.
 * Time: 12:07
 */

namespace FPopov\Services;


abstract class AbstractService
{
    const TYPE_INPUT = 'input';
    const TYPE_SELECT = 'select';
    const TYPE_DATA = 'data';
    const TYPE_ACTIONS = 'actions';
    const TYPE_HIDDEN = 'hidden';
    const TYPE_BUTTON = 'button';
    const TYPE_TIME = 'time';

    protected function generateGridData(array $configuration, array $data)
    {
        $aOrders = [];

        foreach ($data as $row) {
            $tempRow = [];

            foreach ($configuration as $collKey => $collValue) {
                $tempCell = [
                    'name' => $collKey,
                    'title' => $collValue['title'],
                    'value' => isset($row[$collKey]) ? $row[$collKey] : '',
                    'type' => $collValue['type']
                ];

                switch ($collValue['type']) {
                    case self::TYPE_SELECT :
                        $tempCell['completeValues'] = $collValue['completeValues'];
                        break;
                    case self::TYPE_ACTIONS :
                        if (! empty($collValue['values'])) {
                            $tempCell['actions'] = [];
                            foreach ($collValue['values'] as $actionKey => $actionValue) {
                                $tempCell['actions'][$actionKey] = $actionValue($row);
                            }
                        }
                        break;
                }

                if (isset($collValue['inputType'])) {
                    $tempCell['inputType'] = $collValue['inputType'];
                }

                if (isset($collValue['value'])) {
                    $tempCell['value'] = is_callable($collValue['value']) ? $collValue['value']($tempCell['value']) : $collValue['value'];
                }

                if (isset($collValue['class'])) {
                    $tempCell['class'] = is_callable($collValue['class']) ? $collValue['class']($tempCell['value']) : $collValue['class'];
                }

                if (isset($collValue['onClick'])) {
                    $tempCell['onClick'] = is_callable($collValue['onClick']) ? $collValue['onClick']($row) : $collValue['onClick'];
                }

                if (isset($collValue['fieldName'])) {
                    $tempCell['fieldName'] = is_callable($collValue['fieldName']) ? $collValue['fieldName']($row) : $collValue['fieldName'];
                } else {
                    $tempCell['fieldName'] = $collKey;
                }

                $tempRow[] = $tempCell;
            }

            $aOrders[] = $tempRow;
        }

        return $aOrders;
    }

    protected static function generateFormData(array $configuration, array $data = array())
    {
        $result = [];

        foreach ($configuration AS $collKey => $collValue) {
            $tempCell = array(
                'name' => $collKey,
                'title' => $collValue['title'],
                'value' => isset($data[$collKey]) ? $data[$collKey] : '',
                'type' => $collValue['type'],
                'editable' => isset($collValue['editable']) ? $collValue['editable'] : true,
                'placeHolder' => isset($collValue['placeHolder']) ? (isset($data[$collKey]) ? $data[$collKey] : '') : '',
                'class' => isset($collValue['class']) ? $collValue['class'] : ''
            );

            if (isset($collValue['placeHolder'])) {
                $tempCell['value'] = null;
            }

            if (isset($collValue['value'])) {
                $tempCell['value'] = is_callable($collValue['value']) ? $collValue['value']($tempCell['value']) : $collValue['value'];
            }

            switch ($collValue['type']) {
                case self::TYPE_SELECT:
                    $tempCell['compleatValues'] = $collValue['compleatValues'];
                    break;
            }

            $result[] = $tempCell;
        }

        return $result;
    }

    protected function getParamFilters($inputParams = array(), $allowParams = array())
    {
        $returnParams = array();

        $aFilter = $this->getDataFromArray($inputParams, 'filter', []);

        unset($inputParams['filter']);

        $aOrder = $this->getDataFromArray($aFilter, 'order', []);

        if (! empty($aOrder)) {
            $aSortDirection = array('ASC', 'DESC');
            $returnParams['order'] = array();
            foreach ($aOrder AS $key => $value) {
                $value = strtoupper($value);
                if ($key && in_array($value, $aSortDirection)) {
                    $returnParams['order'][$key] = $value;
                }
            }
        }

        $aFind = $this->getDataFromArray($aFilter, 'search', []);
        if ($aFind) {
            foreach ($aFind as $key => $value) {
                $returnParams['search'][$key] = clearInput($value);
            }
        }

        $page = max(1, (int) $this->getDataFromArray($aFilter, 'page', 1));
        $onPage = (int) $this->getDataFromArray($aFilter, 'onPage', 15);
        $returnParams['page'] = array(
            'limit' => $onPage,
            'offset' => $this->calcOffset($page, $onPage)
        );

        foreach ($allowParams AS $value) {
            if (isset($inputParams[$value])) {
                $returnParams[$value] = $inputParams[$value];
            }
        }

        return $returnParams;
    }

    public function pageFilters($params = array())
    {
        $aPage = $this->getDataFromArray($params, 'page', []);
        unset($params['page']);

        $filter = [];

        $iLimit = $this->getDataFromArray($aPage, 'limit', 15);
        $filter["onPage"] = $iLimit;
        $iOffset = (int) $this->getDataFromArray($aPage, 'offset', 0);
        $filter["page"] = ($iOffset + $iLimit) / $iLimit;

        $filter['order'] = $this->getDataFromArray($params, 'order', []);
        unset($params['order']);
        $filter['search'] = $this->getDataFromArray($params, 'search', []);
        unset($params['search']);

        $params['filter'] = $filter;

        return $params;
    }

    private function getDataFromArray(array $params, string $key, $returnValueType = null)
    {
        return isset($params[$key]) ? $params[$key] : $returnValueType;
    }

    private function calcOffset($page, $perOnPage)
    {
        return (max(1, $page) * $perOnPage) - $perOnPage;
    }
}

