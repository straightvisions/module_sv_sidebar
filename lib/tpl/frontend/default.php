<?php
	// Loads the common.css
	$this->get_script( 'common' )->set_is_enqueued();

	echo '<div class="' . $this->get_prefix() . ' ' . $ID . '">';
	dynamic_sidebar( $ID );
	echo '</div>';