<?php

namespace Classifai\Features\AccessControl\Types;

use \Classifai\Features\AccessControl\IAccessControl;

/**
 * Access control by role.
 */
class AccessControlByRole implements IAccessControl {
	public const ID = 'access_control_by_role';

	/**
	 * The roles that have access to the feature.
	 *
	 * @var array
	 */
	private $roles = [];

	/**
	 * AccessControlByRole constructor.
	 *
	 * @param array $roles
	 */
	public function __construct( array $roles = [] ) {
		$this->roles = $roles;
	}

	/**
	 * Returns true if the current user has access to the feature.
	 */
	public function has_access() {
		$current_user = wp_get_current_user();

		return count( array_intersect( $current_user->roles, $this->roles ) ) > 0;
	}

	/**
	 * Returns the access error code.
	 */
	public function get_access_error_code() {
		return self::ID;
	}

	/**
	 * Returns the access error message.
	 */
	public function get_access_error_message() {
		return __( 'The current role does not have access to this feature.', 'classifai' );
	}
}
