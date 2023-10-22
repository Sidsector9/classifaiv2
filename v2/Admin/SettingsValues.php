<?php

namespace Classifai\Admin;

class SettingsValues {
	public static function get_user_roles() {
		global $wp_roles;
		$roles = $wp_roles->get_names();
		return $roles;
	}

	public static function get_public_post_types() {
		$post_types = get_post_types( array( 'public' => true ), 'names' );

		return $post_types;
	}
}
