<?php

namespace Classifai\Admin\Fields;

class TextControl extends FieldControl {
	public function __construct( $settings, $id, $args ) {
		parent::__construct( $settings, $id, $args );
	}

	public function sanitize_setting( $value ) {
		return sanitize_text_field( $value );
	}

	public function render() {
		?>
			<div class="classifai-settings__input-wrapper">
				<label for="<?php echo esc_attr( $this->get_id() ); ?>">
					<?php echo esc_html( $this->get_label() ); ?>
				</label>
				<p class="description">
					<?php echo $this->get_description(); ?>
				</p>
				<input
					type="text"
					id="<?php echo esc_attr( $this->get_id() ); ?>"
					name="<?php echo esc_attr( $this->get_field_name() ) ?>"
					value="<?php echo esc_attr( $this->settings->get_setting( $this->get_id() ) ); ?>"
				/>
			</div>
		<?php
	}
}
