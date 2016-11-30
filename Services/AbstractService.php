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
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_SELECT = 'select';
    const TYPE_DATA = 'data';
    const TYPE_PARAGRAPH = 'paragraph';
    const TYPE_ACTIONS = 'actions';
    const TYPE_HIDDEN = 'hidden';
    const TYPE_BUTTON = 'button';
    const TYPE_DATETIME = 'datetime-local';
    const TYPE_JSON = 'json';
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
                    'type' => $collValue['type'],
                    'typeOfData' => isset($collValue['typeOfData']) ? $collValue['typeOfData'] : 'string'
                ];

                switch ($collValue['type']) {
                    case self::TYPE_SELECT :
                        $tempCell['completeValues'] = $collValue['completeValues'];
                        break;
                    case self::TYPE_ACTIONS :
                        if (! empty($collValue['value'])) {
                            $tempCell['action'] = [];
                            foreach ($collValue['value'] as $actionKey => $actionValue) {
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
}