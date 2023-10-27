<?php

namespace Classifai\Features;

class FeaturePostTitleGeneration extends Feature {
	public const ID = 'post_title_generation';

	/**
	 * Generates result for the Post Title Generation feature.
	 *
	 * @todo document the required arguments.
	 */
	public function generate( ...$args ) {
		$has_access = $this->has_access();

		if ( is_wp_error( $has_access ) ) {
			return $has_access;
		}

		/** @var ChatGPTPostTitleGeneration $provider */
		$provider = $this->get_feature_provider();

		return $provider->get_result( ...$args );
	}
}
