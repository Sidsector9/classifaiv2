<?php

namespace Classifai\Features\AccessControl\Types;

class AccessControlByRole {
	public const ID = 'access_control_by_role';

	public $roles;

	public function __construct( $roles ) {
		$this->roles = $roles;
	}

	public function has_access() {
		$current_user = wp_get_current_user();

		return count( array_intersect( $current_user->roles, $this->roles ) ) > 0;
	}

	public function get_access_error_code() {
		return self::ID;
	}

	public function get_access_error_message() {
		return __( 'The current role does not have access to this feature.' );
	}
}
