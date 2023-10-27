<?php

namespace Classifai\Services;

/**
 * Class Provider
 * Base class for all the service provider classes.
 * This class is provides a way to access the feature
 * settings and the provider settings inside the subclasses.
 *
 * @package Classifai\Services
 */
class Provider {
	public $feature_settings;
	public $provider_settings;

	public function __construct( $feature_settings, $provider_settings ) {
		$this->feature_settings = $feature_settings;
		$this->provider_settings = $provider_settings;
	}
}
