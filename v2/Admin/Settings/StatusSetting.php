<?php

namespace Classifai\Admin\Settings;

class StatusSetting {
	private $manager;

	public function __construct( $manager ) {
		$this->manager = $manager;
	}

	public function sanitize( $value ) {
		$sanitized_value = sanitize_text_field( $value );;
		return $value === $sanitized_value ? 'on' : 'off';
	}

	public function save( $feature, $value ) {
		$value = $this->sanitize( $value );
		$this->manager->set_setting( $feature, 'status', $value );
	}

	public function render( $feature ) {
		$status_value = $this->manager->get_setting( $feature, 'status' );
		?>
		<label for="classifai_<?php echo $feature; ?>_status">Status:</label>
		<input type="checkbox" name="classifai_features_config[<?php echo $feature; ?>][status]" id="classifai_<?php echo $feature; ?>_status" <?php checked('on', $status_value); ?>>
		<p class="description">Description of the status setting.</p>
		<?php
	}
}

