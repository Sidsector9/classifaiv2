<?php

namespace Classifai\Features\AccessControl;

use \Classifai\Features\AccessControl\Types\AccessControlByRole;
use \Classifai\Features\AccessControl\Types\AccessControlByPostType;

class AccessControlRegistry {
	public static function get_list() {
		return [
			AccessControlByRole::ID => '\Classifai\Features\AccessControl\Types\AccessControlByRole',
			AccessControlByPostType::ID => '\Classifai\Features\AccessControl\Types\AccessControlByPostType',
		];
	}
}
