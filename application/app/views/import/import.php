<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * This work is licensed under a Creative Commons Attribution 4.0 International Public License.
 * 		http://creativecommons.org/licenses/by/4.0/
 *
 * */

sys::dump( $this->data);

?>

<?php
foreach ( $this->data->files as $file) {    ?>
<div class="row">
    <div class="col">
        <?= $file->name ?>

    </div>

</div>
<?php
}   ?>