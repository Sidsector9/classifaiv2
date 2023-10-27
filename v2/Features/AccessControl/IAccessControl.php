<?php

namespace Classifai\Features\AccessControl;

/**
 * Interface for access control.
 *
 * @todo Implement a priority field for access control types.
 *       Access control types with higher priority will be checked first.
 *       Access control types with the same value will be "OR"ed.
 *       Access control types with different values will be "AND"ed.
 */
interface IAccessControl {
	/**
	 * Returns true if the feature is accessible
	 * for the current access control types.
	 *
	 * @todo Return an error code and boolean.
	 *
	 * @return boolean
	 */
	public function has_access();

	/**
	 * Returns the access error code.
	 *
	 * @return string
	 */
	public function get_access_error_code();

	/**
	 * Returns the access error message.
	 *
	 * @return string
	 */
	public function get_access_error_message();
}
