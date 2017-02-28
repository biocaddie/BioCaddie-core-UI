<!-- pagination part -->


<?php

function show_pagination($total_num, $offset, $url, $N) {
    $min = max(1, $offset - 5);
    $min = min($min, ceil($total_num / $N) - 10);
    $min = max($min, 1);
    $max = min(ceil($total_num / $N), $offset + 5);
    $max = max(11, $max);
    if ($total_num / $N >= 1) {
        ?> 

        <nav>
            <ul class="pagination" style="margin: 0 0 10px 0">
                <li>
                    <a href="<?php echo $url . '1' ?>" aria-label="Previsous">
                        <span aria-hidden="true">First</span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo $url . get_previsoue($offset); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <?php
                for ($i = $min; $i <= min($max, ceil($total_num / $N)); $i++) {

                    if ($offset == $i) {
                        $a_flag = 'database="active"';
                    } else {
                        $a_flag = '';
                    }
                    ?>

                    <li <?php echo $a_flag; ?>> <a href=" <?php echo $url . $i; ?>"><?php echo $i ?></a></li>
                <?php } ?>
                <li>
                    <a href="<?php echo $url . get_next($offset, $total_num, $N) ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>

                <li>

                    <a href="<?php echo $url . ceil($total_num / $N) ?>" aria-label="Next">
                        <span aria-hidden="true">Last </span>
                    </a>
                </li>

            </ul>
        </nav>


    <?php }
} ?>