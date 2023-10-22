<?php

namespace Classifai\Admin\Fields;

class CheckboxControl extends FieldControl {
	public function __construct( $settings, $id, $args ) {
		parent::__construct( $settings, $id, $args );
	}

	public function sanitize_setting( $value ) {
		if ( empty( $value ) || 'on' !== $value ) {
			return 'off';
		}

		return 'on';
	}

	public function render() {
		?>
			<div class="classifai-settings__input-wrapper">
				<input type="hidden" name="<?php echo esc_attr( $this->get_field_name() ) ?>" value="off">
				<label for="<?php echo esc_attr( $this->get_id() ); ?>">
					<input
						type="checkbox"
						id="<?php echo esc_attr( $this->get_id() ); ?>"
						name="<?php echo esc_attr( $this->get_field_name() ) ?>"
						<?php checked( $this->settings->get_setting( $this->settings->get_context_key(), $this->get_id() ), 'on' ); ?>
					/>
					<?php echo esc_html( $this->get_label() ); ?>
				</label>
				<p class="description">
					<?php echo $this->get_description(); ?>
				</p>
			</div>
		<?php
	}
}
