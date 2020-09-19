<?php
	if ( is_active_sidebar( $settings['id'] ) ) {
		// Loads the common.css
		$this->get_script( 'common' )->set_is_enqueued();

		echo '<div class="' . $this->get_prefix() . ' ' . $settings['id'] . '">';
		dynamic_sidebar( $settings['id'] );
		echo '</div>';
	}