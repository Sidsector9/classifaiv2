<?php

namespace Classifai\Admin\Fields;

use Classifai\Admin\Settings;

abstract class FieldControl implements IFieldControl {
	protected $id;

	protected $settings;

	protected $args;

	protected $option_name;

	public function __construct( $settings, $id, $args ) {
		$this->settings = $settings;
		$this->args = $args;
		$this->id = $id;

		if ( 'feature' === $this->settings->get_context() ) {
			$this->option_name = Settings::FEATURE_KEY;
		} else if ( 'provider' === $this->settings->get_context() ) {
			$this->option_name = Settings::PROVIDER_KEY;
		}
	}

	public function get_id() {
		return $this->id;
	}

	public function get_label() {
		return $this->args['label'] ?? '';
	}

	public function get_description() {
		return $this->args['description'] ?? '';
	}

	public function get_default() {
		return $this->args['default'] ?? '';
	}

	public function get_field_name() {
		return sprintf(
			'%s[%s][%s]',
			$this->option_name,
			$this->settings->get_context_key(),
			$this->id
		);
	}
}
