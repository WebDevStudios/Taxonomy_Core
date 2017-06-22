<?php
/**
 * Plugin Name: WDS Taxonomy Core
 * Plugin URI:  http://webdevstudios.com
 * Description: Taxonomy registration starter class
 * Version:     0.2.5
 * Author:      WebDevStudios.com
 * Author URI:  http://webdevstudios.com
 * License:     GPLv2
 * Domain:      taxonomy-core
 * Path:        languages
 */

 /**
  * Loader versioning: http://jtsternberg.github.io/wp-lib-loader/
  */

 if ( ! class_exists( 'Taxonomy_Core_011', false ) ) {

 	/**
 	 * Versioned loader class-name
 	 *
 	 * This ensures each version is loaded/checked.
 	 *
 	 * @category WordPressLibrary
 	 * @package  Taxonomy_Core
 	 * @author   WebDevStudios <contact@webdevstudios.com>
 	 * @license  GPL-2.0+
 	 * @version  0.1.0
 	 * @link     https://webdevstudios.com
 	 * @since    0.1.0
 	 */
 	class Taxonomy_Core_011 {

 		/**
 		 * Taxonomy_Core version number
 		 * @var   string
 		 * @since 0.1.0
 		 */
 		const VERSION = '0.2.5';

 		/**
 		 * Current version hook priority.
 		 * Will decrement with each release
 		 *
 		 * @var   int
 		 * @since 0.2.4
 		 */
 		const PRIORITY = 9998;

 		/**
 		 * Starts the version checking process.
 		 * Creates TAXONOMY_CORE_LOADED definition for early detection by
 		 * other scripts.
 		 *
 		 * Hooks Taxonomy_Core inclusion to the taxonomy_core_load hook
 		 * on a high priority which decrements (increasing the priority) with
 		 * each version release.
 		 *
 		 * @since 0.2.4
 		 */
 		public function __construct() {
 			if ( ! defined( 'TAXONOMY_CORE_LOADED' ) ) {
 				/**
 				 * A constant you can use to check if Taxonomy_Core is loaded
 				 * for your plugins/themes with Taxonomy_Core dependency.
 				 *
 				 * Can also be used to determine the priority of the hook
 				 * in use for the currently loaded version.
 				 */
 				define( 'TAXONOMY_CORE_LOADED', self::PRIORITY );
 			}

 			// Use the hook system to ensure only the newest version is loaded.
 			add_action( 'taxonomy_core_load', array( $this, 'include_lib' ), self::PRIORITY );

 			/*
 			 * Hook in to the first hook we have available and
 			 * fire our `taxonomy_core_load' hook.
 			 */
 			add_action( 'muplugins_loaded', array( __CLASS__, 'fire_hook' ), 9 );
 			add_action( 'plugins_loaded', array( __CLASS__, 'fire_hook' ), 9 );
 			add_action( 'after_setup_theme', array( __CLASS__, 'fire_hook' ), 9 );
 		}

 		/**
 		 * Fires the taxonomy_core_load action hook.
 		 *
 		 * @since 0.2.4
 		 */
 		public static function fire_hook() {
 			if ( ! did_action( 'taxonomy_core_load' ) ) {
 				// Then fire our hook.
 				do_action( 'taxonomy_core_load' );
 			}
 		}

 		/**
 		 * A final check if Taxonomy_Core exists before kicking off
 		 * our Taxonomy_Core loading.
 		 *
 		 * TAXONOMY_CORE_VERSION and TAXONOMY_CORE_DIR constants are
 		 * set at this point.
 		 *
 		 * @since  0.2.4
 		 */
 		public function include_lib() {
 			if ( class_exists( 'Taxonomy_Core', false ) ) {
 				return;
 			}

 			if ( ! defined( 'TAXONOMY_CORE_VERSION' ) ) {
 				/**
 				 * Defines the currently loaded version of Taxonomy_Core.
 				 */
 				define( 'TAXONOMY_CORE_VERSION', self::VERSION );
 			}

 			if ( ! defined( 'TAXONOMY_CORE_DIR' ) ) {
 				/**
 				 * Defines the directory of the currently loaded version of Taxonomy_Core.
 				 */
 				define( 'TAXONOMY_CORE_DIR', dirname( __FILE__ ) . '/' );
 			}

 			// Include and initiate Taxonomy_Core.
 			require_once TAXONOMY_CORE_DIR . 'lib/init.php';
 		}

 	} 

 	// Kick it off.
 	new Taxonomy_Core_011;
 }
