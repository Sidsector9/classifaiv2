<?php

namespace Classifai\Admin\Fields;

class NumberControl extends FieldControl {
	public function __construct( $settings, $id, $args ) {
		parent::__construct( $settings, $id, $args );
	}

	public function sanitize_setting( $value ) {
		$value = absint( $value );

		if ( $value >= $this->get_min() && $value <= $this->get_max() ) {
			return $value;
		}

		return $this->get_default();
	}

	private function get_min() {
		return isset( $this->args['min'] ) ? $this->args['min'] : 0;
	}

	private function get_max() {
		return isset( $this->args['max'] ) ? $this->args['max'] : 100;
	}

	private function get_step() {
		return isset( $this->args['step'] ) ? $this->args['step'] : 1;
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
					type="number"
					id="<?php echo esc_attr( $this->get_id() ); ?>"
					name="<?php echo esc_attr( $this->get_field_name() ) ?>"
					min="<?php echo esc_attr( $this->get_min() ); ?>"
					max="<?php echo esc_attr( $this->get_max() ); ?>"
					step="<?php echo esc_attr( $this->get_step() ); ?>"
					value="<?php echo esc_attr( $this->settings->get_setting( $this->get_id() ) ); ?>"
				/>
			</div>
		<?php
	}
}
