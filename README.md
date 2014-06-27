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
 */
$genres = register_via_taxonomy_core( 'Genre' );

/**
 * Will register a 'Color' Taxonomy to pages
 * First parameter can be an array with Singular, Plural, and Registered name
 */
$actresses = register_via_taxonomy_core( array( 'Actress', 'Actresses', 'film-actress' ), array(), array( 'page' ) );

/**
 * Use the Taxonomy_Core object:
 */

// Gets all the taxonomy arguments
$actress_args = $actresses->get_args()

// Outputs 'film-actress'
$actress_slug = $actresses->taxonomy()

// Outputs 'Actresses'
$actress_plural = $actresses->taxonomy( 'plural' );

// Outputs 'Actress'
$actress_singular = $actresses->taxonomy( 'singular' );
