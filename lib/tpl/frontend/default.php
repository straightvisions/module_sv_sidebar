<?php
	// Loads the common.css
	$this->get_script( 'common' )->set_is_enqueued();

	echo '<div class="' . $this->get_prefix() . ' ' . $ID . '">';
	ob_start();
	dynamic_sidebar( $ID );
	$sidebar	= ob_get_clean();
	echo apply_filters('the_content', $sidebar);
	echo '</div>';