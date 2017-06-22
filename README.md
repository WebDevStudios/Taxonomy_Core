Taxonomy_Core
=========

A tool to make custom taxonomy registration just a bit simpler. Automatically registers taxonomy labels, and provides helpful methods.

Also see [CPT_Core](https://github.com/jtsternberg/CPT_Core).

### IMPORTANT UPGRADE INFORMATION
Please note, that library initiation method had been changed since 0.2.5 version. Now you need to hook to `taxonomy_core_load` action to work with Taxonomy Core methods.

#### Example Usage:
```php
<?php

function myprefix_taxonomy_core_demo() {
	/**
	 * Load Taxonomy_Core
	 */
	require_once 'Taxonomy_Core/Taxonomy_Core.php';

	/**
	 * Will register a 'Genre' Taxonomy to posts
	 * First parameter is an array with Singular, Plural, and Registered name
	 */
	$genres = register_via_taxonomy_core( array(
		__( 'Genre', 'your-text-domain' ), // Singular
		__( 'Genres', 'your-text-domain' ), // Plural
		'genre' // Registered name
	) );

	$names = array(
		__( 'Actress', 'your-text-domain' ), // Singular
		__( 'Actresses', 'your-text-domain' ), // Plural
		'film-actress' // Registered name
	);
	// Will register an 'Actress' Taxonomy to 'movies' post-type
	$actresses = register_via_taxonomy_core( $names, array(), array( 'movies' ) );

	/**
	 * Use the Taxonomy_Core object:
	 */

	// Gets all the taxonomy arguments
	$actress_args = $actresses->get_args();

	// Outputs 'film-actress', the taxonomoy registration name/slug
	$actress_slug = $actresses->taxonomy();

	// Outputs 'Actresses'
	$actress_plural = $actresses->taxonomy( 'plural' );

	// Outputs 'Actress'
	$actress_singular = $actresses->taxonomy( 'singular' );
}

add_action( 'taxonomy_core_load', 'myprefix_taxonomy_core_demo', TAXONOMY_CORE_LOADED + 1 );
