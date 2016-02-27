<?php
/**
 * Plugin Name: WDS Taxonomy Core
 * Plugin URI:  http://webdevstudios.com
 * Description: Taxonomy registration starter class
 * Version:     0.2.4
 * Author:      WebDevStudios.com
 * Author URI:  http://webdevstudios.com
 * License:     GPLv2
 * Domain:      taxonomy-core
 * Path:        languages
 */

if ( ! class_exists( 'Taxonomy_Core' ) ) :

	/**
	 * Plugin class for generating/registering custom Taxonomies.
	 * @version 0.2.4
	 * @author  Justin Sternberg
	 *
	 * Text Domain: taxonomy-core
	 * Domain Path: /languages
	 */
	class Taxonomy_Core {

		/**
		 * Singlur Taxonomy label
		 * @var string
		 */
		protected $singular;

		/**
		 * Plural Taxonomy label
		 * @var string
		 */
		protected $plural;

		/**
		 * Registered Taxonomy name/slug
		 * @var string
		 */
		protected $taxonomy;

		/**
		 * Optional argument overrides passed in from the constructor.
		 * @var array
		 */
		protected $arg_overrides = array();

		/**
		 * All Taxonomy registration arguments
		 * @var array
		 */
		protected $taxonomy_args = array();

		/**
		 * Objects to register this taxonomy against
		 * @var array
		 */
		protected $object_types;

		/**
		 * An array of each Taxonomy_Core object registered with this class
		 * @var array
		 */
		protected static $taxonomies = array();

		/**
		 * Whether text-domain has been registered
		 * @var boolean
		 */
		protected static $l10n_done = false;

		/**
		 * Constructor. Builds our Taxonomy.
		 * @since 0.1.0
		 * @param mixed $taxonomy      Singular Taxonomy name, or array with Singular, Plural, and Registered
		 * @param array $arg_overrides Taxonomy registration override arguments
		 * @param array $object_types  Post types to register this taxonomy for
		 */
		public function __construct( $taxonomy, $arg_overrides = array(), $object_types = array( 'post' ) ) {

			if ( ! is_array( $taxonomy ) ) {
				wp_die( __( 'It is required to pass a single, plural and slug string to Taxonomy_Core', 'taxonomy-core' ) );
			}

			if ( ! isset( $taxonomy[0], $taxonomy[1], $taxonomy[2] ) ) {
				wp_die( __( 'It is required to pass a single, plural and slug string to Taxonomy_Core', 'cpt-core' ) );
			}

			if ( ! is_string( $taxonomy[0] ) || ! is_string( $taxonomy[1] ) || ! is_string( $taxonomy[2] ) ) {
				wp_die( __( 'It is required to pass a single, plural and slug string to Taxonomy_Core', 'taxonomy-core' ) );
			}

			$this->singular      = $taxonomy[0];
			$this->plural        = ! isset( $taxonomy[1] ) || ! is_string( $taxonomy[1] ) ? $taxonomy[0] .'s' : $taxonomy[1];
			$this->taxonomy      = ! isset( $taxonomy[2] ) || ! is_string( $taxonomy[2] ) ? sanitize_title( $this->plural ) : $taxonomy[2];
			$this->arg_overrides = (array) $arg_overrides;
			$this->object_types  = (array) $object_types;

			// load text domain
			add_action( 'plugins_loaded', array( $this, 'l10n' ), 5 );
			add_action( 'init', array( $this, 'register_taxonomy' ), 5 );
		}

		/**
		 * Gets the passed in arguments combined with our defaults.
		 * @since  0.1.0
		 * @return array  Taxonomy arguments array
		 */
		public function get_args() {
			if ( ! empty( $this->taxonomy_args ) ) {
				return $this->taxonomy_args;
			}

			// Hierarchical check that will be used multiple times below
			$hierarchical = true;
			if ( isset( $this->arg_overrides['hierarchical'] ) ) {
				$hierarchical = (bool) $this->arg_overrides['hierarchical'];
			}

			// Generate CPT labels
			$labels = array(
				'name'                       => $this->plural,
				'singular_name'              => $this->singular,
				'search_items'               => sprintf( __( 'Search %s', 'taxonomy-core' ), $this->plural ),
				'all_items'                  => sprintf( __( 'All %s', 'taxonomy-core' ), $this->plural ),
				'edit_item'                  => sprintf( __( 'Edit %s', 'taxonomy-core' ), $this->singular ),
				'view_item'                  => sprintf( __( 'View %s', 'taxonomy-core' ), $this->singular ),
				'update_item'                => sprintf( __( 'Update %s', 'taxonomy-core' ), $this->singular ),
				'add_new_item'               => sprintf( __( 'Add New %s', 'taxonomy-core' ), $this->singular ),
				'new_item_name'              => sprintf( __( 'New %s Name', 'taxonomy-core' ), $this->singular ),
				'not_found'                  => sprintf( __( 'No %s found.', 'taxonomy-core' ), $this->plural ),
				'no_terms'                   => sprintf( __( 'No %s', 'taxonomy-core' ), $this->plural ),

				// Hierarchical stuff
				'parent_item'       => $hierarchical ? sprintf( __( 'Parent %s', 'taxonomy-core' ), $this->singular ) : null,
				'parent_item_colon' => $hierarchical ? sprintf( __( 'Parent %s:', 'taxonomy-core' ), $this->singular ) : null,

				// Non-hierarchical stuff
				'popular_items'              => $hierarchical ? null : sprintf( __( 'Popular %s', 'taxonomy-core' ), $this->plural ),
				'separate_items_with_commas' => $hierarchical ? null : sprintf( __( 'Separate %s with commas', 'taxonomy-core' ), $this->plural ),
				'add_or_remove_items'        => $hierarchical ? null : sprintf( __( 'Add or remove %s', 'taxonomy-core' ), $this->plural ),
				'choose_from_most_used'      => $hierarchical ? null : sprintf( __( 'Choose from the most used %s', 'taxonomy-core' ), $this->plural ),
			);

			$defaults = array(
				'labels'            => array(),
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'hierarchical' => $hierarchical, 'slug' => $this->taxonomy ),
			);

			$this->taxonomy_args           = wp_parse_args( $this->arg_overrides, $defaults );
			$this->taxonomy_args['labels'] = wp_parse_args( $this->taxonomy_args['labels'], $labels );

			return $this->taxonomy_args;
		}

		/**
		 * Actually registers our Taxonomy with the merged arguments
		 * @since  0.1.0
		 */
		public function register_taxonomy() {
			global $wp_taxonomies;

			// Register our Taxonomy
			$args = register_taxonomy( $this->taxonomy, $this->object_types, $this->get_args() );
			// If error, yell about it.
			if ( is_wp_error( $args ) ) {
				wp_die( $args->get_error_message() );
			}

			// Success. Set args to what WP returns
			$this->taxonomy_args = $wp_taxonomies[ $this->taxonomy ];

			// Add this taxonomy to our taxonomies array
			self::$taxonomies[ $this->taxonomy ] = $this;
		}

		/**
		 * Provides access to protected class properties.
		 * @since  0.1.0
		 * @param  string $key Specific taxonomy parameter to return
		 * @return mixed       Specific taxonomy parameter or array of singular, plural and registered name
		 */
		public function taxonomy( $key = 'taxonomy' ) {

			return isset( $this->$key ) ? $this->$key : array(
				'singular'     => $this->singular,
				'plural'       => $this->plural,
				'taxonomy'     => $this->taxonomy,
				'object_types' => $this->object_types,
			);
		}

		/**
		 * Provides access to all Taxonomy_Core taxonomy objects registered via this class.
		 * @since  0.1.0
		 * @param  string $taxonomy Specific Taxonomy_Core object to return, or 'true' to specify only names.
		 * @return mixed            Specific Taxonomy_Core object or array of all
		 */
		public static function taxonomies( $taxonomy = '' ) {
			if ( true === $taxonomy && ! empty( self::$taxonomies ) ) {
				return array_keys( self::$taxonomies );
			}
			return isset( self::$taxonomies[ $taxonomy ] ) ? self::$taxonomies[ $taxonomy ] : self::$taxonomies;
		}

		/**
		 * Magic method that echos the Taxonomy registered name when treated like a string
		 * @since  0.1.0
		 * @return string Taxonomy registered name
		 */
		public function __toString() {
			return $this->taxonomy();
		}

		/**
		 * Load this library's text domain
		 * @since  0.2.0
		 */
		public function l10n() {
			// Only do this one time
			if ( self::$l10n_done ) {
				return;
			}

			$locale = apply_filters( 'plugin_locale', get_locale(), 'taxonomy-core' );
			$mofile = dirname( __FILE__ ) . '/languages/taxonomy-core-'. $locale .'.mo';
			load_textdomain( 'taxonomy-core', $mofile );
		}

	}

	if ( ! function_exists( 'register_via_taxonomy_core' ) ) {
		/**
		 * Helper function to register a Taxonomy via the Taxonomy_Core class.
		 * @since  0.1.0
		 * @param  mixed $taxonomy      Singular Taxonomy name, or array with Singular, Plural, and Registered
		 * @param  array $arg_overrides Taxonomy registration override arguments
		 * @param  array $object_types  Post types to register this taxonomy for
		 * @return Taxonomy_Core        An instance of the class.
		 */
		function register_via_taxonomy_core( $taxonomy, $arg_overrides = array(), $object_types = array( 'post' ) ) {
			return new Taxonomy_Core( $taxonomy, $arg_overrides, $object_types );
		}
	}

endif; // end class_exists check
