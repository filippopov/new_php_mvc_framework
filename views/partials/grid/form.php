<div class="message"></div>
<?php
/**
 * @var \FPopov\Core\ViewInterface $this
 */

$formFieldData = isset($model['formFieldData']) ? $model['formFieldData'] : array();

$aFieldClosure = array(
    \FPopov\Services\AbstractService::TYPE_DATA => function ($cell) {

        return isset($cell['onClick']) ? '<a name="' . $cell['name'] .'" href="' . $cell['onClick'] . '">' . $cell['value'] . '</a>' : $cell['value'];
    },
    \FPopov\Services\AbstractService::TYPE_HIDDEN => function ($cell) {
        return '<input type="hidden" name="' . $cell['name'] .'" value="' . $cell['value'] . '" />';
    },
    \FPopov\Services\AbstractService::TYPE_INPUT => function ($cell) {
        $class = isset($cell['class']) ? ' class="' . $cell['class'] . '" ' : '';
        $disabled = $cell['editable'] ? '' : 'disabled';

        return '
            <div class="input-group modal-input">
                <input
                    class="form-control col-md-8"
                    type="text"
                    name="' . $cell['name'] .'"
                    value="' . $cell['value'] . '"
                    placeholder="' . $cell['placeHolder'] . '"
                    ' . $class . '
                    ' . $disabled . '
                >
            </div>';
    },
    \FPopov\Services\AbstractService::TYPE_SELECT => function ($cell) {
        $class = $cell['class'];
        $disabled = $cell['editable'] ? '' : 'disabled';
        $result = '<select  selectpicker class="form-control col-md-8 modal-input ' . $class . '" name="' . $cell['name'] . '" ' . $disabled . '>';
        $result .= '<option value="">---</option>';
        foreach ($cell['compleatValues'] AS $key => $value) {
            $selected = $key === $cell['value'] ? 'selected' : '';

            $result .= '<option value="' . $key . '" ' . $selected . ">" . $value . "</option>";
        }

        $result .= '</select>';

        return $result;
    },
    \FPopov\Services\AbstractService::TYPE_TIME => function ($cell) {
        $value = empty($cell['value']) ? '' : date('H:i:s', strtotime($cell['value']));

        return '
            <div class="input-group time input-group modal-input"
                id="datetimepicker2">
                <input
                    type="text"
                    class="form-control col-md-8"
                    name="' . $cell['name'] .'"
                    value="' . $value . '"
                >
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        ';
    }
);

?>

<?php foreach ($formFieldData as $valueFieldData) :?>
    <div class="list-separated col-md-12 modal-row">
        <?php if ($valueFieldData['type'] != \FPopov\Services\AbstractService::TYPE_HIDDEN) : ?>
        <label class="col-md-4 modal-label" ><?php echo $valueFieldData['title']; ?> : </label>
        <?php endif; ?>
        <?php echo $aFieldClosure[$valueFieldData['type']]($valueFieldData); ?>
    </div>
<?php endforeach; ?>

<input type="hidden" name="saveUrl" value="#" />


<script>
    $(document).ready(function() {
        $('#modalForm').find('select').change(function () {
            if (this.value) {
                $("option[value=" + this.value + "]", this)
                    .attr("selected", true).siblings()
                    .removeAttr("selected");
            }
        });
    });
</script>
