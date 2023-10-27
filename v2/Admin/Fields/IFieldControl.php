<?php

namespace Classifai\Admin\Fields;

/**
 * Interface IFieldControl
 */
interface IFieldControl {

	/**
	 * The FieldControl subclass will sanitize the setting value.
	 */
	public function sanitize_setting( $value );

	/**
	 * The FieldControl subclass will render the field.
	 */
	public function render();
}
