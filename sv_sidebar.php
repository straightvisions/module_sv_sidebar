<?php
namespace sv_100;

/**
 * @version		 1.00
 * @author			straightvisions
 * @package			sv_100
 * @copyright		2019 Matthias Bathke
 * @link			https://straightvisions.com
 * @since			1.0
 * @license			See license.txt or https://straightvisions.com
 */

class sv_sidebar extends init {
	protected static $sidebars					= array();
	protected static $custom_scripts			= array();

	// Properties
	protected $ID								= false;
	protected $title							= false;
	protected $description						= false;
	protected $before_widget					= false;
	protected $after_widget						= false;
	protected $before_title						= false;
	protected $after_title						= false;

	public function init() {
		// Module Info
		$this->set_module_title( 'SV Sidebar' );
		$this->set_module_desc( __( 'This module gives the ability to display sidebars via the "[sv_sidebar]" shortcode.', 'straightvisions_100' ) );

		// Action Hooks
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
	}

	public function load( $settings = array() ) :string {
		$settings								= shortcode_atts(
			array(
				'id'							=> false,
			),
			$settings,
			$this->get_module_name()
		);

		$settings['id']						 = $this->get_prefix( $settings['id'] );

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
		$new									= new static();

		$new->set_root( $parent->get_root() );
		$new->set_parent( $parent );

		$new->ID								= $this->get_prefix( $parent->get_module_name() );

		return $new;
	}

	public function load_sidebar() :sv_sidebar {
		$sidebar				= array(
			'name'			  	=> $this->get_title() ? $this->get_title() : $this->get_ID(),
			'id'				=> $this->get_ID(),
			'description'	   	=> $this->get_desc() ? $this->get_desc() : '',
			'before_widget'	 	=> $this->get_before_widget() ? $this->get_before_widget() : '<div id="%1$s" class="widget %2$s">',
			'after_widget'	  	=> $this->get_after_widget() ? $this->get_after_widget() : '</div>',
			'before_title'	  	=> $this->get_before_title() ? $this->get_before_title() : '<h3 class="' . $this->get_prefix() . '">',
			'after_title'	   	=> $this->get_after_title() ? $this->get_after_title() : '</h3>',
		);

		static::$sidebars[]	 	= $sidebar;

		return $this->get_root()->sv_sidebar;
	}
	
	// Adds a widget to the given sidebar
	public function add_widget_to_sidebar( string $widget_id, string $sidebar, $widget_data = false ): sv_sidebar {
		$sidebars_widgets 	= get_option( 'sidebars_widgets', array() );
		$widget_instances 	= get_option( 'widget_' . $widget_id, array() );
		
		if ( isset( $widget_instances ) ) {
			$numeric_keys 	= array_filter( array_keys( $widget_instances ), 'is_int' );
			$next_key 		= count( $numeric_keys ) + 1;
			
			if ( ! isset( $sidebars_widgets[ $sidebar ] ) ) {
				$sidebars_widgets[ $sidebar ] = array();
			}
			
			$sidebars_widgets[ $sidebar ][] = $widget_id . '-' . $next_key;
			$widget_instances[ $next_key ] 	= $widget_data ? $widget_data : array();
			
			update_option( 'sidebars_widgets', $sidebars_widgets );
			update_option( 'widget_' . $widget_id, $widget_instances );
		}
		
		return $this;
	}
	
	// Removes all widgets from the given sidebar
	public function clear_sidebar( string $sidebar ): sv_sidebar {
		$sidebars_widgets 	= get_option( 'sidebars_widgets', array() );
		
		if ( isset( $sidebars_widgets[ $sidebar ] )  ) {
			$sidebars_widgets[ $sidebar ] = array();
			
			update_option( 'sidebars_widgets', $sidebars_widgets );
		}
		
		return $this;
	}

	// Setter & Getter
	public function set_ID( string $ID ) :sv_sidebar {
		$this->ID			   = $this->get_ID() . '_' . $ID;

		return $this;
	}

	public function get_ID() :string {
		return $this->ID;
	}

	public function set_title( string $title ) :sv_sidebar {
		$this->title							 = $title;

		return $this;
	}

	public function get_title() :string {
		return $this->title;
	}

	public function set_desc( string $description ) :sv_sidebar {
		$this->description					  = $description;

		return $this;
	}

	public function get_desc() :string {
		return $this->description;
	}

	public function set_before_widget( string $before_widget ) :sv_sidebar {
		$this->before_widget					= $before_widget;

		return $this;
	}

	public function get_before_widget() :string {
		return $this->before_widget;
	}

	public function set_after_widget( string $after_widget ) :sv_sidebar {
		$this->after_widget					 = $after_widget;

		return $this;
	}

	public function get_after_widget() :string {
		return $this->after_widget;
	}

	public function set_before_title( string $before_title ) :sv_sidebar {
		$this->before_title					 = $before_title;

		return $this;
	}

	public function get_before_title() :string {
		return $this->before_title;
	}

	public function set_after_title( string $after_title ) :sv_sidebar {
		$this->after_title					  = $after_title;

		return $this;
	}

	public function get_after_title() :string {
		return $this->after_title;
	}
}