<?php

namespace Classifai\Features;

class FeatureExcerptGeneration {
	public const ID = 'post_excerpt_generation';

	public static $title;

	public function __construct() {
		self::$title = __( 'Post Excerpt Generation', 'classifai' );
	}
}
