<?php

namespace Classifai\Features\AccessControl\Types;

use \Classifai\Features\AccessControl\IAccessControl;

class AccessControlByPostType implements IAccessControl {
	/**
	 * The post types that have access to the feature.
	 */
	public const ID = 'access_control_by_post_type';

	/**
	 * The post types that have access to the feature.
	 *
	 * @var array
	 */
	private $post_types = [];

	/**
	 * AccessControlByPostType constructor.
	 *
	 * @var array $post_types
	 */
	public function __construct( array $post_types = [] ) {
		$this->post_types = $post_types;
	}

	/**
	 * Returns true if the current post type has access to the feature.
	 */
	public function has_access() {
		$all_post_stypes = array_keys(
			get_post_types( [ 'public' => true ], 'names' )
		);

		return count( array_intersect( $all_post_stypes, $this->post_types ) ) > 0;
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
		return __( 'The current post type does not support this feature.', 'classifai' );
	}
}
