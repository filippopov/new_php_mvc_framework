<?php /**
 * @var \FPopov\Core\ViewInterface $this
 * @var \FPopov\Models\DB\Category\Category[] $model
 */
$uriJunk = isset($uriJunk) ? $uriJunk : '';
$getParams = isset($getParams) ? $getParams : '';
?>


<?php require_once 'views/partials/grid/filter.php'; ?>

<div class="save-button-container">
    <input type="button"  class="btn btn-default" value="Create new" onclick="createOrUpdate('<?php echo $this->uri('categories', 'addGridCategory')?>')" />
</div>

<div id="tablesContainer" class="col-md-12">
    <?php require 'views/partials/grid/table.php'; ?>
</div>



<?php require 'views/partials/grid/modalForm.php' ?>
<?php require 'views/partials/grid/pagination.php' ?>












