<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<form class="form" method="POST" action="<?php url::write( 'settings') ?>" >
<?php	if ( $this->data) {	?>
	<div class="form-group">
		<label for="name">Name</label>
		<input type="text" name="name" class="form-control"
			value="<?php print $this->data->name ?>" />

	</div>

	<div class=" form-check">
		<label class="form-check-label">
			<input type="checkbox" name="lockdown" class="form-check-input" value="1"
				<?php if( $this->data->lockdown) print 'checked'; ?> />

			Lockdown

		</label>

	</div>

	<input type="submit" name="action" value="update" class="btn btn-primary" />

<?php	}	?>

</form>
<script>
$(document).ready( function() {})
</script>