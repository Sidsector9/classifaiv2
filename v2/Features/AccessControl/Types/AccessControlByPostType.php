<?php

namespace Classifai\Features\AccessControl\Types;

class AccessControlByPostType {
	public const ID = 'access_control_by_post_type';

    public $post_types;

    public function __construct( $post_types ) {
		$this->post_types = $post_types;
	}

	public function has_access() {
		$all_post_stypes = array_keys(
			get_post_types( array( 'public' => true ), 'names' )
		);

		return count( array_intersect( $all_post_stypes, $this->post_types ) ) > 0;
	}

	public function get_access_error_code() {
		return self::ID;
	}

	public function get_access_error_message() {
		return __( 'The current post type does not support this feature.' );
	}
}
