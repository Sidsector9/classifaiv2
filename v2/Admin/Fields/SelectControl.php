<?php

namespace Classifai\Admin\Fields;

class SelectControl extends FieldControl {
	public function __construct( $settings, $id, $args ) {
		parent::__construct( $settings, $id, $args );
	}

	public function sanitize_setting( $value ) {
		return sanitize_text_field( $value );
	}

	public function render() {
		$db_value = $this->settings->get_setting( $this->get_id() );
		?>
			<div class="classifai-settings__input-wrapper">
				<label for="<?php echo esc_attr( $this->get_id() ); ?>">
					<?php echo esc_html( $this->get_label() ); ?>
				</label>
				<input type="hidden" name="<?php echo $this->get_field_name(); ?>[]">
				<p class="description">
					<?php echo esc_html( $this->get_description() ); ?>
				</p>
				<select
					class="classifai-settings__select"
					name="<?php echo $this->get_field_name(); ?>"
				>
					<?php foreach ( $this->args['options'] as $value => $label ) : ?>
						<option <?php selected( $db_value, $value ); ?> value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php
	}
}
