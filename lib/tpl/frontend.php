<?php
if ( is_active_sidebar( $this->get_module_name() . '_' . $this->template ) ) {
	?>
	<div id="<?php echo $this->get_module_name() . '_' . $this->template; ?>" class="<?php echo $this->get_module_name(); ?>">
		<?php dynamic_sidebar( $this->get_module_name() . '_' . $this->template ); ?>
	</div>
	<?php
}