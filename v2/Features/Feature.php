<?php

namespace Classifai\Features;

use \Classifai\Features\AccessControl\AccessControlRegistry;
use \Classifai\Services\ProviderRegistry;

abstract class Feature implements IFeature {
	public const ID = '';

	public $settings;

	private $access_control_types;

	private $providers;

	public function __construct( $settings ) {
		$this->settings = $settings->get_setting( $settings->get_context(), $settings->get_context_key() );
		$this->access_control_types = AccessControlRegistry::get_list();
		$this->providers = ProviderRegistry::get_list();
	}

	public function get_filtered_access_control_types() {
		$access_control_types = [];

		foreach ( $this->settings as $access_control_key => $access_control ) {
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
			$access_control = new $access_control_class( $this->settings[ $access_control_key ] );

			if ( ! $access_control->has_access() ) {
				return false;
			}
		}

		return true;
	}
}

