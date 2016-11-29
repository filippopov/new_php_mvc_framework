<?php /**
 * @var \FPopov\Core\ViewInterface $this
 * @var \FPopov\Models\DB\Category\Category[] $model
 */
?>
<div class="container">
    <div class="row">
        <div class="col-xs-6">№</div>
        <div class="col-xs-6">Name</div>
    </div>
    <hr/>
    <?php foreach ($model as $category): ?>
        <div class="row">
            <div class="col-xs-6"><?= htmlentities($category->getId());?></div>
            <div class="col-xs-6">
                <a href="<?=$this->uri("categories", "topics", [$category->getId()]);?>">
                    <?= htmlentities($category->getName());?>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
