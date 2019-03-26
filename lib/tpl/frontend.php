<?php
if ( is_active_sidebar( $settings['id'] ) ) {
	echo '<div id="' . $settings['id'] . '" class="' . $this->get_prefix() . '">';
	dynamic_sidebar( $settings['id'] );
	echo '</div>';
}