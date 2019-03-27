<?php
namespace sv_100;

/**
 * @version         1.00
 * @author			straightvisions
 * @package			sv_100
 * @copyright		2019 Matthias Bathke
 * @link			https://straightvisions.com
 * @since			1.0
 * @license			See license.txt or https://straightvisions.com
 */

class sv_sidebar extends init {
	protected static $sidebars                  = array();
	protected static $custom_scripts            = array();

	// Properties
	protected $ID                               = false;
	protected $name                             = false;
	protected $description                      = false;
	protected $before_widget                    = false;
	protected $after_widget                     = false;
	protected $before_title                     = false;
	protected $after_title                      = false;


	public function __construct() {

	}

	public function init(){
		// Translates the module
		load_theme_textdomain( $this->get_module_name(), $this->get_path( 'languages' ) );

		// Module Info
		$this->set_module_title( 'SV Sidebar' );
		$this->set_module_desc( __( 'This module gives the ability to display sidebars via the "[sv_sidebar]" shortcode.', $this->get_module_name() ) );

		// Action Hooks
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );

		// Shortcodes
		add_shortcode( $this->get_module_name(), array( $this, 'shortcode' ) );
	}

	public function shortcode( $settings, $content = '' ) {
		$settings								= shortcode_atts(
			array(
				'id'					    	=> false,
			),
			$settings,
			$this->get_module_name()
		);

		$settings['id']                         = $this->get_prefix( $settings['id'] );

		ob_start();
		include( $this->get_path( 'lib/frontend/tpl/default.php' ) );
		$output									= ob_get_contents();
		ob_end_clean();

		return $output;
	}

	// Registers all created sidebars
	public function register_sidebars() {
		foreach ( static::$sidebars as $sidebar ) {
			register_sidebar( $sidebar );
		}
	}

	// Object Methods
	public function create( $parent ) :sv_sidebar {
		$new                                    = new static();

		$new->set_root( $parent->get_root() );
		$new->set_parent( $parent );

		$new->ID                                = $this->get_prefix( $parent->get_module_name() );

		return $new;
	}

	public function load_sidebar() :sv_sidebar {
		$sidebar                = array(
			'name'              => $this->get_name() ? $this->get_name() : $this->get_ID(),
			'id'                => $this->get_ID(),
			'description'       => $this->get_desc() ? $this->get_desc() : '',
			'before_widget'     => $this->get_before_widget() ? $this->get_before_widget() : '<div id="%1$s" class="widget %2$s">',
			'after_widget'      => $this->get_after_widget() ? $this->get_after_widget() : '</div>',
			'before_title'      => $this->get_before_title() ? $this->get_before_title() : '<h3 class="' . $this->get_prefix() . '">',
			'after_title'       => $this->get_after_title() ? $this->get_after_title() : '</h3>',
		);

		static::$sidebars[]     = $sidebar;

		return $this->get_root()->sv_sidebar;
	}

	// Setter & Getter
	public function set_ID( string $ID ) :sv_sidebar {
		$this->ID               = $this->get_ID() . '_' . $ID;

		return $this;
	}

	public function get_ID() :string {
		return $this->ID;
	}

	public function set_name( string $name ) :sv_sidebar {
		$this->name                             = $name;

		return $this;
	}

	public function get_name() :string {
		return $this->name;
	}

	public function set_desc( string $description ) :sv_sidebar {
		$this->description                      = $description;

		return $this;
	}

	public function get_desc() :string {
		return $this->description;
	}

	public function set_before_widget( string $before_widget ) :sv_sidebar {
		$this->before_widget                    = $before_widget;

		return $this;
	}

	public function get_before_widget() :string {
		return $this->before_widget;
	}

	public function set_after_widget( string $after_widget ) :sv_sidebar {
		$this->after_widget                     = $after_widget;

		return $this;
	}

	public function get_after_widget() :string {
		return $this->after_widget;
	}

	public function set_before_title( string $before_title ) :sv_sidebar {
		$this->before_title                     = $before_title;

		return $this;
	}

	public function get_before_title() :string {
		return $this->before_title;
	}

	public function set_after_title( string $after_title ) :sv_sidebar {
		$this->after_title                      = $after_title;

		return $this;
	}

	public function get_after_title() :string {
		return $this->after_title;
	}
}