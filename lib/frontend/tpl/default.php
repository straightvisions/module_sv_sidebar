<?php
if ( is_active_sidebar( $settings['id'] ) ) {
	echo '<div class="' . $this->get_prefix() . ' ' . $settings['id'] . '">';
	dynamic_sidebar( $settings['id'] );
	echo '</div>';
}