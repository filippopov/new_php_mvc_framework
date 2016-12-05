<?php
/**
 * @var \FPopov\Core\ViewInterface $this
 */
$tableSearchFields = isset($model['tableSearchFields']) ? $model['tableSearchFields'] : array();
$filter = isset($model['filter']) ? $model['filter'] : array();
$searchFilter = isset($filter['filter'], $filter['filter']['search']) ? $filter['filter']['search'] : array();

?>

<?php if (! empty($tableSearchFields)) : ?>
<div id="filter" class="col-md-12">
   <form id="searchFormId" action="<?php echo $this->urlSearch($filter); ?>" method="post">
        <?php foreach ($tableSearchFields as $seachKeyName => $searchValueTitle) : ?>
        <?php if(is_array($searchValueTitle)) :?>
            <div class="col-md-3" style="display: inline-block; text-align: center">
                <?php
                    $labelName = isset($searchValueTitle['title']) ?  $searchValueTitle['title'] : '';
                    unset($searchValueTitle['title']);
                    $className = isset($searchValueTitle['class']) ? $searchValueTitle['class'] : '';
                    unset($searchValueTitle['class']);
                    $disabled = isset($searchValueTitle['editable']) ? $searchValueTitle['editable'] ? '' : 'disabled' : '';
                    unset($searchValueTitle['editable']);
                ?>
                <label for="<?php echo $seachKeyName ?>"><?php echo $labelName?></label>
                <select class="input input-sm <?php echo $className ?>" name="<?php echo $seachKeyName; ?>" id="<?php echo $seachKeyName?>" <?php echo $disabled?>>
                    <?php foreach ($searchValueTitle as $aKey => $aValue) : ?>
                        <?php
                            $inputValue = isset($searchFilter[$seachKeyName]) ? $searchFilter[$seachKeyName] : '';
                            $selected = $inputValue == $aKey ? 'selected' : '';
                        ?>
                        <option value="<?php echo $aKey ?>" <?php echo $selected ?>><?php echo $aValue ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
            </div>
        <?php else: ?>
            <div class="col-md-3" style="display: inline-block; text-align: center">
                <label for="<?php echo $seachKeyName ?>"><?php echo $searchValueTitle?></label>
                <input
                    class="input input-sm"
                    type="text"
                    name="<?php echo $seachKeyName; ?>"
                    id="<?php echo $seachKeyName?>"
                    placeholder="<?php echo $searchValueTitle; ?>"
                    value="<?php echo isset($searchFilter[$seachKeyName]) ? $searchFilter[$seachKeyName] : ''; ?>"
                    />
                <br>
            </div>
        <?php endif; ?>
        <?php endforeach; ?>
        <button id="submitButton"  class="btn btn-primary">Submit</button>
    </form>
</div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('#submitButton').click(function() {
        var initialLoc = $('#searchFormId').attr('action');
            $('#filter').find($('.input')).each(function() {
                   if($(this).val()) {
                        initialLoc += '&filter[search][' +
                         $(this).attr('name') + ']=' + encodeURIComponent($(this).val());
                   }
             });
             setTimeout(function() {
                window.location.href= initialLoc;
             },1)
        });
    });
</script>