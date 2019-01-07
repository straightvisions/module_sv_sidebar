<?php
namespace sv_100;

/**
 * @author			straightvisions
 * @package			sv_100
 * @copyright		2019 Matthias Bathke
 * @link			https://straightvisions.com
 * @since			1.0
 * @license			See license.txt or https://straightvisions.com
 */

class sv_sidebar extends init {
	static $scripts_loaded						= false;
	private $template                           = false;
	private $sidebars                           = array();

	public function __construct( $path, $url ) {
		$this->path								= $path;
		$this->url								= $url;
		$this->name								= get_class($this);

		add_shortcode( $this->get_module_name(), array( $this, 'shortcode' ) );

		add_action( 'widgets_init', array( $this, 'sidebars' ) );
	}

	public function shortcode( $settings, $content = '' ) {
		$settings								= shortcode_atts(
			array(
				'inline'						=> false,
				'template'                      => false,
			),
			$settings,
			$this->get_module_name()
		);

		$this->template                         = $settings['template'] ? $settings['template'] : 'home';

		$this->module_enqueue_scripts( $settings['inline'] );

		ob_start();
		include( $this->get_file_path( 'lib/tpl/frontend.php' ) );
		$output									= ob_get_contents();
		ob_end_clean();

		return $output;
	}

	public function sidebars() {
		array_push( $this->sidebars,
			array(
				'name'									=> __( 'Footer - Left', $this->get_module_name() ),
				'id'									=> $this->get_module_name() . '_footer_left',
				'description'							=> __( 'Widgets in this area will be shown in the left section of the footer.', $this->get_module_name() ),
				'before_widget'							=> '<div id="%1$s" class="widget %2$s mb-3">',
				'after_widget'							=> '</div>',
				'before_title'							=> '<h3 class="' . $this->get_module_name() . '">',
				'after_title'							=> '</h3>',
			)
		);

		array_push( $this->sidebars,
			array(
				'name'									=> __( 'Footer - Center', $this->get_module_name() ),
				'id'									=> $this->get_module_name() . '_footer_center',
				'description'							=> __( 'Widgets in this area will be shown in the center section of the footer.', $this->get_module_name() ),
				'before_widget'							=> '<div id="%1$s" class="widget %2$s mb-3">',
				'after_widget'							=> '</div>',
				'before_title'							=> '<h3 class="' . $this->get_module_name() . '">',
				'after_title'							=> '</h3>',
			)
		);

		array_push( $this->sidebars,
			array(
				'name'									=> __( 'Footer - Right', $this->get_module_name() ),
				'id'									=> $this->get_module_name() . '_footer_right',
				'description'							=> __( 'Widgets in this area will be shown in the right section of the footer.', $this->get_module_name() ),
				'before_widget'							=> '<div id="%1$s" class="widget %2$s mb-3">',
				'after_widget'							=> '</div>',
				'before_title'							=> '<h3 class="' . $this->get_module_name() . '">',
				'after_title'							=> '</h3>',
			)
		);

		array_push( $this->sidebars,
			array(
				'name'									=> __( 'Home Sidebar', $this->get_module_name() ),
				'id'									=> $this->get_module_name() . '_home',
				'description'							=> __( 'Widgets in this area will be shown in the sidebar of the home page.', $this->get_module_name() ),
				'before_widget'							=> '<div id="%1$s" class="widget %2$s mb-3">',
				'after_widget'							=> '</div>',
				'before_title'							=> '<h3 class="' . $this->get_module_name() . '">',
				'after_title'							=> '</h3>',
			)
		);

		array_push( $this->sidebars,
			array(
				'name'									=> __( 'Post Sidebar', $this->get_module_name() ),
				'id'									=> $this->get_module_name() . '_post',
				'description'							=> __( 'Widgets in this area will be shown in the sidebar of single posts.', $this->get_module_name() ),
				'before_widget'							=> '<div id="%1$s" class="widget %2$s mb-5">',
				'after_widget'							=> '</div>',
				'before_title'							=> '<h3 class="' . $this->get_module_name() . ' mb-4 pb-3 border-bottom">',
				'after_title'							=> '</h3>',
			)
		);

		array_push( $this->sidebars,
			array(
				'name'									=> __( 'Author Sidebar', $this->get_module_name() ),
				'id'									=> $this->get_module_name() . '_author',
				'description'							=> __( 'Widgets in this area will be shown in the sidebar of author pages.', $this->get_module_name() ),
				'before_widget'							=> '<div id="%1$s" class="widget %2$s mb-5">',
				'after_widget'							=> '</div>',
				'before_title'							=> '<h3 class="' . $this->get_module_name() . ' mb-4 pb-3 border-bottom">',
				'after_title'							=> '</h3>',
			)
		);

		array_push( $this->sidebars,
			array(
				'name'									=> __( 'Search Sidebar', $this->get_module_name() ),
				'id'									=> $this->get_module_name() . '_search',
				'description'							=> __( 'Widgets in this area will be shown in the sidebar of search page.', $this->get_module_name() ),
				'before_widget'							=> '<div id="%1$s" class="widget %2$s mb-5">',
				'after_widget'							=> '</div>',
				'before_title'							=> '<h3 class="' . $this->get_module_name() . ' mb-4 pb-3 border-bottom">',
				'after_title'							=> '</h3>',
			)
		);

		foreach ( $this->sidebars as $sidebar ) {
			register_sidebar( $sidebar );
		}
	}
}