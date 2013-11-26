Taxonomy_Core
=========

A tool to make custom taxonomy registration just a bit simpler. Automatically registers taxonomy labels, and provides helpful methods.

#### Example Usage:
```php
<?php

/**
 * Load Taxonomy_Core.
 */
require_once 'Taxonomy_Core/Taxonomy_Core.php';

/**
 * Will register a 'Q & A' CPT
 */
register_via_taxonomy_core( 'Genre' );
```
