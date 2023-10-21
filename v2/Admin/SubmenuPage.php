<?php

namespace Classifai\Admin;

use \Classifai\Admin\FeaturesSettingManager;
use \Classifai\Admin\Settings\StatusSetting;
use \Classifai\Admin\Settings\URLSetting;

class SubmenuPage {
	private $feature_page;

	public function __construct() {
		$this->feature_page = isset( $_GET['feature_page'] ) ? wp_unslash( sanitize_text_field( $_GET['feature_page'] ) ) : 'post_title_generation';
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_menu', [ $this, 'register_submenu_page' ] );
	}

	function register_settings() {
		register_setting( 'classifai_settings_group', 'classifai_features_config' );
	}

	public function register_submenu_page() {
		add_submenu_page(
			'tools.php',
			__( 'ClassifAI Settings' ),
			__( 'ClassifAI Settings' ),
			'manage_options',
			'classifai',
			[ $this, 'render_settings_page' ]
		);
	}

	function render_settings_page() {
		?>
		<h1>Ha!</h1>
		<form action="options.php" method="POST">
			<?php
				$status_setting = new StatusSetting( new FeaturesSettingManager() );
				$url_setting = new URLSetting( new FeaturesSettingManager() );
	
				$status_setting->render( 'post_title_generation' );
				$url_setting->render( 'post_title_generation' );
	
				settings_fields( 'classifai_settings_group' );
				submit_button();
			?>
		</form>
		<?php
	}
}
