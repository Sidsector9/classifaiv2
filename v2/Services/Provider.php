<?php

namespace Classifai\Services;

class Provider {
	public $settings;

	public function __construct( $settings ) {
		$this->settings = $settings->get_setting( $settings->get_context(), $settings->get_context_key() );
	}
}
