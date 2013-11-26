Taxonomy_Core
=========

A tool to make custom taxonomy registration just a bit simpler. Automatically registers taxonomy labels, and provides helpful methods.

Also see (CPT_Core)[https://github.com/jtsternberg/CPT_Core].

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
register_via_taxonomy_core( 'Genre' );

/**
 * Will register a 'Color' Taxonomy to pages
 */
register_via_taxonomy_core( 'Color', array(), array( 'page' ) );
```
