<?php
/* function partialFilters1($searchBuilder) {
  if ($searchBuilder->getTotalRows() > 0) {
  ?>
  <div class="panel-group" id="accordion-filters" role="tablist" aria-multiselectable="true">
  <?php foreach ($searchBuilder->getFilters() as $facet): ?>
  <?php
  $key = $facet['key'];
  $normalizedKey = str_replace('.', '_', $key);
  $terms = array_slice($facet['terms'], 0);
  ?>
  <div class="panel panel-success">
  <div class="panel-heading" role="tab" id="<?php echo 'heading' . $normalizedKey ?>">
  <h4 class="panel-title">
  <a role="button" data-target="#<?php echo 'filter' . $normalizedKey ?>" data-toggle="collapse" aria-expanded="true" aria-controls="<?php echo 'filter' . $normalizedKey ?>">
  <i class="fa fa-expand"></i> <?php echo $facet['display_name'] ?>
  </a>
  </h4>
  </div>
  <div id="<?php echo 'filter' . $normalizedKey ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
  <div class="panel-body">
  <ul class="no-disk">
  <?php foreach ($terms as $tag) : ?>
  <li>
  <a href="<?php echo $searchBuilder->getUrlByFilter($key, $tag['tag_display_name']); ?>">
  <?php if ($tag['selected'] == true): ?>
  <i class="fa fa-check-square"></i>
  <?php else: ?>
  <i class="fa fa-square-o"></i>
  <?php endif; ?>
  <?php echo ucwords($tag['tag_display_name']); ?>
  <?php echo '(' . $tag['count'] . ')'; ?>
  </a>
  </li>
  <?php endforeach; ?>
  </ul>
  </div>
  </div>
  </div>
  <?php endforeach; ?>
  </div>
  <?php }
  }
 */

function partialFilters($searchBuilder) {
    //run without filter
    $original_facets = [];
    $selectedFilters = $searchBuilder->getSelectedFilters();
    if (sizeof($selectedFilters) > 0) {
        $original_facets = $searchBuilder->getSearchResultsNoFilter();
    }
    if ($searchBuilder->getTotalRows() > 0) {
        if (sizeof($selectedFilters) > 0) {
            ?>
            <div style="margin: -10px 0 5px 10px;">
                <a class="hyperlink" role="button" href="<?php echo $searchBuilder->getUrlWithQyery(); ?>">
                    <i class="glyphicon glyphicon-remove-sign"></i>
                    Clear All Filters
                </a>
            </div>
        <?php } ?>
        <div class="panel-group" id="accordion-filters" role="tablist" aria-multiselectable="true">
            <?php foreach ($searchBuilder->getFilters() as $facet): ?>
                <?php
                $key = $facet['key'];
                $normalizedKeyid = str_replace('.', '_', $key);
                $normalizedKey = $key;
                $terms = array_slice($facet['terms'], 0);
                $tag_names = [];
                foreach ($terms as $tag) {
                    array_push($tag_names, $tag['tag_display_name']);
                }
                $oldterms = [];
                if (sizeof($selectedFilters) > 0) {
                    $oldterms = array_slice($original_facets[$key]['terms'], 0);
                }
                ?>
                <div class="panel panel-success">
                    <div class="panel-heading" role="tab" id="<?php echo 'heading' . $normalizedKeyid ?>">
                        <h4 class="panel-title">
                            <a role="button" data-target="#<?php echo 'filter' . $normalizedKeyid ?>" data-toggle="collapse" aria-expanded="true" aria-controls="<?php echo 'filter' . $normalizedKeyid ?>">
                                <i class="fa fa-expand"></i> <?php echo $facet['display_name'] ?>
                            </a>
                        </h4>
                    </div>
                    <div id="<?php echo 'filter' . $normalizedKeyid ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            <ul class="no-disk">
                                <?php $termN = 0; ?>
                                <?php $threshold = 6; ?>
                                <?php $result = get_no_result_filter($terms, $oldterms, $selectedFilters[$normalizedKey]); ?>
                                <?php $zero_filters = $result['zero_filters']; ?>
                                <?php $left_terms = $result['left_terms']; ?>
                                <?php foreach ($terms as $tag) : ?>
                                    <?php $termN = $termN + 1; ?>
                                    <?php if ($termN == $threshold): ?>
                                        <li class="container_hide">
                                            <input type="checkbox" id="check_id<?php echo $normalizedKey; ?>">
                                            <label for="check_id<?php echo $normalizedKey; ?>"></label>
                                            <ul>
                                            <?php endif; ?>
                                            <li>
                                                <a href="<?php echo $searchBuilder->getUrlByFilter($key, $tag['tag_display_name']); ?>">
                                                    <?php if ($tag['selected'] == true): ?>
                                                        <i class="fa fa-check-square"></i>
                                                    <?php else: ?>
                                                        <i class="fa fa-square-o"></i>
                                                    <?php endif; ?>

                                                    <?php echo ucwords($tag['tag_display_name']); ?>
                                                    <?php echo '(' . number_format($tag['count']) . ')'; ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                        <!--show unfiltered terms in gray-->
                                        <?php foreach ($zero_filters as $oldtag) : ?>
                                            <?php if ($termN == $threshold): ?>
                                                <li class="container_hide">
                                                    <input type="checkbox" id="check_id<?php echo $normalizedKey; ?>">
                                                    <label for="check_id<?php echo $normalizedKey; ?>"></label>
                                                    <ul>
                                                    <?php endif; ?>
                                                    <li>
                                                        <a href="<?php echo $searchBuilder->getUrlByFilter($key, $oldtag['tag_display_name']); ?>">
                                                            <i class="fa fa-check-square"></i>
                                                            <?php echo ucwords($oldtag['tag_display_name']); ?>
                                                            <?php echo '(0)'; ?>
                                                            <?php $termN = $termN + 1; ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>

                                                <?php foreach ($left_terms as $oldtag) : ?>
                                                    <?php if ($termN == $threshold): ?>
                                                        <li class="container_hide">
                                                            <input type="checkbox" id="check_id<?php echo $normalizedKey; ?>">
                                                            <label for="check_id<?php echo $normalizedKey; ?>"></label>
                                                            <ul>

                                                            <?php endif; ?>
                                                            <li>
                                                                <a href="<?php echo $searchBuilder->getUrlByFilter($key, $oldtag['tag_display_name']); ?>">
                                                                    <i class="fa fa-square-o"></i>
                                                                    <span style="color:gray">
                                                                        <?php echo ucwords($oldtag['tag_display_name']); ?>
                                                                        <?php echo '(' . $oldtag['count'] . ')'; ?>
                                                                    </span>
                                                                    <?php $termN = $termN + 1; ?>
                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                        <?php if ($termN >= $threshold): ?>
                                                        </ul>
                                                    </li>
                                                <?php endif; ?>

                                            </ul>
                                            </div>
                                            </div>
                                            </div>
                                        <?php endforeach; ?>
                                        </div>
                                        <?php
                                    }
                                }

                                function get_no_result_filter($terms, $oldterms, $selectedFilters) {
                                    $result = [];
                                    $name = [];
                                    $zero_filters = [];
                                    $left_terms = [];
                                    foreach ($terms as $term) {
                                        array_push($name, $term['name']);
                                    }

                                    foreach ($oldterms as $term) {

                                        if (!in_array($term['name'], $name)) {

                                            if (in_array($term['tag_display_name'], $selectedFilters)) {
                                                array_push($zero_filters, $term);
                                            } else {
                                                array_push($left_terms, $term);
                                            }
                                        }
                                    }
                                    $result['zero_filters'] = $zero_filters;
                                    $result['left_terms'] = $left_terms;
                                    return $result;
                                }
                                ?>