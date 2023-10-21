<?php
/**
 * Plugin Name: ClassifAI v2
 */

use \Classifai\Admin\SubMenuPage;

require_once __DIR__ . '/vendor/autoload.php';

add_action( 'init', function() {
	if ( is_admin() ) {
		new SubmenuPage();
	}
} );
