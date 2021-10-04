<?php
	namespace sv100;

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
			$this->set_module_title( __( 'SV Sidebar', 'sv100' ) )
				->set_module_desc( __( 'Creates and manages sidebars.', 'sv100' ) )
				->load_settings()
				->set_css_cache_active()
				->set_section_title( $this->get_module_title() )
				->set_section_desc( $this->get_module_desc() )
				->set_section_template_path()
				->set_section_order(3000)
				->set_section_icon('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0v24h24v-24h-24zm11 22h-9v-16h9v16zm11 0h-9v-7h9v7zm0-9h-9v-7h9v7z"/></svg>')
				->load_sidebars()
				->get_root()
				->add_section( $this );
	
			// Action Hooks
			add_action( 'widgets_init', array( $this, 'register_sidebars' ) );

			// disable block based sidebars/widgets, as some kind of bug results in some sidebars not displayed on admin screen
			remove_theme_support( 'widgets-block-editor' );
		}
		protected function load_settings(): sv_sidebar{
			$this->get_setting('sidebars')
				->set_title(__('Register Sidebars'))
				->set_description(__('Create unlimited Sidebars for use in Theme Modules', 'sv100'))
				->load_type('group');

			$this->get_setting( 'sidebars' )
				->run_type()
				->add_child()
				->set_ID( 'ID' )
				->set_title( __( 'Sidebar ID', 'sv100' ) )
				->set_description( __( 'The unique ID of the sidebar.', 'sv100' ) )
				->set_required(true)
				->load_type( 'id' )
				->set_placeholder( __( 'ID', 'sv100' ) );

			$this->get_setting( 'sidebars' )
				->run_type()
				->add_child()
				->set_ID( 'entry_label' )
				->set_title( __( 'Sidebar Label', 'sv100' ) )
				->set_description( __( 'A label to differentiate your sidebars.', 'sv100' ) )
				->set_required(true)
				->load_type( 'text' )
				->set_placeholder( __( 'Label', 'sv100' ) );

			$this->get_setting( 'sidebars' )
				->run_type()
				->add_child()
				->set_ID( 'Description' )
				->set_title( __( 'Sidebar Description', 'sv100' ) )
				->set_description( __( 'Description of the sidebar.', 'sv100' ) )
				->load_type( 'text' )
				->set_placeholder( __( 'Description', 'sv100' ) );

			return $this;
		}
		protected function load_sidebars(): sv_sidebar{
			$sidebars = $this->get_setting('sidebars')->get_data();

			if($sidebars && is_array($sidebars) && count($sidebars) > 0){
				foreach($sidebars as $sidebar){
					$this->create( $this, $this->get_prefix($sidebar['id']) )
						->set_title( $sidebar['entry_label'] )
						->set_desc( isset($sidebar['description']) ? $sidebar['description'] : '' )
						->load_sidebar();
				}
			}

			return $this;
		}
		// Registers all created sidebars
		public function register_sidebars() {
			foreach ( static::$sidebars as $sidebar ) {
				register_sidebar( $sidebar );
			}
		}
	
		public function load( string $ID ): string {
			if ( !is_active_sidebar( $ID ) ) {
				return '';
			}

			if(!is_admin()){
				$this->load_settings()->register_scripts();

				foreach($this->get_scripts() as $script){
					$script->set_is_enqueued();
				}

				// conditionally load Custom CSS for active Widgets
				if(isset(wp_get_sidebars_widgets()[$ID])){
					foreach(wp_get_sidebars_widgets()[$ID] as $widget){
						$slug		= _get_widget_id_base($widget);
						$this->get_script( 'widget_'.$slug )
							->set_path( 'lib/css/widgets/'.$slug.'.css' )
							->set_is_enqueued();
					}
				}
			}
	
			ob_start();
			require( $this->get_path( 'lib/tpl/frontend/default.php' ) );
			$output									= ob_get_clean();

			return $output;
		}
	
		// Object Methods
		public function create( $parent, string $ID ): sv_sidebar {
			$new									= new static();
	
			$new->set_root( $parent->get_root() );
			$new->set_parent( $parent );
			$new->set_ID( $ID );
	
			return $new;
		}
	
		public function load_sidebar(): sv_sidebar {
			$sidebar				= array(
				'name'			  	=> $this->get_title() ? $this->get_title() : $this->get_ID(),
				'id'				=> $this->get_ID(),
				'description'	   	=> $this->get_desc() ? $this->get_desc() : '',
				'before_widget'	 	=> $this->get_before_widget()
					? $this->get_before_widget()
					: '<div id="%1$s" class="widget %2$s">',
				'after_widget'	  	=> $this->get_after_widget()
					? $this->get_after_widget()
					: '</div>',
				'before_title'	  	=> $this->get_before_title()
					? $this->get_before_title()
					: '<h3 class="widget-title ' . $this->get_prefix() . '">',
				'after_title'	   	=> $this->get_after_title()
					? $this->get_after_title()
					: '</h3>',
			);

			static::$sidebars[]	 	= $sidebar;
	
			return $this;
		}

		public function get_sidebars( $parent = false ): array {
			$sidebars = $this::$sidebars;
			
			if ( $parent ) {
				$sidebars = array_filter( $sidebars, function ( $sidebar ) use ( $parent ) {
					if ( strpos( $sidebar['id'], $parent->get_module_name() ) ) {
						return true;
					}
					
					return false;
				});
			}
			
			return $sidebars;
		}
		public function get_sidebars_for_settings_options( $parent = false ): array {
			$sidebars = $this::get_sidebars($parent);

			$sidebars_array		= array(''	=> __('No Sidebar', 'sv100'));

			foreach($sidebars as $sidebar){
				$sidebars_array[$sidebar['id']]		= $sidebar['name'];
			}

			return $sidebars_array;
		}

		public function get_sidebars_for_metabox_options( $parent = false ): array {
			$sidebars = $this::get_sidebars($parent);

			$sidebars_array		= array(
				''			=> __('Inherit', 'sv100'),
				'0'			=> __('Hidden', 'sv100'),
			);

			foreach($sidebars as $sidebar){
				$sidebars_array[$sidebar['id']]		= $sidebar['name'];
			}

			return $sidebars_array;
		}
		
		public function set_ID( string $ID ): sv_sidebar {
			if ( $this->get_ID() ) {
				$this->ID = $this->get_ID() . '_' . $ID;
			} else {
				$this->ID = $ID;
			}

			$this->get_parent()
				 ->get_setting( $this->get_ID() )
				 ->set_description( __( 'Widget alignment inside this Sidebar.', 'sv100' ) )
				 ->set_options( array(
				 	'left'		=> __( 'Left', 'sv100' ),
					'center'	=> __( 'Center', 'sv100' ),
					'right'		=> __( 'Right', 'sv100' )
				 ) )
				 ->set_default_value( 'left' )
				 ->load_type( 'select' );
	
			return $this;
		}
	
		public function get_ID(): string {
			return $this->ID;
		}
	
		public function set_title( string $title ): sv_sidebar {
			$this->title = $title;
			
			$this->get_parent()
				 ->get_setting( $this->get_ID() )
				 ->set_title( $this->get_title() );
			
			return $this;
		}
	
		public function get_title() :string {
			return $this->title;
		}
	
		public function set_desc( string $description ): sv_sidebar {
			$this->description = $description;
	
			return $this;
		}
	
		public function get_desc() :string {
			return $this->description;
		}
	
		public function set_before_widget( string $before_widget ): sv_sidebar {
			$this->before_widget = $before_widget;
	
			return $this;
		}
	
		public function get_before_widget(): string {
			return $this->before_widget;
		}
	
		public function set_after_widget( string $after_widget ): sv_sidebar {
			$this->after_widget = $after_widget;
	
			return $this;
		}
	
		public function get_after_widget(): string {
			return $this->after_widget;
		}
	
		public function set_before_title( string $before_title ): sv_sidebar {
			$this->before_title = $before_title;
	
			return $this;
		}
	
		public function get_before_title(): string {
			return $this->before_title;
		}
	
		public function set_after_title( string $after_title ): sv_sidebar {
			$this->after_title = $after_title;
	
			return $this;
		}
	
		public function get_after_title(): string {
			return $this->after_title;
		}
	}