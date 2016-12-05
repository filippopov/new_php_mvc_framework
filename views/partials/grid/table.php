<?php
/**
 * @var \FPopov\Core\ViewInterface $this
 */

$textAlign = array(
    'string' => 'left',
    'numeric' => 'right',
    'boolean' => 'center'
);

$tableData = isset($model['tableData']) ? $model['tableData'] : array();
$colHeader = isset($model['tableData'][0]) ? $model['tableData'][0] : array();
$allColumnName = array();

$isSetOrderFields = isset($tableOrderFields) ? true : false;
foreach ($colHeader as $header) {
    $allColumnName[$header['name']] = $header['title'];
    if (! $isSetOrderFields && $header['type'] != \FPopov\Services\AbstractService::TYPE_ACTIONS) {
        $tableOrderFields[] = $header['name'];
    }
    if ($header['type'] == \FPopov\Services\AbstractService::TYPE_HIDDEN) {
        $aHiddenRow[$header['name']] = 1;
    }
}

$filter = isset($model['filter']) ? $model['filter'] : array();

$aFieldClosure = array(
    \FPopov\Services\AbstractService::TYPE_DATA => function ($cell) {
        return isset($cell['onClick']) ? '<a href="' . $cell['onClick'] . '">' . $cell['value'] . '</a>' : $cell['value'];
    },
    \FPopov\Services\AbstractService::TYPE_HIDDEN => function ($cell) {
        return '<input type="hidden" name="' . $cell['fieldName'] .'" value="' . $cell['value'] . '" />';
    },
    \FPopov\Services\AbstractService::TYPE_INPUT => function ($cell) use ($textAlign){
        $align = isset($textAlign[$cell['typeOfData']]) ? $textAlign[$cell['typeOfData']] : 'left';
        $class = isset($cell['class']) ? ' class="' . $cell['class'] . '" ' : 'class="input-sm"';
        $name = isset($cell['fieldName']) ? ' name="' . $cell['fieldName'] . '" ' : '';
        $type = isset($cell['inputType']) ? $cell['inputType'] : 'text';

        return '<input type="' . $type . '" value="' . $cell['value'] . '" ' . $class . ' ' . $name . ' ' . 'style="text-align:' . $align . '"' . ' >';
    },
    \FPopov\Services\AbstractService::TYPE_BUTTON => function ($cell) {
        $value = isset($cell['value']) ? ' value="' . $cell['value'] . '" ' : '';
        $title = isset($cell['title']) ? $cell['title'] : '';
        $onClick = isset($cell['onClick']) ? 'onclick="' . $cell['onClick'] .'"': '';

        return '<button type="submit" ' . $value . ' ' . $onClick . '>' . $title . '</button>';
    },
    \FPopov\Services\AbstractService::TYPE_SELECT => function ($cell) {
        $result = '<select class="form-control" style="width:initial; min-width:100%" name="' . $cell['fieldName'] . '">';
        foreach ($cell['compleatValues'] AS $key => $value) {
            $selected = $key == $cell['value'] ? 'selected' : '';
     
            $result .= '<option value="' . $key . '" ' . $selected . ">" . $value . "</option>";
        }

        $result .= '</select>';

        return $result;
    },
    \FPopov\Services\AbstractService::TYPE_ACTIONS => function ($cell) {
        $actions = array();
        static $index = 0;

        foreach ($cell['actions'] AS $actionKey => $actionUrl) {

            $tmpAction = '<div class="btn btn-icon-only fa-item"';
            switch ($actionKey) {
                case 'edit' :
                    $tmpAction .= ' onclick="createOrUpdate(\'' . $actionUrl .'\')"><i class="fa fa-edit" title="Edit"></i>';
                    break;
                case 'delete' :
                    $tmpAction .= ' onclick="remove(\'' . $actionUrl .'\', ' . ++$index . ')"><i id="button' . $index .'" class="fa fa-remove" title="Delete"></i>';
                    break;
                case 'show' :
                    $tmpAction .= ' onclick="show(\'' . $actionUrl .'\')"><i id="button' . $index .'" class="fa fa-folder-open-o" title="Show"></i>';
                    break;
            }
            $tmpAction .= '</div>';

            $actions[] = $tmpAction;
        }

        return implode(' ', $actions);
    }
);
?>

<table class="table table-striped" id="<?php echo isset($tableId) ? $tableId : 'table'; ?>">
    <thead>
        <tr>
            <?php foreach ($allColumnName as $columnName => $columnTitle) : ?>
                <th class="table-cell table-header-color border <?php echo isset($aHiddenRow[$columnName]) ? 'hidden' : ''; ?>" >
                    <?php if (in_array($columnName, $tableOrderFields)) :
                        $tmpOrder = isset($filter['filter'], $filter['filter']['order'], $filter['filter']['order'][$columnName])
                            ? $filter['filter']['order'][$columnName] : false;
                    ?>
                    <a href="<?php echo $this->generateUriWithOrderParams($columnName, $filter); ?>">
                        <?php echo $columnTitle; ?>
                    </a>
                    <i class="fa fa-sort<?php echo $tmpOrder ? '-' . strtolower($tmpOrder) : ''; ?>"></i>
                    <?php else : ?>
                    <?php echo $columnTitle; ?>
                    <?php endif; ?>
                </th>
            <?php endforeach ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tableData as $rowNumber => $tableRows) :?>
            <tr>
                <?php foreach ($tableRows as $tableCell) : ?>
                    <td class="table-cell <?php echo isset($aHiddenRow[$tableCell['name']]) ? 'hidden' : ''; ?>" style="vertical-align: inherit">
                         <?php echo $aFieldClosure[$tableCell['type']]($tableCell, $rowNumber); ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>