<?php

/**
 * @var \FPopov\Core\ViewInterface $this
 */

$filter = isset($model['filter']) ? $model['filter'] : array();

$aFilter = isset($filter['filter']) ? $filter['filter'] : array();
$iPage = isset($aFilter['page']) ? $aFilter['page'] : 1;
$iOnPage = isset($aFilter['onPage']) ? $aFilter['onPage'] : 10;
$iTotal = isset($filter['total']) ? $filter['total'] : 0;
$iTotalPage = ceil($iTotal / $iOnPage);

$hasPrevious = $iPage > 1;
$haNext = $iTotalPage > $iPage;
?>
<div id="footer" class="col-md-12">
<nav aria-label="Page navigation">
  <ul class="pagination" style="margin: 0; float: right">
      <li>
          <?php if ($hasPrevious) : ?>
              <a href="<?php echo $this->generatePageUrl($iPage - 1, $filter); ?>" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
              </a>
          <?php endif; ?>
      </li>
      <?php if ($iPage > 5) : ?>
          <li><span>...</span></li>
      <?php endif; ?>
      <?php for ($i = ($iPage - 5 > 1 ? $iPage - 5 : 1); $i <= ($iTotalPage > $iPage + 5 ? $iPage + 5 : $iTotalPage); $i++) : ?>
          <li class="<?php echo $iPage == $i ? 'active' : ''; ?>">
              <a href="<?php echo $this->generatePageUrl($i, $filter); ?>">
                  <?php echo $i; ?>
              </a>
          </li>
      <?php endfor; ?>
      <?php if ($iTotalPage - $iPage > 5) : ?>
          <li><span>...</span></li>
      <?php endif; ?>
      <li>
          <?php if ($haNext) : ?>
              <a href="<?php echo $this->generatePageUrl($iPage + 1, $filter); ?>" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
              </a>
          <?php endif; ?>
      </li>
  </ul>
</nav>
    <div>
<?php if ($iTotal > 15) : ?>
<select selectpicker class="form-control rows-per-page" style="width: 80px" >
    <option value="<?php echo $this->generatePageUrlCounter(15, $filter);?>" <?php echo $selected = $iOnPage == 15 ? 'selected' : ''?>>15</option>
            <option value="<?php echo $this->generatePageUrlCounter(20, $filter);?>" <?php echo $selected = $iOnPage == 20 ? 'selected' : ''?>>20</option>
            <option value="<?php echo $this->generatePageUrlCounter(30, $filter);?>" <?php echo $selected = $iOnPage == 30 ? 'selected' : ''?>>30</option>
        </select>
<?php endif; ?>
</div>

</div>

<script language="JavaScript">
    $(function() {
        $('.rows-per-page').off('change').on('change', function(e){
            var selected = e.target;

            var value = $(selected).val();

            window.location.replace(value);
        });
    });
</script>