<!--Word Cloud-->
<?php
function partialWordCloud(){
    ?>

    <div class="panel panel-primary" id="word-cloud">
        <div class="panel-heading">
            <strong>Word Cloud</strong>
            <input type="button" value="Apply" id="word-apply" style="display: none" disabled>
        </div>

        <div class="panel-body">
            <svg  xmlns="http://www.w3.org/2000/svg" id="svg-key" viewBox="0 0 180 180">
            </svg>
        </div>
    </div>

<?php
}
?>
