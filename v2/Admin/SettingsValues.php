<?php

namespace Classifai\Admin;

/**
 * Class SettingsValues
 *
 * Couldn't think of a better name.
 * This class consists of helper methods that return values to
 * populate values in the settings page.
 */
class SettingsValues {
	public static function get_user_roles() {
		global $wp_roles;
		$roles = $wp_roles->get_names();
		return $roles;
	}

	public static function get_public_post_types() {
		$post_types = get_post_types( [ 'public' => true ], 'names' );

		return $post_types;
	}
}
