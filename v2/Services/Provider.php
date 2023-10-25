<?php

namespace Classifai\Services;

class Provider {
	public $feature_settings;
	public $provider_settings;

	public function __construct( $feature_settings, $provider_settings ) {
		$this->feature_settings = $feature_settings;
		$this->provider_settings = $provider_settings;
	}
}
