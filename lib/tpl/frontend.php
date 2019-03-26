<?php
if ( is_active_sidebar( $this->get_prefix( $settings['template'] ) ) ) {
	echo '<div id="' . $this->get_prefix( $settings['template'] ) . '" class="' . $this->get_prefix() . '">';
	dynamic_sidebar( $this->get_prefix( $settings['template'] ) );
	echo '</div>';
}