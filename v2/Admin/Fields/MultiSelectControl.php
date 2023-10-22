<?php

namespace Classifai\Admin\Fields;

class MultiSelectControl extends FieldControl {
	public function __construct( $settings, $id, $args ) {
		parent::__construct( $settings, $id, $args );
	}

	public function sanitize_setting( $values ) {
		$sanitized_values = array_map( 'sanitize_text_field', $values );

		return array_filter( $sanitized_values, function( $value ) {
			return ! empty( $value );
		} );
	}

	public function render() {
		$db_value = $this->settings->get_setting( $this->settings->get_context_key(), $this->get_id() );
		?>
			<div class="classifai-settings__input-wrapper">
				<label for="<?php echo esc_attr( $this->get_id() ); ?>"><?php echo esc_html( $this->get_label() ); ?></label>
				<input type="hidden" name="<?php echo $this->get_field_name(); ?>[]">
				<p class="description">
					<?php echo esc_html( $this->get_description() ); ?>
				</p>
				<select
					class="classifai-settings__multi-select"
					name="<?php echo $this->get_field_name(); ?>[]"
					multiple
				>
					<?php foreach ( $this->args['options'] as $value => $label ) : ?>
						<option <?php selected( in_array( $value, $db_value, true ), true ); ?> value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php
	}
}
