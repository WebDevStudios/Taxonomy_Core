Taxonomy_Core
=========

A tool to make custom taxonomy registration just a bit simpler. Automatically registers taxonomy labels, and provides helpful methods.

Also see [CPT_Core](https://github.com/jtsternberg/CPT_Core).

#### Example Usage:
```php
<?php

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
