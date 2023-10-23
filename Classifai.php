<?php
/**
 * Plugin Name: ClassifAI v2
 */

use \Classifai\Admin\Settings;

require_once __DIR__ . '/vendor/autoload.php';

add_action( 'init', function() {
	if ( is_admin() ) {
		$settings = new Settings();
		$settings->admin_init();
	}

	require_once 'v2/Features/FeatureHelpers.php';

	generate_post_title( 0, [] );
} );

add_action( 'admin_enqueue_scripts', function() {
	wp_enqueue_style( 'classifai-admin', plugin_dir_url( __FILE__ ) . 'build/configstyle.css', [], '1.0.0' );
} );

add_action( 'admin_footer', function() {
	?>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script>
		(($) => {
			$(document).ready(function() {
				$('.classifai-settings__multi-select').select2();
			});
		})(jQuery)
	</script>
	<?php
} );
