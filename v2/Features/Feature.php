<?php

namespace Classifai\Features;

use Classifai\Admin\Settings;
use \Classifai\Features\AccessControl\AccessControlRegistry;
use \Classifai\Services\ProviderRegistry;

abstract class Feature implements IFeature {
	public const ID = '';

	public $feature_settings;

	private $access_control_types;

	private $providers_list;

	public function __construct( $feature_settings ) {
		$this->feature_settings = $feature_settings->get_setting();
		$this->access_control_types = AccessControlRegistry::get_list();
		$this->providers_list = ProviderRegistry::get_list();
	}

	public function get_filtered_access_control_types() {
		$access_control_types = [];

		foreach ( $this->feature_settings as $access_control_key => $access_control ) {
			if ( ! isset( $this->access_control_types[ $access_control_key ] ) ) {
				continue;
			}

			$access_control_types[ $access_control_key ] = $this->access_control_types[ $access_control_key ];
		}

		return $access_control_types;
	}

	public function has_access() {
		$access_control_types = $this->get_filtered_access_control_types();

		foreach ( $access_control_types as $access_control_key => $access_control_class ) {
			$access_control = new $access_control_class( $this->feature_settings[ $access_control_key ] );

			if ( ! $access_control->has_access() ) {
				return false;
			}
		}

		return true;
	}

	protected function get_feature_provider() {
		$provider_id = $this->feature_settings['provider'];

		if ( ! isset( $this->providers_list[ $provider_id ] ) ) {
			return new \WP_Error( 'invalid_provider', __( 'Invalid provider.' ) );
		}

		$provider_class = '\\' . $this->providers_list[ $provider_id ]['class'];
		$settings = new Settings( 'provider', $provider_class::CONNECTOR_ID );
		$provider_settings = $settings->get_setting();

		return new $provider_class( $this->feature_settings, $provider_settings );
	}
}

