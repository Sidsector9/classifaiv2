<?php
/**
 * Plugin Name: ClassifAI v2
 */

use \Classifai\Admin\Settings;

define( 'CLASSIFAI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CLASSIFAI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once __DIR__ . '/vendor/autoload.php';

add_action( 'init', function() {
	if ( is_admin() ) {
		$settings = new Settings();
		$settings->admin_init();
	}

	require_once 'v2/Features/FeatureHelpers.php';

	if ( ! is_admin() ) {
		generate_post_title( 1, [] );
	}
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
