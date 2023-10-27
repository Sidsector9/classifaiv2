<?php

namespace Classifai\Features\AccessControl;

use \Classifai\Features\AccessControl\Types\AccessControlByRole;
use \Classifai\Features\AccessControl\Types\AccessControlByPostType;

/**
 * Registry for access control types.
 */
class AccessControlRegistry {
	/**
	 * Returns the array of access control types.
	 *
	 * @return array
	 */
	public static function get_list() {
		return [
			AccessControlByRole::ID => '\Classifai\Features\AccessControl\Types\AccessControlByRole',
			AccessControlByPostType::ID => '\Classifai\Features\AccessControl\Types\AccessControlByPostType',
		];
	}
}
