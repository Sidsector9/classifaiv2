<?php

namespace Classifai\Admin;

class FeaturesSettingManager {
	private $settings;

	public function __construct() {
		$this->settings = get_option( 'classifai_features_config', [] );

		// Initialize the settings for each feature
		$feature = 'post_title_generation';
		$this->settings[ $feature ] = isset( $this->settings[ $feature ] ) ? $this->settings[ $feature ] : [];
	}

	public function save() {
		update_option( 'classifai_features_config', $this->settings );
	}

	public function get_setting( $feature, $setting ) {
		return $this->settings[ $feature ][ $setting ] ?? null;
	}

	public function set_setting( $feature, $setting, $value ) {
		// Get the current settings
		$settings = get_option( 'classifai_features_config', [] );

		// Update the specific setting for the feature
		$settings[ $feature ][ $setting ] = $value;

		// Save the updated settings
		update_option( 'classifai_features_config', $settings );

		// Update the local settings variable
		$this->settings = $settings;
	}
}
