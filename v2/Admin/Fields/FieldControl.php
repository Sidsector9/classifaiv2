<?php

namespace Classifai\Admin\Fields;

use Classifai\Admin\Settings;

/**
 * Class FieldControl
 */
abstract class FieldControl implements IFieldControl {
	/**
	 * ID of the setting key that holds the corresponding value.
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 * Settings object.
	 *
	 * @var \Classifai\Admin\Settings
	 */
	protected $settings = null;

	/**
	 * Additional rguments passed to the FieldControl.
	 *
	 * @var array
	 */
	protected $args = [];

	protected $option_name;

	/**
	 * FieldControl constructor.
	 *
	 * @param \Classifai\Admin\Settings $settings
	 * @param string $id
	 * @param array $args
	 */
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

	/**
	 * Returns the ID of the FieldControl.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Returns the label of the FieldControl.
	 *
	 * @return string
	 */
	public function get_label() {
		return $this->args['label'] ?? '';
	}

	/**
	 * Returns the description of the FieldControl.
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->args['description'] ?? '';
	}

	/**
	 * Returns the default value of the FieldControl.
	 *
	 * @return string
	 */
	public function get_default() {
		return $this->args['default'] ?? '';
	}

	/**
	 * Prepares and returns a name for the FieldControl.
	 *
	 * @return string
	 */
	public function get_field_name() {
		return sprintf(
			'%s[%s][%s]',
			$this->option_name,
			$this->settings->get_context_key(),
			$this->id
		);
	}
}
